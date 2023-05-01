<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TblCreditBureauMasterDistrict;
use Illuminate\Support\Facades\DB;
use Exception;
use League\Csv\Reader;
use League\Csv\Statement;

class CsvController extends Controller
{

    public function csvImport(Request $request) {
        try {
            if (!$request->hasFile('csv')) {
                return response()->json(['status'=> false,'message' => 'No CSV file uploaded']);
            }
    
            $validator = Validator::make($request->all(), [
                'csv' => 'required|mimes:csv,txt',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status'=> false,'errors' => $validator->errors()], 400);
            }
    
            $file = $request->file('csv');

            // Create a new Reader object from the uploaded file
            $csv = Reader::createFromPath($file->getPathname());

            // Use the first row of the CSV as headers
            $csv->setHeaderOffset(0);

            // Get the total number of records in the CSV
            $totalRecords = count($csv);

            // Define the batch size (number of records to process per batch)
            $batchSize = 1000;

            // Calculate the number of batches
            $numBatches = ceil($totalRecords / $batchSize);
            $colArray = ['Month','Quarter','State','District'];
            $succeedRecord = 0;
            for ($i = 0; $i < $numBatches; $i++) {
                // Get the next batch of records
                $offset = $i * $batchSize;
                $records = (new Statement())->offset($offset)->limit($batchSize)->process($csv);

                $finalData = array();
                foreach ($records as $key => $record) {
                    $data = [];
                    foreach ($colArray as $value) {
                        if (array_key_exists($value,$record)) {
                            $data[$value] = $record[$value];
                        }
                    }
                    $data['jsonData'] = json_encode($record);
                    $finalData[] = $data;
                }
                $result = TblCreditBureauMasterDistrict::insert($finalData);
                if ($result) {
                    $succeedRecord = $succeedRecord + count($finalData);
                }
                // DB::table('tbl_credit_bureau_master_districts')->upsert(
                //                     $data,
                //                     ['month','district'],
                //                     ['jsonData']
                //                 );
            }

            return response()->json(['status'=> true, "msg"=>"records imported successfully", 'succeed_records'=>$succeedRecord], 200);

        } catch (Exception $e) {
            return response()->json(['status'=> false,'error' => $e], 500);
        }

        
        
    
    }
}
