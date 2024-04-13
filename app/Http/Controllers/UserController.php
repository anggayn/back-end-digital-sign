<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Str;
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
            $fotoPath = Str::random(32).".".$request->foto->getClientOriginalExtension();
      
            // Buat Pengguna
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'no_tlfn' => $request->no_tlfn,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
            ]);
      
            // Save Image in Storage folder
            Storage::disk('public')->put($fotoPath, file_get_contents($request->foto));

            // Return JSON Response
            return response()->json([
                'message' => "Pengguna berhasil dibuat."
            ],200);
        } catch (\Exception $e) {
            // Return JSON Response
            return response()->json([
                'message' => "Terjadi kesalahan saat membuat pengguna."
            ],500);
        }
    }
  
   
    public function update(UserStoreRequest $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found.'
                ], 404);
            }
    
         $user->name = $request->name;
         $user->email = $request->email;
         $user->password = $request->password;
         $user->no_tlfn = $request->no_tlfn;
         $user->alamat = $request->alamat;

         if($request->foto) {

              // Public storage
              $storage = Storage::disk('public');

               // Old foto delete
               if($storage->exists($user->foto))
               $storage->delete($user->foto);

               // fotopath
               $fotoPath = Str::random(32).".".$request->foto->getClientOriginalExtension();
               $user->foto = $fotoPath;

                // Image save in public folder
                $storage->put($fotoPath, file_get_contents($request->foto));
         }

                // Update Product
                 $user->save();
      
                // Return Json Response
                return response()->json([
                'message' => "user successfully updated."
                ],200);
              } catch (\Exception $e) {
                  // Return Json Response
                 return response()->json([
                'message' => "Something went really wrong!"
                 ],500);
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