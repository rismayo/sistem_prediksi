<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Tampilkan semua data obat
    public function index(Request $request)
    {
       $perPage = request('perPage', 10); // default 10
       $obats = Obat::orderBy('nama_obat')->paginate($perPage);
       return view('dataobat', compact('obats'));;
    }

    // Simpan data obat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Obat::create($request->all());

        return redirect()->back()->with('success', 'Data obat berhasil ditambahkan.');
    }

    // Update data obat
    public function update(Request $request, $id_obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $obat = Obat::where('id_obat', $id_obat)->firstOrFail();
        $obat->update($request->only(['nama_obat', 'satuan']));

        return redirect()->back()->with('success', 'Data obat berhasil diperbarui.');
    }

    // Hapus data obat
    public function destroy($id_obat)
    {
        $obat = Obat::where('id_obat', $id_obat)->firstOrFail();
        $obat->delete();

        return redirect()->back()->with('success', 'Data obat berhasil dihapus.');
    }

    // (Opsional) Edit data obat untuk AJAX/modal
    public function edit($id_obat)
    {
        $obat = Obat::findOrFail($id_obat);
        return response()->json($obat);
    }
}
