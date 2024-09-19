<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;

class FileUploadController extends Controller
{
    /**
     * Upload file by part
     *
     * @param FileUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(FileUploadRequest $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('file');
        $part = $request->input('part');
        $fileName = time() . '_' . $request->input('fileName');

        // Temporary folder for storing file parts
        $tempDirectory = storage_path('app/uploads/temp/' . $fileName);

        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }

        // Saving part of the file
        $file->move($tempDirectory, $part);

        // Combine of the file after loading all parts
        $this->combineFile($tempDirectory, $fileName);

        return response()->json([
            'message' => 'Uploaded successfully',
        ], 200);
    }


    /**
     * Combine of the file after loading all parts
     *
     * @param $tempDirectory
     * @param $fileName
     * @return void
     */
    public function combineFile($tempDirectory, $fileName)
    {
        $path = storage_path('app/uploads/' . $fileName);
        $outputFile = fopen($path, 'w');

        // Merging all parts of a file
        foreach (scandir($tempDirectory) as $part) {
            if ($part == '.' || $part == '..') {
                continue;
            }

            $chunk = file_get_contents($tempDirectory . '/' . $part);
            fwrite($outputFile, $chunk);
        }

        fclose($outputFile);

        // Cleaning temporary files
        array_map('unlink', glob("$tempDirectory/*"));
        rmdir($tempDirectory);
    }
}
