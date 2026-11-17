<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Program (Contoh Slide 4 & 11)
 * Merepresentasikan tabel 'programs' pada database.
 */
class Program extends Model
{
    protected $table = 'programs';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public $timestamps = true;
}
