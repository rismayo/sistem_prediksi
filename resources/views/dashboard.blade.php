@extends('layouts.index')
<link href="css/styles.css" rel="stylesheet" />
@section('content')
    
<h1 class="mt-4">Prediksi Persediaan Obat UPT Puskesmas Jiwan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-chart-bar me-1"></i>
        10 Obat yang Paling Sering Diprediksi
    </div>
    <div class="card-body">
        <canvas id="obatChart" width="100%" height="30"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('obatChart');
    @if($topObats->isNotEmpty())
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topObats->pluck('obat.nama_obat')) !!},
            datasets: [{
                label: 'Jumlah Prediksi',
                data: {!! json_encode($topObats->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Prediksi'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Obat'
                    }
                }
            }
        }
    });
    @endif
</script>
@endsection