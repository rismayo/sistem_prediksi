@php
    $obatTerpilih = $prediksi->pluck('obat.nama_obat')->unique()->values();
    $historiPerObat = $histori->groupBy('obat.nama_obat');
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Hasil Prediksi:</h5>
    <button class="btn btn-outline-primary btn-sm" onclick="printSemuaPrediksi()">
        <i class="fas fa-print"></i> Cetak Hasil Prediksi
    </button>
</div>

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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkStatus = @json($status);
    if (checkStatus && checkStatus.status === 'processing') {
        const intervalId = setInterval(() => {
            fetch(`{{ route('prediksi.status') }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'done') {
                        clearInterval(intervalId);
                        document.getElementById('prediksiStatusWrapper').innerHTML = '';
                        document.getElementById('hasilPrediksiWrapper').innerHTML = data.html;

                        // Jalankan ulang script grafik setelah hasil dimuat
                        const script = document.createElement('script');
                        script.innerHTML = `
                            @foreach ($prediksi->pluck('obat.nama_obat')->unique()->values() as $index => $namaObat)
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
                                                        @foreach ($histori->where('obat.nama_obat', $namaObat) as $h)
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
                        `;
                        document.body.appendChild(script);
                    }
                });
        }, 3000);
    }
});
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