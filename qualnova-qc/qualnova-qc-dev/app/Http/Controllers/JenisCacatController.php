<?php

namespace App\Http\Controllers;

use App\Models\JenisCacat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisCacatController extends Controller
{
    // ✅ List semua jenis cacat
    public function index()
    {
        $this->authorizeAccess();
        $jenisCacat = JenisCacat::all();
        return view('jenis_cacat.index', compact('jenisCacat'));
    }

    // ✅ Form tambah data
    public function create()
    {
        $this->authorizeAccess();
        return view('jenis_cacat.create');
    }

    // ✅ Simpan data baru
    public function store(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'nama_jenis' => 'required|string|max:255',
        ]);

        JenisCacat::create([
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis_cacat.index')
                         ->with('success', 'Jenis cacat berhasil ditambahkan!');
    }

    // ✅ Form edit
    public function edit($id)
    {
        $this->authorizeAccess();

        $jenisCacat = JenisCacat::findOrFail($id);
        return view('jenis_cacat.edit', compact('jenisCacat'));
    }

    // ✅ Update data
    public function update(Request $request, $id)
    {
        $this->authorizeAccess();

        $request->validate([
            'nama_jenis' => 'required|string|max:255',
        ]);

        $jenisCacat = JenisCacat::findOrFail($id);
        $jenisCacat->update([
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis_cacat.index')
                         ->with('success', 'Jenis cacat berhasil diperbarui!');
    }

    // ✅ Hapus data
    public function destroy($id)
    {
        $this->authorizeAccess();

        JenisCacat::findOrFail($id)->delete();

        return redirect()->route('jenis_cacat.index')
                         ->with('success', 'Jenis cacat berhasil dihapus!');
    }

    // ✅ Pembatasan akses
    private function authorizeAccess()
    {
        $allowedRoles = ['super_admin', 'admin', 'qc'];
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Akses ditolak');
        }
    }
}
