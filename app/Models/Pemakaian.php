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
}
