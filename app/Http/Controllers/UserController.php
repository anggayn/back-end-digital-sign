<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;

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
            if ($request->hasFile('ttd')) {
                $ttdPath = $request->file('ttd')->store('public/image/ttd');
            } else {
                throw new \Exception("Ttd file not found in the request.");
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, 
                'no_tlfn' => $request->no_tlfn,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
                'ttd' => $ttdPath,
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
   
    public function update(UserStoreRequest $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User Not Found.'
                ], 404);
            }
       
            // Update atribut user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->no_tlfn = $request->no_tlfn;
            $user->alamat = $request->alamat;

            // Simpan foto jika ada perubahan
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('public/image/foto');
                $user->foto = $fotoPath; // Simpan path foto
            }

            // Simpan ttd jika ada perubahan
            if ($request->hasFile('ttd')) {
                $ttdPath = $request->file('ttd')->store('public/image/ttd');
                $user->ttd = $ttdPath; // Simpan path ttd
            }

            // Update User
            $user->save();

            return response()->json([
                'message' => "User successfully updated."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
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