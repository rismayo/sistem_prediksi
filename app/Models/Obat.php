<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_obat';
    public $timestamps = false;
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat',
        'satuan',
    ];

    public function prediksi()
    {
        return $this->hasMany(Prediksi::class, 'id_obat', 'id_obat');
    }

    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_obat', 'id_obat');
    }

}