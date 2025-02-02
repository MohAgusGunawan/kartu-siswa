<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Tentukan nama tabel (jika tabel bukan plural dari nama model)
    protected $table = 'siswa';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'id_card',
        'nis',
        'nama',
        'ttl',
        'gender',
        'alamat',
        'wa',
        'kelas_id', 
        'id_tahun_akademik',
        'email',
        'foto',
        'status_cetak'
    ];

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik');
    }

    // Jika tidak ingin menggunakan kolom created_at dan updated_at
    // public $timestamps = false;
}
