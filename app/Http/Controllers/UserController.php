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
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }
   
        // Perbarui atribut pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->no_tlfn = $request->no_tlfn;
        $user->alamat = $request->alamat;

        // Periksa apakah ada file baru untuk 'foto'
        if ($request->hasFile('foto')) {
            // Jika ada file baru, simpan dan perbarui atribut 'foto'
            $fotoPath = $request->file('foto')->store('public/image/foto');
            $user->foto = $fotoPath; 
        }

        // Simpan pengguna terlepas dari apakah 'foto' telah berubah
        $user->save();

        return response()->json([
            'message' => "Pengguna berhasil diperbarui."
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => "Terjadi kesalahan!"
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