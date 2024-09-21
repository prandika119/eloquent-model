<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>

<body>
    <div class="container">
        <h4>Edit Buku</h4>
        <form action="{{ route('buku.update', $buku->id) }}" method="post">
            @csrf
            @method('PUT')
            <div>judul <input type="text" name='judul' class="form-control" value="{{ $buku->judul }}"></div>
            <div>penulis<input type="text" name='penulis' class="form-control" value="{{ $buku->penulis }}"></div>
            <div>harga <input type="text" name='harga' class="form-control" value="{{ $buku->harga }}"></div>
            <div>tanggal terbit <input type="date" name='tgl_terbit' class="form-control"
                    value="{{ $buku->tgl_terbit }}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</body>

</html>
