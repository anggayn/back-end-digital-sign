<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
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

    public function store(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls,pdf|max:2048',
            'title' => 'required|string',
            'tgl' => 'string',
            'deskripsi' => 'required|string',
        ]);

        try {
            if ($req->file()) {
                $fileName = time() . '_' . $req->file->getClientOriginalName();
                $filePath = $req->file('file')->storeAs('public/documents', $fileName);

                $Document = new Document;
                $Document->title = $req->title;
                $Document->tgl = $req->tgl;
                $Document->deskripsi = $req->deskripsi;
                $Document->file = $filePath;
                $Document->save();

                return response()->json([
                    'message' => 'File has been uploaded.',
                    'file' => $Document
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
            $Document = Document::findOrFail($id);

            $Document->title = $request->input('title');
            $Document->tgl = $request->input('tgl');
            $Document->deskripsi = $request->input('deskripsi');

            if ($request->hasFile('file')) {
                Storage::delete('public/documents/' . $Document->file_path);

                // Unggah file yang baru
                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('public/documents', $fileName);

                // Perbarui file_path dengan yang baru
                $Document->file = $filePath;
            }

            // Simpan perubahan
            $Document->save();

            return response()->json([
                'message' => "Document berhasil diperbarui."
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