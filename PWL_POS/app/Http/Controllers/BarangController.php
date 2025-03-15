<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Data Barang'
        ];

        $activeMenu = 'barang';
        $kategori = KategoriModel::all();
        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Barang Baru'
        ];

        $activeMenu = 'barang';
        $kategoris = KategoriModel::all();
        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategoris' => $kategoris]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_jual' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'kategori_id' => 'required|integer'
        ]);

        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'kategori_id' => $request->kategori_id
        ]);

        return redirect('barang')->with('success', 'Data barang berhasil disimpan');
    }

    public function show(string $id)
    {
        $barang = BarangModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Barang'
        ];

        $activeMenu = 'barang';

        return view('barang.show', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'barang' => $barang, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Barang'
        ];

        $activeMenu = 'barang';

        return view('barang.edit', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'barang' => $barang, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:10|unique:m_barang,kode_barang,'.$id.',barang_id',
            'nama_barang' => 'required|string|max:100',
            'harga_jual' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'kategori_id' => 'required|integer'
        ]);

        $barang = BarangModel::find($id);
        if (!$barang) {
            return redirect('barang')->with('error', 'Data barang tidak ditemukan');
        }

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
        try {
            BarangModel::destroy($id);
            return redirect('barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function list(Request $request)
    {
        $barangQuery = BarangModel::with('kategori')->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_jual', 'harga_beli');

        // Apply category filter if selected
        if ($request->kategori_id) {
            $barangQuery->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangQuery)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '<a href="'.url('barang/'.$row->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('barang/'.$row->barang_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/barang/'.$row->barang_id).'">';
                $btn .= csrf_field() . 
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
