<?php

namespace App\Http\Controllers;

use App\Models\Prediksi;
use App\Models\Obat;
use App\Models\Pemakaian;
use App\Jobs\PrediksiObatJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        $status = session('prediksi_status');
        $prediksi = collect();

        if ($status && isset($status['job_id'])) {
            $prediksi = Prediksi::with('obat')
                ->where('job_id', $status['job_id'])
                ->orderBy('waktu_prediksi', 'desc')
                ->get();
            if ($prediksi->isNotEmpty()) {
                $status['status'] = 'done';
                session(['prediksi_status' => $status]);
            }
        }

        $idObatTerpilih = $prediksi->pluck('id_obat')->unique();
        $histori = Pemakaian::with('obat')
            ->whereIn('id_obat', $idObatTerpilih)
            ->orderBy('periode')
            ->get()
            ->map(function ($item) {
                $item->periode = \Carbon\Carbon::parse($item->periode)->format('Y-m');
                return $item;
            });

        return view('prediksi', compact('prediksi', 'obats', 'histori', 'status'));
    }
    

    public function storeJob(Request $request)
    {
        $request->validate([
            'obat' => 'required|array',
            'periode' => 'required|date_format:Y-m'
        ]);

        $periode = $request->periode;
        $id_obat = $request->obat;

        $obats = in_array('all', $id_obat)
            ? Obat::all()
            : Obat::whereIn('id_obat', $id_obat)->get();

        $jobId = (string) Str::uuid();

        // Dispatch job ke queue
        dispatch(new PrediksiObatJob($obats, $periode, $jobId));

        // Simpan status prediksi aktif di session
        session([
            'prediksi_status' => [
                'job_id' => $jobId,
                'status' => 'processing'
            ]
        ]);

        return redirect()->route('prediksi')->with('success', 'Prediksi sedang diproses di latar belakang.');
    }
}
