@if (!isset($noLayout))
    @extends('layouts.index')
@endif
<link href="css/styles.css" rel="stylesheet" />
@section('content')

<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Data Pemakaian</h3>
        <div>
            <!-- Tombol Tambah -->
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPersediaanModal">
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
                            <th>Periode</th>
                            <th>Nama Obat</th>
                            <th>Pemakaian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemakaians as $index => $pemakaian)
                        <tr>
                            <td>{{ $pemakaians->firstItem() + $index }}</td>
                            <td>{{ $pemakaian->periode }}</td>
                            <td>{{ $pemakaian->obat->nama_obat }}</td>
                            <td>{{ $pemakaian->pemakaian }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="#" class="text-warning fs-5" title="Edit"
                                       data-bs-toggle="modal" data-bs-target="#editPersediaanModal{{ $pemakaian->id_pemakaian }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                        
                                    <a href="#" class="text-danger fs-5" title="Hapus"
                                       data-bs-toggle="modal" data-bs-target="#deletePersediaanModal{{ $pemakaian->id_pemakaian }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editPersediaanModal{{ $pemakaian->id_pemakaian }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('pemakaian.update', $pemakaian->id_pemakaian) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Pemakaian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Periode</label>
                                                <input type="month" name="periode" class="form-control" value="{{ $pemakaian->periode }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Nama Obat</label>
                                                <select name="id_obat" class="form-select" required>
                                                    @foreach ($obats as $obat)
                                                        <option value="{{ $obat->id_obat }}" 
                                                            {{ $pemakaian->id_obat == $obat->id_obat ? 'selected' : '' }}>
                                                            {{ $obat->nama_obat }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Pemakaian</label>
                                                <input type="number" name="pemakaian" class="form-control" value="{{ $pemakaian->pemakaian }}" required>
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
                        
                        <!-- Modal Hapus -->
                        <div class="modal fade" id="deletePersediaanModal{{ $pemakaian->id_pemakaian }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('pemakaian.destroy', $pemakaian->id_pemakaian) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin menghapus data pemakaian untuk <strong>{{ $pemakaian->obat->nama_obat }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $pemakaians->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addPersediaanModal" tabindex="-1" aria-labelledby="addPersediaanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPersediaanModalLabel">Tambah Data Persediaan Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pemakaian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="periode" class="form-label">Periode</label>
                                <input type="month" class="form-control" id="periode" name="periode" required>
                            </div>
                            <select class="form-select" id="id_obat" name="id_obat" required>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pemakaian" class="form-label">Pemakaian</label>
                                <input type="number" class="form-control" id="pemakaian" name="pemakaian" placeholder="Masukkan pemakaian" required>
                            </div>
                        </div>
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