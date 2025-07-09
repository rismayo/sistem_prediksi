<?php
namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Obat;
use Illuminate\Http\Request;

class PemakaianController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->query('sort_by', 'periode');
        $order = $request->query('order', 'asc');
        $search = $request->query('search');

        $pemakaians = Pemakaian::getFilteredPaginated($search, $sort_by, $order);
        $obats = Obat::all();

        return view('datapersediaan', compact('pemakaians', 'obats', 'sort_by', 'order', 'search'));
    }

    public function store(Request $request)
    {
        Pemakaian::addPemakaian($request->only(['periode', 'id_obat', 'pemakaian']));
        return redirect()->back()->with('success', 'Data pemakaian berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        Pemakaian::updatePemakaian($id, $request->only(['periode', 'id_obat', 'pemakaian']));
        return redirect()->back()->with('success', 'Data pemakaian berhasil diupdate');
    }

    public function destroy($id)
    {
        Pemakaian::deletePemakaian($id);
        return redirect()->back()->with('success', 'Data pemakaian berhasil dihapus');
    }
}
