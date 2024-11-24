<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Laravel\Facades\Image;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index',
            'search'
        ]);
    }

    public function index()
    {

        $data_buku = Buku::all();
        $jumlah_buku = Buku::count();
        $no = 0;

        return view('buku.index', compact('data_buku', 'no', 'jumlah_buku'));
    }

    public function apiIndex()
    {
        return view('buku.api.index');
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

    public function show(Buku $buku)
    {
        $buku = Buku::find($buku->id);
        return view('buku.show', compact('buku'));
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
                'photo' => 'image|nullable|max:1999'
            ],
            [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute diisi dengan string',
                'numeric' => ':attribute harus diisi dengan angka',
                'date' => ':attribute harus diisi dengan tanggal',
                'max' => ':attribute minimal berisi :max karakter atau KB'
            ]
        );
        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
            $this->resizePhoto($filenameSimpan);
        }

        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->ori_image = $filenameSimpan ?? null;
        $buku->square_image = 'square_' . $filenameSimpan ?? null;
        $buku->save();
        return redirect('/buku')->with('pesan', 'Data Buku Berhasil Disimpan');
    }
    public function destroy($id)
    {
        $buku = Buku::find($id);
        // Hapus foto lama jika ada
        if ($buku->ori_image) {
            Storage::delete('photos/' . $buku->ori_image);
            Storage::delete('photos/square_' . $buku->square_image);
        }
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

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($buku->photo) {
                Storage::delete('photos/' . $buku->photo);
            }

            // Unggah foto baru
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);

            // Resize foto
            $this->resizePhoto($filenameSimpan);

            // Simpan nama file foto ke database
            $buku->ori_image = $filenameSimpan ?? null;
            $buku->square_image = 'square_' . $filenameSimpan ?? null;
        }

        $buku->save();
        return redirect()->route('buku.index');
    }

    public function resizePhoto($filename)
    {
        $image_ori = Storage::get('photos/' . $filename);
        $image_square = Image::read($image_ori);
        $image_square->resize(100, 100);
        $image_square->save(Storage::path('photos/' . 'square_' . $filename));
    }

    public function getPhoto($filename)
    {
        $image_square = Storage::get('photos/' . $filename);
        return $image_square;
    }
}
