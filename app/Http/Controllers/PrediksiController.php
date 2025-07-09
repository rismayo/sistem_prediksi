<?php

namespace App\Http\Controllers;

use App\Models\Prediksi;
use App\Models\Obat;
use App\Models\Pemakaian;
use App\Jobs\PrediksiObatJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        // ambil status prediksi dari session jika ada
        $status = session('prediksi_status');
        $prediksi = collect();

        if ($status && isset($status['job_id'])) {
            // jika job id ada, ambil hasil prediksi berdasarkan job tersebut
            $prediksi = Prediksi::getByJobId($status['job_id']);
            // jika sudah ada hasilnya, ubah status menjadi done
            if ($prediksi->isNotEmpty()) {
                $status['status'] = 'done';
                session(['prediksi_status' => $status]);
            }
        }

        $idObatTerpilih = $prediksi->pluck('id_obat')->unique()->toArray();
        // ambil histori pemakaian dari obat yang di prediksi
        $histori = Pemakaian::getHistoriByObat($idObatTerpilih);

        return view('prediksi', compact('prediksi', 'obats', 'histori', 'status'));
    }

    public function storeJob(Request $request)
    {
        // validasi inputan untuk obat dan periode
        $request->validate([
            'obat' => 'required|array',
            'periode' => 'required|date_format:Y-m'
        ]);

        $periode = $request->periode;
        $id_obat = $request->obat;
        // ambil data obat berdasarkan input, method getSelectedObats pada model Obat
        $obats = Obat::getSelectedObats($id_obat);
        $jobId = (string) Str::uuid();
        // menjalankan queue job untuk prediksi di latar belakang
        dispatch(new PrediksiObatJob($obats, $periode, $jobId));
        // menyimpan status prediksi ke session
        session([
            'prediksi_status' => [
                'job_id' => $jobId,
                'status' => 'processing'
            ]
        ]);

        return redirect()->route('prediksi')->with('success', 'Prediksi sedang diproses di latar belakang.');
    }
}