<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
          
        // Return JSON Response
        return response()->json([
            'results' => $users
        ], 200);
    }
   
    public function store(UserStoreRequest $request)
    {
        try {
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('public/image/foto');
            } else {
                throw new \Exception("Foto file not found in the request.");
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, 
                'no_tlfn' => $request->no_tlfn,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);

            return response()->json([
                'message' => "User successfully created."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
   
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found.'
                ], 404);
            }
    
            $validator = Validator::make($request->all(), [
                'name' => 'string|between:2,100',
                'email' => 'string|email|max:100|unique:users,email,'.$id,
                'password' => 'nullable|string|confirmed|min:6',
                'no_tlfn' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $validatedData = $validator->validated();
    
            if (isset($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }
    
            if (isset($validatedData['name'])) {
                $user->name = $validatedData['name'];
            }
    
            if (isset($validatedData['email'])) {
                $user->email = $validatedData['email'];
            }
    
            if (isset($validatedData['no_tlfn'])) {
                $user->no_tlfn = $validatedData['no_tlfn'];
            }
    
            if (isset($validatedData['alamat'])) {
                $user->alamat = $validatedData['alamat'];
            }
    
            if ($request->hasFile('foto')) {
                if ($user->foto) {
                    Storage::delete($user->foto);
                }
    
                $fotoPath = $request->file('foto')->store('public/image/foto');
                $user->foto = $fotoPath;
            }
    
            $user->save();
    
            return response()->json([
                'message' => "User successfully updated."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "An error occurred while updating user."
            ], 500);
        }
    }
    

    public function show($id)
    {
        // User Detail 
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }
       
        // Return JSON Response
        return response()->json([
            'user' => $user
        ], 200);
    }
   
    

    public function destroy($id)
    {
        // Detail 
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }
         
        // Delete User
        $user->delete();
       
        // Return JSON Response
        return response()->json([
            'message' => "User successfully deleted."
        ], 200);
    }
}