<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Data Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu'));
    }


    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_kontak' => 'nullable|string|max:50',
            'supplier_alamat' => 'nullable|string|max:255',
        ]);
        SupplierModel::create($request->all());

        return redirect('supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu', 'supplier'));
    }

    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'supplier' => $supplier, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_nama' => 'required|string|max:100',
            'supplier_kontak' => 'nullable|string|max:50',
            'supplier_alamat' => 'nullable|string|max:255',
        ]);

        $supplier = SupplierModel::find($id);
        if (!$supplier) {
            return redirect('supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        $supplier->update($request->all());

        return redirect('supplier')->with('success', 'Data supplier berhasil diubah');
    }

    public function destroy(string $id)
    {
        try {
            SupplierModel::destroy($id);
            return redirect('supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function list()
    {
        
        $suppliers = SupplierModel::select('supplier_id', 'supplier_nama', 'supplier_kontak', 'supplier_alamat');

        return DataTables::of($suppliers)
        ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '<a href="'.url('supplier/'.$row->supplier_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('supplier/'.$row->supplier_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/supplier/'.$row->supplier_id).'">';
                $btn .= csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
