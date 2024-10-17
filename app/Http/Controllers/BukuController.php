<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        Paginator::useBootstrapFive();
        $batas = 5;
        $data_buku = Buku::orderBy('id', 'desc')->paginate($batas);
        $jumlah_buku = Buku::count();
        $no = $batas * ($data_buku->currentPage() - 1);
        $cari = false;
        return view('buku.index', compact('data_buku', 'no', 'jumlah_buku', 'cari'));
    }
    public function search(Request $request)
    {
        Paginator::useBootstrapFive();
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like', "%" . $cari . "%")->orwhere('judul', 'like', "%" . $cari . "%")->paginate($batas);
        $jumlah_buku = Buku::count();
        $no = $batas * ($data_buku->currentPage() - 1);
        return view('buku.index', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'judul' => 'required|string',
                'penulis' => 'required|string|max:30',
                'harga' => 'required|numeric',
                'tgl_terbit' => 'required|date',
            ],
            [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute diisi dengan string',
                'numeric' => ':attribute harus diisi dengan angka',
                'date' => ':attribute harus diisi dengan tanggal',
                'max' => ':attribute minimal berisi :max karakter'
            ]
        );
        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->save();
        return redirect('/buku')->with('pesan', 'Data Buku Berhasil Disimpan');
    }
    public function destroy($id)
    {
        $buku = Buku::find($id);
        $buku->delete();
        return redirect('/buku');
    }

    public function edit($id)
    {
        $buku = Buku::find($id);
        return view('buku.edit', compact('buku'));
    }
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'judul' => 'required|string',
                'penulis' => 'required|string|max:30',
                'harga' => 'required|numeric',
                'tgl_terbit' => 'required|date',
            ],
            [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute diisi dengan string',
                'numeric' => ':attribute harus diisi dengan angka',
                'date' => ':attribute harus diisi dengan tanggal',
                'max' => ':attribute minimal berisi :max karakter'
            ]
        );
        $buku = Buku::find($id);
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->save();
        return redirect()->route('buku.index');
    }
}
