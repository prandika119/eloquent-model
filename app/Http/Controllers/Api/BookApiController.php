<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class BookApiController extends Controller
{
    public function index()
    {
        $books = Buku::latest()->paginate(5);
        return new BookResource(true, 'List Data Buku', $books);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'photo' => 'image|nullable|max:1999'
        ]);

        if ($validator->fails()) {
            return new BookResource(false, 'Data Buku Tidak Tersimpan', $validator->errors());
        }

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
        return new BookResource(true, 'Data Buku Berhasil Ditambahkan', $buku);
    }
    public function show(Buku $buku)
    {
        return new BookResource(true, 'Detail Data Buku', $buku);
    }
    public function update(Request $request, Buku $buku)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'photo' => 'image|nullable|max:1999'
        ]);

        if ($validator->fails()) {
            return new BookResource(false, 'Data Buku Tidak Tersimpan', $validator->errors());
        }
        $filenameSimpan = null;
        if ($request->hasFile('photo')) {
            if ($buku->ori_image) {
                Storage::delete('photos/' . $buku->ori_image);
                Storage::delete('photos/' . $buku->square_image);
            }

            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
            $this->resizePhoto($filenameSimpan);
            // $square_image = 'square_' . $filenameSimpan ?? null;
        }
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit,
            'ori_image' => $filenameSimpan !== null ? $filenameSimpan : $buku->ori_image,
            'square_image' => $filenameSimpan !== null ? 'square_' . $filenameSimpan : $buku->square_image
        ]);
        return new BookResource(true, 'Data Buku Berhasil Diubah', $buku);
    }

    public function destroy(Buku $buku)
    {
        if ($buku->ori_image) {
            Storage::delete('photos/' . $buku->ori_image);
            Storage::delete('photos/' . $buku->square_image);
        }
        $buku->delete();
        return new BookResource(true, 'Data Buku Berhasil Dihapus', null);
    }

    public function resizePhoto($filename)
    {
        $image_ori = Storage::get('photos/' . $filename);
        $image_square = Image::read($image_ori);
        $image_square->resize(100, 100);
        $image_square->save(Storage::path('photos/' . 'square_' . $filename));
    }
}
