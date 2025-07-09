<?php

namespace App\Http\Controllers;

use App\Models\Prediksi;

class DashboardController extends Controller
{
    public function index()
    {
        $topObats = Prediksi::getTopPredictedObats();
        return view('dashboard', compact('topObats'));
    }
}