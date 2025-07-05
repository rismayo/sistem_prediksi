@extends('layouts.index')
@section('content')
<div class="container mt-4">
    <h3 class="text-primary fw-bold mb-3">Prediksi Persediaan Obat<br><small class="text-muted">UPT Puskesmas Jiwan</small></h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="prediksiForm" action="{{ route('prediksi.store') }}" method="POST">
                @csrf
                <div class="row mb-3 align-items-end">
                    <div class="col-md-5">
                        <label for="obat" class="form-label">Pilih Obat</label>
                        <select id="obat" name="obat[]" class="form-select" multiple size="5" style="overflow-y: auto;">
                            <option value="all">Semua Obat</option>
                            @foreach ($obats as $obat)
                                <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="periode" class="form-label">Pilih Tanggal</label>
                        <input type="month" class="form-control" id="periode" name="periode" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-chart-line me-1"></i> Prediksi
                        </button>
                    </div>
                </div>
            </form>

            @if ($status && $status['status'] === 'processing')
                <div class="alert alert-warning d-flex align-items-center mt-3" role="alert" id="prediksiStatus">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    <div>
                        <strong>Prediksi sedang diproses...</strong><br>
                        Tunggu sebentar atau kembali beberapa saat, kemudian refresh halaman
                    </div>
                </div>
            @endif

            <div class="mt-4 print-area">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Hasil Prediksi:</h5>
                    @if ($prediksi->count() > 0)
                    <button class="btn btn-outline-primary btn-sm" onclick="printSemuaPrediksi()">
                        <i class="fas fa-print"></i> Cetak Hasil Prediksi
                    </button>
                    @endif
                </div>

                @if ($prediksi->count() > 0)

                @php
                    $obatTerpilih = $prediksi->pluck('obat.nama_obat')->unique()->values();
                    $historiPerObat = $histori->groupBy('obat.nama_obat');
                @endphp

                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Obat</th>
                            <th>Periode</th>
                            <th>Hasil Prediksi</th>
                            <th>Akurasi</th>
                            <th>Waktu Prediksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prediksi as $item)
                            @php
                                $akurasi = floatval($item->akurasi);
                                if ($akurasi >= 85) {
                                    $warnaBg = '#d4edda';
                                    $warnaText = '#155724';
                                } elseif ($akurasi >= 70) {
                                    $warnaBg = '#fff3cd';
                                    $warnaText = '#856404';
                                } else {
                                    $warnaBg = '#f8d7da';
                                    $warnaText = '#721c24';
                                }
                            @endphp
                            <tr>
                                <td>{{ $item->obat->nama_obat }}</td>
                                <td>{{ $item->periode_prediksi }}</td>
                                <td>{{ $item->hasil_prediksi }}</td>
                                <td>
                                    <span style="background-color: {{ $warnaBg }}; color: {{ $warnaText }}; padding: 2px 6px; border-radius: 4px;">
                                        {{ $item->akurasi }}%
                                    </span>
                                </td>
                                <td>{{ $item->waktu_prediksi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Grafik per Obat -->
                <div class="mt-5">
                    <div id="printSection" class="d-none"></div>
                    <h6 class="fw-bold mb-3">Visualisasi Grafik:</h6>
                    <div class="accordion" id="grafikAccordion">
                        @foreach ($obatTerpilih as $index => $namaObat)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                    Grafik: {{ $namaObat }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#grafikAccordion">
                                <div class="accordion-body">
                                    <canvas id="chartObat{{ $index }}" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Script untuk Chart per Obat -->
                <script data-chart-data>
                    @foreach ($obatTerpilih as $index => $namaObat)
                        const ctx{{ $index }} = document.getElementById('chartObat{{ $index }}');
                        if (ctx{{ $index }}) {
                            new Chart(ctx{{ $index }}, {
                                type: 'line',
                                data: {
                                    datasets: [
                                        {
                                            label: 'Prediksi - {{ $namaObat }}',
                                            data: [
                                                @foreach ($prediksi->where('obat.nama_obat', $namaObat) as $p)
                                                    { x: '{{ $p->periode_prediksi }}', y: {{ $p->hasil_prediksi }} },
                                                @endforeach
                                            ],
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                            tension: 0.4,
                                            fill: false
                                        },
                                        {
                                            label: 'Histori - {{ $namaObat }}',
                                            data: [
                                                @foreach ($historiPerObat[$namaObat] ?? [] as $h)
                                                    { x: '{{ $h->periode }}', y: {{ $h->pemakaian }} },
                                                @endforeach
                                            ],
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                            tension: 0.4,
                                            fill: false
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            type: 'time',
                                            time: {
                                                unit: 'month',
                                                tooltipFormat: 'yyyy-MM',
                                                displayFormats: { month: 'yyyy-MM' }
                                            },
                                            title: { display: true, text: 'Periode' }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            title: { display: true, text: 'Jumlah Pemakaian' }
                                        }
                                    },
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Grafik Prediksi dan Histori - {{ $namaObat }}'
                                        }
                                    }
                                }
                            });
                        }
                    @endforeach
                </script>

                @else
                    <div class="alert alert-info">Belum ada hasil prediksi ditampilkan.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function printSemuaPrediksi() {
    const container = document.getElementById('printSection');
    container.innerHTML = '';

    const rows = Array.from(document.querySelectorAll('tbody tr'));
    if (rows.length === 0) return alert('Tidak ada data untuk dicetak.');

    const periode = rows[0].children[1]?.innerText || '-';
    const waktu = rows[0].children[4]?.innerText || '-';

    const tableRows = rows.map(row => `
        <tr>
            <td>${row.children[0].innerText}</td>
            <td>${row.children[1].innerText}</td>
            <td>${row.children[2].innerText}</td>
            <td>${row.children[3].innerText}</td>
        </tr>
    `).join('');

    const tableHtml = `
        <table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>Nama Obat</th>
                    <th>Periode</th>
                    <th>Hasil Prediksi</th>
                    <th>Akurasi</th>
                </tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
    `;

    container.innerHTML = `
        <div style="padding: 20px; font-family: sans-serif;">
            <h3 style="text-align: center; margin-bottom: 10px;">Laporan Prediksi Persediaan Obat</h3>
            <p><strong>Periode Prediksi</strong>: ${periode}</p>
            <p><strong>Waktu Prediksi</strong>: ${waktu}</p>
            ${tableHtml}
        </div>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Cetak Semua Hasil Prediksi</title>
                <style>
                    body { font-family: sans-serif; padding: 30px; }
                    table, th, td { border: 1px solid #000; border-collapse: collapse; }
                    th, td { padding: 8px; text-align: center; }
                </style>
            </head>
            <body>
                ${container.innerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    };
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endsection