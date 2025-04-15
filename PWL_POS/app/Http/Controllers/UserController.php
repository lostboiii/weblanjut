<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Hash;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash as Bcrypt;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'User'],
        ];
        $page = (object) [
            'title' => 'Daftar User yang terdaftar pada sistem',
    
        ];
        $activeMenu = 'user';
        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb,'level' => $level, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function tambah(){
        return view('user_tambah');
    }
    public function tambah_simpan(Request $request){
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Bcrypt::make($request->password),
            'level_id' => $request->level_id,
        ]);
        return redirect('/user');
    }
    public function ubah($id){
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }
    public function ubah_simpan(Request $request,$id){
        $user = UserModel::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Bcrypt::make($request->password);
        $user->level_id = $request->level_id;
        $user->save();
        return redirect('/user');
    }
    public function hapus($id){
        $user = UserModel::find($id);
        $user->delete();
        return redirect('/user');
    }

    public function list(Request $request)
    {
        $users = UserModel::with('level')->select('user_id', 'username', 'nama', 'level_id')->get();
        
        // Apply level filter if selected
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
        
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '<a href="'.url('user/'.$row->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $row->user_id .
                '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $row->user_id .
                '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            })
            ->rawColumns(['aksi'])
            ->escapeColumns([])
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5',          // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => Bcrypt::make($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();
        
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5',          // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
     
        
        $check = UserModel::find($id);
        if (!$check) {
          
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }
        
        try {
          
            UserModel::destroy($id);    // Hapus data user
          
            
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
          
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Create the user
        UserModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil disimpan',
        ]);
    }

    return redirect('/');
}
public function create_ajax()
{
    $level = LevelModel::all(); 
    return view('user.create_ajax', compact('level'));
}

public function edit_ajax(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::select('level_id', 'level_nama')->get();

    return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
}
public function update_ajax(Request $request, $id){
    // Check if the request is from ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'msgField' => $validator->errors()
            ]);
        }
        $check = UserModel::find($id);
        if ($check) {
            if(!$request->filled('password') ){ 
                $request->request->remove('password');
            }
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
    $user = UserModel::find($id);
    return view('user.confirm_ajax', ['user' => $user]);
}

public function delete_ajax(Request $request, string $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $user = UserModel::find($id);
        if ($user) {
            $user->delete();
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
    $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->orderBy('user_id')
        ->with('level')
        ->get();
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Username');
    $sheet->setCellValue('C1', 'Nama');
    $sheet->setCellValue('D1', 'Level');

    $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    // Populate data
    $no = 1;
    $row = 2;
    foreach ($users as $key => $value) {
        $sheet->setCellValue('A'.$row, $no);
        $sheet->setCellValue('B'.$row, $value->username);
        $sheet->setCellValue('C'.$row, $value->nama);
        $sheet->setCellValue('D'.$row, $value->level->level_nama);
        $row++;
        $no++;
    }
    foreach(range('A','D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $sheet->setTitle('Data User');
    $writer = new Xlsx($spreadsheet);
    $filename = 'data_user_'.date('Ymd_His').'.xlsx';
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
    return view('user.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        $file = $request->file('file_user');
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
                        'username' => $value['B'],
                        'nama' => $value['C'],
                        'level_id' => $value['D'],
                        'password' => bcrypt('password123'), // Default password
                        'created_at' => now(),
                    ];
                }
            }
            if (count($insert) > 0) {
                UserModel::insertOrIgnore($insert);
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
