<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return false;
        return true;
    }
 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:258',
                'email' => 'required|string',
                'password' => 'required|string',
                'alamat' => 'string',
                'no_tlfn' => 'string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'ttd' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        } else {
            return [
                'name' => 'required|string|max:258',
                'email' => 'required|string',
                'password' => 'required|string',
                'alamat' => 'string',
                'no_tlfn' => 'string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'ttd' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }
    }
     
    public function messages()
    {
        if(request()->isMethod('post')) {
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                'password.required' => 'Password is required!',
                'foto.required' => 'foto is required'
                // 'foto.image' => 'Foto must be an image.',
                // 'foto.mimes' => 'Foto must be a file of type: jpeg, png, jpg, gif.',
                // 'foto.max' => 'Foto may not be greater than 2048 kilobytes.',
                // 'ttd.image' => 'TTD must be an image.',
                // 'ttd.mimes' => 'TTD must be a file of type: jpeg, png, jpg, gif.',
                // 'ttd.max' => 'TTD may not be greater than 2048 kilobytes.'
            ];
        } else {
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                'password.required' => 'Password is required!',
                // 'foto.image' => 'Foto must be an image.',
                // 'foto.mimes' => 'Foto must be a file of type: jpeg, png, jpg, gif.',
                // 'foto.max' => 'Foto may not be greater than 2048 kilobytes.',
                // 'ttd.image' => 'TTD must be an image.',
                // 'ttd.mimes' => 'TTD must be a file of type: jpeg, png, jpg, gif.',
                // 'ttd.max' => 'TTD may not be greater than 2048 kilobytes.'
            ];   
        }
    }
}