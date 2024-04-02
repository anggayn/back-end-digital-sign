<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFile;

class FileController extends Controller
{
    public function fileUpload(Request $req)
{
    $req->validate([
        'file' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048',
        'name' => 'required|string',
        'email' => 'required|email',
        'tgl' => 'required|date',
    ]);

    try {
        if ($req->file()) {
            $fileName = time() . '_' . $req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

            $userFile = new UserFile;
            $userFile->name = $req->name;
            $userFile->email = $req->email;
            $userFile->tgl = $req->tgl;
            $userFile->file_path = '/storage/' . $filePath;
            $userFile->save();

            return response()->json([
                'message' => 'File has been uploaded.',
                'file' => $userFile
            ], 200);
        }
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function update(Request $request, $id)
    {
        try {
            $user = UserFile::findOrFail($id); 
            $user->update($request->all());

            return response()->json([
                'message' => "User successfully updated."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = UserFile::findOrFail($id); 

            $user->delete();

            return response()->json([
                'message' => "User successfully deleted."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = userfile::findOrFail($id); 

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}