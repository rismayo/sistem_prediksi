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
    // mengatur paginasi pada tampilan table obat
    public static function getAllPaginated($perPage = 10)
    {
        return self::orderBy('nama_obat')->paginate($perPage);
    }

    public static function addObat(array $data)
    {
        return self::create($data);
    }

    public static function updateObat($id_obat, array $data)
    {
        $obat = self::findOrFail($id_obat);
        $obat->update($data);
        return $obat;
    }

    public static function deleteObat($id_obat)
    {
        $obat = self::findOrFail($id_obat);
        return $obat->delete();
    }
    // jika pilih 'all' maka ambil semua obat, jika tidak maka ambil obat yang dipilih saja. 
    public static function getSelectedObats(array $id_obat)
    {
        return in_array('all', $id_obat)
            ? self::all()
            : self::whereIn('id_obat', $id_obat)->get();
    }
}