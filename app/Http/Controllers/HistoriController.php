<?php

namespace App\Http\Controllers;

use App\Models\Histori;

class HistoriController extends Controller
{
    public function index()
    {
        $histori = Histori::getHistoriPaginated();
        return view('histori', compact('histori'));
    }
}
