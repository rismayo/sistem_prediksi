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

        $pemakaians = Pemakaian::with('obat')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('obat', function ($q) use ($search) {
                    $q->where('nama_obat', 'like', "%$search%");
                })->orWhere('periode', 'like', "%$search%");
            })
            ->when($sort_by === 'nama_obat', function ($query) use ($order) {
                $query->join('tbl_obat', 'tbl_pemakaian.id_obat', '=', 'tbl_obat.id_obat')
                    ->orderBy('tbl_obat.nama_obat', $order)
                    ->select('tbl_pemakaian.*');
            }, function ($query) use ($sort_by, $order) {
                $query->orderBy($sort_by, $order);
            })
            ->paginate(30);

        $obats = Obat::all();
        return view('datapersediaan', compact('pemakaians', 'obats', 'sort_by', 'order', 'search'));
    }

    public function store(Request $request)
    {
        Pemakaian::create([
            'periode' => $request->periode.'-01',
            'id_obat' => $request->id_obat,
            'pemakaian' => $request->pemakaian,
        ]);

        return redirect()->back()->with('success', 'Data pemakaian berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pemakaian->update([
            'periode' => $request->periode.'-01',
            'id_obat' => $request->id_obat,
            'pemakaian' => $request->pemakaian,
        ]);

        return redirect()->back()->with('success', 'Data pemakaian berhasil diupdate');
    }

    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pemakaian->delete();

        return redirect()->back()->with('success', 'Data pemakaian berhasil dihapus');
    }
}
