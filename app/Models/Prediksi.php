<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
    // relasi ke tabel obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
    // mengambil semua prediksi berdasarkan job id tertentu
    public static function getByJobId($jobId)
    {
        return self::with('obat')
            ->where('job_id', $jobId)
            ->orderBy('waktu_prediksi', 'desc')
            ->get();
    }
    // menampilkan 10 obat yang sering diprediksi ke dashboard
    public static function getTopPredictedObats($limit = 10)
    {
        return self::select('id_obat', DB::raw('COUNT(*) as total'))
            ->groupBy('id_obat')
            ->orderByDesc('total')
            ->with('obat')
            ->take($limit)
            ->get();
    }
}
