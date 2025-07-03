<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    use HasFactory;

    protected $table = 'tbl_prediksi';
    public $timestamps = false;
    protected $primaryKey = 'id_prediksi';

    protected $fillable = [
        'id_obat',
        'periode_prediksi',
        'hasil_prediksi',
        'akurasi',
        'waktu_prediksi',
        'job_id',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}
