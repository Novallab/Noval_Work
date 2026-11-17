<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Barang (Task 02: Build Program/Barang Model)
 * Merepresentasikan tabel 'barangs' pada database.
 */
class Barang extends Model
{
    // Nama tabel di database (opsional jika jamak standar 'barangs')
    protected $table = 'barangs';

    // Kolom-kolom yang diizinkan untuk mass-assignment
    protected $fillable = [
        'nama',
        'kategori',
        'stok',
        'satuan',
        'deskripsi',
    ];

    // Kolom timestamps aktif secara default (created_at & updated_at)
    public $timestamps = true;
}
