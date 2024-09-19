<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true; // Set true if authorization is not required
    }

    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'file' => 'required|file|max:10240', // File size limit
            'part' => 'required|integer|min:0',  // The file part number
            'fileName' => 'required|string',     // File name
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'file.required' => 'The file is required to download.',
            'file.file' => 'Incorrect file format.',
            'file.max' => 'The file size should not exceed 10MB per part.',
            'part.required' => 'It is necessary to specify the part number.',
            'part.integer' => 'The part number must be an integer.',
            'fileName.required' => 'It is necessary to specify the name of the file.',
        ];
    }
}
