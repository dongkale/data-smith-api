<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storages = Storage::all();    
        return response()->json($storages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        // $validator = Validator::make($request->all(), [
        //   "name" => "required",
        //   "data" => "required"
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         "result_code" => -1,
        //         "result_message" => $validator->errors(),
        //     ]);
        // }

        // $name = $request->name;        
        // $data = $request->data;

        $storages = Storage::create($request->all()); 
            
        return response()->json($storages, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $storage = Storage::find($id);    
        return response()->json($storage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $storage = Storage::find($id);
        $storage->update($request->all());    
        return response()->json($storage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {    
        Storage::destroy($id);
        return response()->json(null, 204);
    }

    public function fileUpload(Request $request,)
    {    
        // $directroy_date = substr(date('Ym', time()), 2, 6);
        // // $save_directory = 'file/' . $channel_id . '/' . $directroy_date;

        $fileDirectory = '/save_files';

        // $contract_FileName = $request->file('file')->getClientOriginalName();
        // $contractFile_path = $request->file('file')->storeAs($File_Directory, $contract_FileName, 'local');
        // $contractFile_size = $request->file('file')->getSize();
        // $contractFile_extension = $request->file('file')->extension();
        // // $contractFile_extension = Str::lower($contractFile_extension);

        $storage_path = storage_path();

        // 화일 저장
        $file = $request->file('file');
        if ($file) {
            $filePath = $file->storeAs($fileDirectory, $file->getClientOriginalName());
            $fileSize = $request->file('file')->getSize();
            $fileExtension = $request->file('file')->extension();

            // 화일 로그 Log
            Log::info('File Upload', [
                'file' => $file->getClientOriginalName(),
                'size' => $fileSize,
                'extension' => $fileExtension,
                'path' => $storage_path . '/app/' . $filePath,
            ]);   
        }

        return response()->json('');
    }
}
