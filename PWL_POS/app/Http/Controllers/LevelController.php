<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Data Level Pengguna'
        ];

        $activeMenu = 'level';

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('level')->with('success', 'Data level berhasil disimpan');
    }

    public function show(string $id)
    {
        $level = LevelModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Level'
        ];

        $activeMenu = 'level';

        return view('level.show', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $level = LevelModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Level'
        ];

        $activeMenu = 'level';

        return view('level.edit', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode,'.$id.',level_id',
            'level_nama' => 'required|string|max:100',
        ]);

        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $id)
    {
        try {
            LevelModel::destroy($id);
            return redirect('level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function list()
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama')->get();
        
        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '<a href="'.url('level/'.$row->level_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $row->level_id .
                '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $row->level_id .
                '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Create the level
        LevelModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data level berhasil disimpan',
        ]);
    }

    return redirect('/');
}
public function create_ajax()
{
    return view('level.create_ajax');
}

public function edit_ajax(string $id)
{
    $level = LevelModel::find($id);

    return view('level.edit_ajax', ['level' => $level]);
}
public function update_ajax(Request $request, $id){
    // Check if the request is from ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode,'.$id.',level_id',
            'level_nama' => 'required|string|max:100',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'msgField' => $validator->errors()
            ]);
        }
        $check = LevelModel::find($id);
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
    $level = LevelModel::find($id);
    return view('level.confirm_ajax', ['level' => $level]);
}

public function delete_ajax(Request $request, string $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $level = LevelModel::find($id);
        if ($level) {
            $level->delete();
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
    $levels = LevelModel::select('level_id', 'level_kode', 'level_nama')
        ->orderBy('level_id')
        ->get();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Level');
    $sheet->setCellValue('C1', 'Nama Level');

    $sheet->getStyle('A1:C1')->getFont()->setBold(true);
    // Populate data
    $no = 1;
    $row = 2;
    foreach ($levels as $key => $value) {
        $sheet->setCellValue('A'.$row, $no);
        $sheet->setCellValue('B'.$row, $value->level_kode);
        $sheet->setCellValue('C'.$row, $value->level_nama);
        $row++;
        $no++;
    }
    foreach(range('A','C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $sheet->setTitle('Data Level');
    $writer = new Xlsx($spreadsheet);
    $filename = 'data_level_'.date('Ymd_His').'.xlsx';
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
    return view('level.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_level' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        $file = $request->file('file_level');
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
                        'level_kode' => $value['B'],
                        'level_nama' => $value['C'],
                        'created_at' => now(),
                    ];
                }
            }
            if (count($insert) > 0) {
                LevelModel::insertOrIgnore($insert);
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
