<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if (request()->isMethod('post')) {
            return [
                'title' => 'required|string|max:258',
                'tgl' => 'required|string',
                'deskripsi' => 'string',
                'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ];
        } else {
            return [
                'title' => 'required|string|max:258',
                'tgl' => 'required|string',
                'deskripsi' => 'string',
                'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ];
        }
    }


    public function messages()
    {
        if (request()->isMethod('post')) {
            return [
                'title.required' => 'Title is required!',
                'tgl.required' => 'Tgl is required!',
                'deskripsi.required' => 'Deskripsi is required!',
                'file.required' => 'File is required',
            ];
        } else {
            return [
                'title.required' => 'Title is required!',
                'tgl.required' => 'Tgl is required!',
                'deskripsi.required' => 'Deskripsi is required!',
            ];   
        }
    }
}
