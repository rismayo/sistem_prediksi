@if (!isset($noLayout))
    @extends('layouts.index')
@endif
<link href="css/styles.css" rel="stylesheet" />
@section('content')

<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Data Obat</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addObatModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($obats as $index => $obat)
                        <tr>
                            <td>{{ $obats->firstItem() + $index }}</td>
                            <td>{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->satuan }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-3">
                                    
                                    <a href="#" class="text-warning fs-5" title="Edit"
                                       data-bs-toggle="modal" data-bs-target="#editObatModal{{ $obat->id_obat }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                            
                                    <a href="#" class="text-danger fs-5" title="Hapus"
                                       data-bs-toggle="modal" data-bs-target="#deleteModal{{ $obat->id_obat }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>                            
                        </tr>

                        <!-- Modal Edit Obat -->
                        <div class="modal fade" id="editObatModal{{ $obat->id_obat }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('obat.update', $obat->id_obat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Obat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama Obat</label>
                                                <input type="text" name="nama_obat" class="form-control" value="{{ $obat->nama_obat }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Satuan</label>
                                                <input type="text" name="satuan" class="form-control" value="{{ $obat->satuan }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Modal Konfirmasi Hapus -->
                        <div class="modal fade" id="deleteModal{{ $obat->id_obat }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $obat->id_obat }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('obat.destroy', $obat->id_obat) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $obat->id_obat }}">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin menghapus obat <strong>{{ $obat->nama_obat }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
                @if ($obats instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-3">
                        {{ $obats->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah Data -->
<div class="modal fade" id="addObatModal" tabindex="-1" aria-labelledby="addObatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addObatModalLabel">Tambah Data Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk menambah data -->
                <form action="{{ route('obat.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3">
                            <label for="nama_obat" class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" id="nama_obat" name="nama_obat" placeholder="Masukkan nama obat" required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Masukkan satuan obat" required>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if (!isset($noLayout))
    @endsection
@endif