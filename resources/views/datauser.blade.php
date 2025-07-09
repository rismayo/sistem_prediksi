@if (!isset($noLayout))
    @extends('layouts.index')
@endif
<link href="css/styles.css" rel="stylesheet" />
@section('content')

<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Data Pengguna</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPenggunaModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Data
        </button>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->level }}</td>
                            <td>
                                <a href="#" class="text-secondary fs-5" data-bs-toggle="modal" data-bs-target="#ubahPasswordModal{{ $user->id_user }}" title="Ubah Password">
                                    <i class="fas fa-key"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="#" class="text-warning fs-5" title="Edit"
                                       data-bs-toggle="modal" data-bs-target="#editPenggunaModal{{ $user->id_user }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="#" class="text-danger fs-5" title="Hapus"
                                       data-bs-toggle="modal" data-bs-target="#deletePenggunaModal{{ $user->id_user }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editPenggunaModal{{ $user->id_user }}" tabindex="-1" aria-labelledby="editPenggunaModalLabel{{ $user->id_user }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('user.update', $user->id_user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPenggunaModalLabel{{ $user->id_user }}">Edit Data Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Nama</label>
                                                        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Level</label>
                                                        <select name="level" class="form-select" required>
                                                            <option value="superadmin" {{ $user->level === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                                            <option value="admin" {{ $user->level === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Password (biarkan kosong jika tidak diubah)</label>
                                                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password minimal 6 karakter">
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <input type="hidden" name="_modal" value="editPenggunaModal{{ $user->id_user }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer mt-2">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Delete -->
                        <div class="modal fade" id="deletePenggunaModal{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('user.destroy', $user->id_user) }}" method="POST">
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
                                            <p>Yakin ingin menghapus <strong>{{ $user->nama }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Modal Ubah Password -->
                        <div class="modal fade" id="ubahPasswordModal{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('user.update', $user->id_user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ubah Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="nama" value="{{ $user->nama }}">
                                            <input type="hidden" name="username" value="{{ $user->username }}">
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                            <input type="hidden" name="level" value="{{ $user->level }}">

                                            <div class="mb-3">
                                                <label>Password Baru</label>
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru dengan minimal 6 karakter" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <input type="hidden" name="_modal" value="ubahPasswordModal{{ $user->id_user }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah Data -->
<div class="modal fade" id="addPenggunaModal" tabindex="-1" aria-labelledby="addPenggunaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPenggunaModalLabel">Tambah Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama pengguna" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="superadmin">Superadmin</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password dengan minimal 6 karakter" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" name="_modal" value="add">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if (!isset($noLayout))
@if ($errors->any() && old('_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalId = '{{ old("_modal") }}';
            var modal = document.getElementById(modalId);
            if (modal) {
                new bootstrap.Modal(modal).show();
            }
        });
    </script>
@endif
    @endsection
@endif