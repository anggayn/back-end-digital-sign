<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_document';
    protected $fillable = ['title', 'tgl', 'deskripsi', 'file'];
}
