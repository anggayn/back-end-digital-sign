<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFile;

class FileController extends Controller
{
    public function fileUpload(Request $req)
{
    $req->validate([
        'file' => 'required|mimes:csv,txt,xlsx,xls,pdf|max:2048',
        'title' => 'required|string',
        'tgl' => 'date',
        'deskripsi' => 'required|string',
    ]);

    try {
        if ($req->file()) {
            $fileName = time() . '_' . $req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

            $userFile = new UserFile;
            $userFile->title = $req->title;
            $userFile->tgl = $req->tgl;
            $userFile->deskripsi = $req->deskripsi;
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
        // Cari record berdasarkan id dokumen
        $userFile = UserFile::findOrFail($id);

        // Memperbarui atribut-atribut yang diterima dari permintaan
        $userFile->title = $request->input('title');
        $userFile->tgl = $request->input('tgl');
        $userFile->deskripsi = $request->input('deskripsi');

        // Periksa apakah ada file yang diunggah dalam permintaan
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            Storage::disk('public')->delete($userFile->file_path);

            // Unggah file yang baru
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            // Perbarui file_path dengan yang baru
            $userFile->file_path = '/storage/' . $filePath;
        }

        // Simpan perubahan
        $userFile->save();

        return response()->json([
            'message' => "User berhasil diperbarui."
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