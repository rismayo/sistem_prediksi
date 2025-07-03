<?php

namespace App\Http\Controllers;

use App\Models\Prediksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $topObats = Prediksi::select('id_obat', DB::raw('COUNT(*) as total'))
            ->groupBy('id_obat')
            ->orderByDesc('total')
            ->with('obat') // pastikan relasi 'obat' ada di model Prediksi
            ->take(10)
            ->get();

        return view('dashboard', compact('topObats'));
    }
}
