<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $obats = Obat::getAllPaginated($perPage);
        return view('dataobat', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Obat::addObat($request->only(['nama_obat', 'satuan']));

        return redirect()->back()->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function update(Request $request, $id_obat)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Obat::updateObat($id_obat, $request->only(['nama_obat', 'satuan']));

        return redirect()->back()->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy($id_obat)
    {
        Obat::deleteObat($id_obat);
        return redirect()->back()->with('success', 'Data obat berhasil dihapus.');
    }

    public function edit($id_obat)
    {
        $obat = Obat::findOrFail($id_obat);
        return response()->json($obat);
    }
}