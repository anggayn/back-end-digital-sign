<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_document';
    protected $fillable = ['title', 'tgl', 'deskripsi', 'file_path'];
    


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
