<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\FileData;
use App\Services\FileDataService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                'cash_advance_allocation_id' => 'required|exists:cash_advance_allocations,id',
                'location' => 'required|in:onsite,offsite',
            ]);
            $failedRecords = [];

            DB::beginTransaction(); 

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->getPathname();
            $fileContent = file_get_contents($filePath);
            $encoding = mb_detect_encoding($fileContent, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding ?: 'UTF-8');
            $csv = Reader::createFromString($fileContent);
            $csv->setHeaderOffset(0);

            // Read the records in chunks
            $chunkSize = 500;
            $records = $csv->getRecords();
            $chunk = [];

            $storedFile = File::create([
                'file_name' => $fileName,
                'cash_advance_allocation_id' => $request->input('cash_advance_allocation_id'),
                'total_amount' => 0,
                'total_beneficiary' => 0,
                'location' => $request->input('location'),
            ]);

            foreach ($records  as $record) {
                try {
                    $validatedRecord = $this->validateRecord($record);
                    $formattedDateTimeClaimed = $this->formatDateTimeClaimed($record['TIME_CLAIMED']);
                        $data = [
                        'id' => Str::ulid(),
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
                    $failedRecords[] = ['data' => $record, 'reason' => $e->getMessage()];
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

            return redirect()->back()->with('success', 'File uploaded and data saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred. Please try again. ' . $e->getMessage());
        }
    }

    private function validateRecord(array $record)
    {
        $validator = Validator::make($record, [
            'CONTROL_NUMBER' => 'required|string|max:255',
            'LASTNAME' => 'required|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/',
            'FIRSTNAME' => 'required|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/',
            'MIDDLENAME' => 'nullable|string|max:255|regex:/^[A-Za-zÑñ\s\-.]+$/',
            'EXT_NAME' => 'nullable|string|max:50|regex:/^[A-Za-zÑñ\s\-.]+$/',
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
    public function index(Request $request, $sdo)
    {
        $perPage = $request->input('perPage', 5);
    
        $query = File::where('cash_advance_allocation_id', $sdo);
    
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $terms = explode(' ', $searchTerm); 

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('file_name', 'LIKE', "%{$term}%");
                    });
                }
            });
        }
    
        $file_list = $query->paginate($perPage);
    
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

            return response()->json(['success' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    /**
     * Get the list of file IDs for a specific SDO.
     */
    public function getIdToRCD($id)
    {
        $file_ids = File::where('cash_advance_allocation_id', $id)->pluck('id');
        return $file_ids->toArray();
    }

    /**
     * Get the overall total amount and total beneficiaries for a specific SDO.
     */
    public function getSdoTotal($sdo)
    {
        $files = File::where('cash_advance_allocation_id', $sdo)->get();

        $totalAmount = $files->sum('total_amount');
        $totalBeneficiaries = $files->sum('total_beneficiary');

        return response()->json([
            'overall_total_amount' => $totalAmount,
            'overall_total_beneficiaries' => $totalBeneficiaries
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->input('file_id');
        $file = File::findOrFail($id);

        $request->validate([
            'location' => 'required|in:onsite,offsite',
        ]);

        $file->update([
            'location' => $request->input('location'),
        ]);

        return redirect()->back()->with('success', 'Location updated successfully!');
    }

  
}
