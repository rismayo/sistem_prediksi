<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $table = 'tbl_pemakaian';
    protected $primaryKey = 'id_pemakaian';
    public $timestamps = false;
    
    protected $fillable = [
        'periode', 
        'id_obat', 
        'pemakaian',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }

    public static function getFilteredPaginated($search = null, $sort_by = 'periode', $order = 'asc', $perPage = 30)
    {
        return self::with('obat')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('obat', function ($q) use ($search) {
                    $q->where('nama_obat', 'like', "%$search%");
                })->orWhere('periode', 'like', "%$search%");
            })
            ->when($sort_by === 'nama_obat', function ($query) use ($order) {
                $query->join('tbl_obat', 'tbl_pemakaian.id_obat', '=', 'tbl_obat.id_obat')
                    ->orderBy('tbl_obat.nama_obat', $order)
                    ->select('tbl_pemakaian.*');
            }, function ($query) use ($sort_by, $order) {
                $query->orderBy($sort_by, $order);
            })
            ->paginate($perPage);
    }

    public static function addPemakaian(array $data)
    {
        $data['periode'] .= '-01';
        return self::create($data);
    }

    public static function updatePemakaian($id, array $data)
    {
        $pemakaian = self::findOrFail($id);
        $data['periode'] .= '-01';
        $pemakaian->update($data);
        return $pemakaian;
    }

    public static function deletePemakaian($id)
    {
        $pemakaian = self::findOrFail($id);
        return $pemakaian->delete();
    }
    // mengambil data pemakaian untuk data histori pada grafik visualisasi
    public static function getHistoriByObat(array $idObatTerpilih)
    {
        return self::with('obat')
            ->whereIn('id_obat', $idObatTerpilih)
            ->orderBy('periode')
            ->get()
            ->map(function ($item) {
                $item->periode = \Carbon\Carbon::parse($item->periode)->format('Y-m');
                return $item;
            });
    }
}
