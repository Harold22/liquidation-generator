<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\FileData;
use App\Services\FileDataService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    protected $fileDataService;

    public function __construct(FileDataService $fileDataService)
    {
        $this->fileDataService = $fileDataService;
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimetypes:text/csv,text/plain',
                'cash_advance' => 'required|exists:cash_advances,id',
                'file_name' => 'unique:files,file_name,NULL,id,cash_advance_id,' . $request->input('cash_advance'),
            ]);

            $failedRecords = [];
            
            DB::beginTransaction();

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->getPathname();
            $fileContent = file_get_contents($filePath);
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'auto');
            $csv = Reader::createFromString($fileContent);
            $csv->setHeaderOffset(0);

            // Read the records in chunks
            $chunkSize = 500;
            $records = $csv->getRecords();
            $chunk = [];

            $storedFile = File::create([
                'file_name' => $fileName,
                'cash_advance_id' => $request->input('cash_advance'),
                'total_amount' => 0,
                'total_beneficiary' => 0,
            ]);

            foreach ($records as $record) {
                try {

                    $validatedRecord = $this->validateRecord($record);

                    $formattedDateTimeClaimed = $this->formatDateTimeClaimed($record['TIME_CLAIMED']);

                    $data = [
                        'file_id' => $storedFile->id,
                        'control_number' => $validatedRecord['CONTROL_NUMBER'],
                        'lastname' => $validatedRecord['LASTNAME'],
                        'firstname' => $validatedRecord['FIRSTNAME'],
                        'middlename' => $validatedRecord['MIDDLENAME'] ?? null,
                        'extension_name' => $validatedRecord['EXT_NAME'] === '' ? null : $validatedRecord['EXT_NAME'],
                        'birthdate' => isset($validatedRecord['BIRTHDATE'])
                            ? Carbon::createFromFormat('m/d/Y', $validatedRecord['BIRTHDATE'])->format('Y-m-d')
                            : null,
                        'status' => $validatedRecord['STATUS'],
                        'date_time_claimed' => $formattedDateTimeClaimed,
                        'remarks' => $validatedRecord['REMARKS'] === '' ? null : $validatedRecord['REMARKS'],
                        'amount' => $validatedRecord['AMOUNT'],
                        'assistance_type' => $validatedRecord['TYPE OF ASSISTANCE'],
                    ];

                    $chunk[] = $data;

                    if (count($chunk) >= $chunkSize) {
                        $this->fileDataService->create($chunk); 
                        $chunk = []; 
                    }
    

                } catch (\Exception $e) {
                    $failedRecords[] = [
                        'data' => $record,
                        'reason' => $e->getMessage(),
                    ];
                }
            }
            if (count($chunk) > 0) {
                $this->fileDataService->create($chunk);
            }

            if (count($failedRecords) > 0) {
                DB::rollback();
                return $this->downloadFailedCSV($failedRecords);
            }

            $this->updateFileTotals($storedFile);
            DB::commit();

            return redirect()->back()->with('message', 'File uploaded and data saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    private function validateRecord(array $record)
    {
        $validator = Validator::make($record, [
            'CONTROL_NUMBER' => 'required|string|max:255',
            'LASTNAME' => 'required|string|max:255',
            'FIRSTNAME' => 'required|string|max:255',
            'MIDDLENAME' => 'nullable|string|max:255',
            'EXT_NAME' => 'nullable|string|max:50',
            'BIRTHDATE' => 'nullable',
            'STATUS' => 'required|string|max:50',
            'TIME_CLAIMED' => 'required|regex:/^\d{1,2}\/\d{1,2}\/\d{4} \d{2}:\d{2}$/',
            'REMARKS' => 'nullable|string|max:500',
            'AMOUNT' => 'required|numeric|min:0',
            'TYPE OF ASSISTANCE' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception(implode(', ', $validator->errors()->all()));
        }

        return $record;
    }

    private function formatDateTimeClaimed(string $dateTime): string
    {
        try {
            return Carbon::createFromFormat('m/d/Y H:i', $dateTime)
                ->format('Y-m-d H:i:00');
        } catch (\Exception $e) {
            throw new \Exception("Invalid date format for TIME_CLAIMED: $dateTime");
        }
    }


    private function updateFileTotals(File $file)
    {
        $file->total_amount = $file->file_data()->sum('amount');
        $file->total_beneficiary = $file->file_data()->count();
        $file->save();
    }

    /**
     * Download the failed CSV records.
     */
    private function downloadFailedCSV($failedRecords)
    {
        $headers = [
            'CONTROL_NUMBER',
            'LASTNAME',
            'FIRSTNAME',
            'MIDDLENAME',
            'EXT_NAME',
            'BIRTHDATE',
            'STATUS',
            'TIME_CLAIMED',
            'REMARKS',
            'AMOUNT',
            'TYPE OF ASSISTANCE',
            'REASON' 
        ];
    
        $response = new StreamedResponse(function () use ($failedRecords, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers); 
    
            foreach ($failedRecords as $record) {
               
                $row = array_merge(
                    $record['data'], 
                    ['reason' => $record['reason']]
                );
    
                fputcsv($handle, $row); 
            }
    
            fclose($handle);
        });
    
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="failed_uploads.csv"');
    
        return $response;
    }
    

    /**
     * Show the list of files for a specific SDO (cash advance).
     */
    public function show($sdo)
    {
        $file_list = File::where('cash_advance_id', $sdo)->paginate(5);

        return response()->json($file_list->toArray());
    }

    /**
     * Delete the file from storage.
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if ($file) {
            $file->delete(); 

            return response()->json(['message' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    /**
     * Get the list of file IDs for a specific SDO.
     */
    public function getIdToRCD($id)
    {
        $file_ids = File::where('cash_advance_id', $id)->pluck('id');
        return $file_ids->toArray();
    }

    /**
     * Get the overall total amount and total beneficiaries for a specific SDO.
     */
    public function getSdoTotal($sdo)
    {
        $files = File::where('cash_advance_id', $sdo)->get();

        $totalAmount = $files->sum('total_amount');
        $totalBeneficiaries = $files->sum('total_beneficiary');

        return response()->json([
            'overall_total_amount' => $totalAmount,
            'overall_total_beneficiaries' => $totalBeneficiaries
        ]);
    }
}
