<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FileStoreRequest;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $document = Document::all();
          
        return response()->json([
            'results' => $document
        ], 200);
    }

    public function store(FileStoreRequest $request)
{
    try {
        $filePath = Str::random(32) . "." . $request->file('file')->getClientOriginalExtension();
  
        // Buat Dokumen
        Document::create([
            'title' => $request->title,
            'tgl' => $request->tgl,
            'deskripsi' => $request->deskripsi,
            'file' => $filePath,
        ]);
  
        // Simpan File di Folder Storage
        Storage::disk('public')->put($filePath, file_get_contents($request->file('file')));
  
        // Return JSON Response
        return response()->json([
            'message' => "Dokumen berhasil dibuat."
        ],200);
    } catch (\Exception $e) {
        // Return JSON Response
        return response()->json([
            'message' => "Terjadi kesalahan saat membuat dokumen."
        ],500);
    }
}


public function update(FileStoreRequest $request, $id)
{
    try {
        $user = Document::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $user->title = $request->title;
        $user->tgl = $request->tgl;
        $user->deskripsi = $request->deskripsi;

        if($request->file) {

            // Public storage
            $storage = Storage::disk('public');

            // Old file delete
            if($storage->exists($user->file))
                $storage->delete($user->file);

            // File path
            $filePath =Str::random(32).".".$request->file->getClientOriginalExtension();
            $user->file = $filePath;

            // File save in public folder
            $storage->put($filePath, file_get_contents($request->file));
        }

        // Update User
        $user->save();

        // Return Json Response
        return response()->json([
            'message' => "User successfully updated."
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
        try {
            $document = Document::findOrFail($id);

            return response()->json($document, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $document = Document::findOrFail($id);

            Storage::delete('public/documents/' . $document->file_path);

            $document->delete();

            return response()->json([
                'message' => "Document successfully deleted."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}