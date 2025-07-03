<?php

namespace App\Jobs;

use App\Models\Prediksi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PrediksiObatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $obats;
    protected $periode;
    protected $jobId;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Support\Collection $obats
     * @param string $periode
     * @param string $jobId
     */
    public function __construct($obats, $periode, $jobId)
    {
        $this->obats = $obats;
        $this->periode = $periode;
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->obats as $obat) {
            $response = Http::post('http://localhost:5000/predict', [
                'id_obat' => $obat->nama_obat,
                'periode_prediksi' => $this->periode
            ]);

            if ($response->successful()) {
                $hasil_prediksi = $response->json();

                foreach ($hasil_prediksi as $hasil) {
                    Prediksi::create([
                        'id_obat' => $obat->id_obat,
                        'periode_prediksi' => $hasil['periode'],
                        'hasil_prediksi' => $hasil['hasil'],
                        'akurasi' => is_numeric($hasil['akurasi']) ? $hasil['akurasi'] : null,
                        'waktu_prediksi' => $hasil['waktu'],
                        'job_id' => $this->jobId 
                    ]);
                }
            }
        }
    }
}
