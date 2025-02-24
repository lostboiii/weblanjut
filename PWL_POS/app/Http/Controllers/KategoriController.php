<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(){
        // $data = [
        //     'kategori_kode' => 'SNK',
        //     'kategori_nama' => 'Snack/Makanan Ringan',
        //     'created_at' => now(),
        // ];
        // DB::table('m_kategori')->insert($data);
        // return 'Insert data baru berhasil ditambahkan';

        // $row = DB::table('m_kategori')
        // ->where('kategori_kode','SNK')->update(['kategori_nama' => 'Cemilan']);
        // return "Update data baru berhasil. Jumlah data yang diupdate:" .$row. "baris";

        // $row = DB::table('m_kategori')
        // ->where('kategori_kode','SNK')->delete();
        // return "Delete data berhasil. Jumlah data yang dihapus:".$row. "baris";

        $data = DB::select('select * from m_kategori');
        return view('kategori', ['data' => $data]);
    }
}
