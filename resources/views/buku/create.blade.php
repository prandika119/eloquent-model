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
    {{-- pesan error --}}
    @if (count($errors) > 0)
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <div class="container">
        <h4>Tambah Buku</h4>
        <form action="{{ route('buku.store') }}" method="post">
            @csrf
            <div>judul <input type="text" name='judul' class="form-control" /></div>
            <div>penulis<input type="text" name='penulis' class="form-control" /></div>
            <div>harga <input type="text" name='harga' class="form-control" /></div>
            <div>tanggal terbit <input type="date" name='tgl_terbit' class="form-control" /></div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ '/buku' }}">Kembali</a>
        </form>
    </div>
</body>

</html>
