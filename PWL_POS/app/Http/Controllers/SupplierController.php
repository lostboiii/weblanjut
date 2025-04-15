<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $row->supplier_id .
                '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $row->supplier_id .
                '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create_ajax()
{
    return view('supplier.create_ajax');
}
public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_nama' => 'required|string|max:100',
            'supplier_kontak' => 'nullable|string|max:50',
            'supplier_alamat' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Create the supplier
        SupplierModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data supplier berhasil disimpan',
        ]);
    }

    return redirect('/');
}
public function edit_ajax(string $id)
{
    $supplier = SupplierModel::find($id);

    return view('supplier.edit_ajax', ['supplier' => $supplier]);
}
public function update_ajax(Request $request, $id){
    // Check if the request is from ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_nama' => 'required|string|max:100',
            'supplier_kontak' => 'nullable|string|max:50',
            'supplier_alamat' => 'nullable|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'msgField' => $validator->errors()
            ]);
        }
        $check = SupplierModel::find($id);
        if ($check) {
            $check->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data successfully updated'
            ]);
        } else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ]);
        }
    }
    return redirect('/');
}

public function confirm_ajax(string $id)
{
    $supplier = SupplierModel::find($id);
    return view('supplier.confirm_ajax', ['supplier' => $supplier]);
}

public function delete_ajax(Request $request, string $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $supplier = SupplierModel::find($id);
        if ($supplier) {
            $supplier->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}

public function export_excel()
{
    $suppliers = SupplierModel::select('supplier_id', 'supplier_nama', 'supplier_kontak', 'supplier_alamat')
        ->orderBy('supplier_id')
        ->get();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama Supplier');
    $sheet->setCellValue('C1', 'Kontak');
    $sheet->setCellValue('D1', 'Alamat');

    $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    // Populate data
    $no = 1;
    $row = 2;
    foreach ($suppliers as $key => $value) {
        $sheet->setCellValue('A'.$row, $no);
        $sheet->setCellValue('B'.$row, $value->supplier_nama);
        $sheet->setCellValue('C'.$row, $value->supplier_kontak);
        $sheet->setCellValue('D'.$row, $value->supplier_alamat);
        $row++;
        $no++;
    }
    foreach(range('A','D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $sheet->setTitle('Data Supplier');
    $writer = new Xlsx($spreadsheet);
    $filename = 'data_supplier_'.date('Ymd_His').'.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function import()
{
    return view('supplier.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        $file = $request->file('file_supplier');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);
        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'supplier_nama' => $value['B'],
                        'supplier_kontak' => $value['C'],
                        'supplier_alamat' => $value['D'],
                        'created_at' => now(),
                    ];
                }
            }
            if (count($insert) > 0) {
                SupplierModel::insertOrIgnore($insert);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }
    return redirect('/');
}
}
