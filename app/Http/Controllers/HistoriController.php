<?php

namespace App\Http\Controllers;

class HistoriController extends Controller
{
    public function index()
    {
        $histori = \App\Models\Prediksi::with('obat')
                    ->orderBy('waktu_prediksi', 'desc')
                    ->paginate(10); // paginate 10 per halaman
        return view('histori', compact('histori'));
    }
}