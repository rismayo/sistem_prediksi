@extends('layouts.index')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-4 fw-bold">Histori Prediksi Obat</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Periode Prediksi</th>
                            <th>Hasil Prediksi</th>
                            <th>Akurasi</th>
                            <th>Tanggal Prediksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histori as $index => $item)
                            <tr>
                                <td>{{ $histori->firstItem() + $index }}</td>
                                <td>{{ $item->obat->nama_obat }}</td>
                                <td>{{ $item->periode_prediksi }}</td>
                                <td>{{ $item->hasil_prediksi }}</td>
                                <td>{{ $item->akurasi }}%</td>
                                <td>{{ \Carbon\Carbon::parse($item->waktu_prediksi)->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">Belum ada histori prediksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $histori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
