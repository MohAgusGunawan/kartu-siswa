<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Tentukan nama tabel (jika tabel bukan plural dari nama model)
    protected $table = 'kelas';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_kelas'
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
