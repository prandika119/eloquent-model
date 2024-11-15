@extends('auth.layouts')
@section('content')
    <h1 class="text-center">Detail Buku</h1>
    <div class="card mb-3">
        <img src="{{ route('buku.photo', $buku->ori_image) }}" class="card-img-top" alt="...">
        <div class="card-body">
            <h3 class="card-title text-center">{{ $buku->judul }}</h3>
            <p class="card-text">Penulis : {{ $buku->penulis }}</p>
            <p class="card-text">Harga : Rp. {{ $buku->harga }}</p>
            <p class="card-text"><small class="text-body-secondary">{{ $buku->tgl_terbit }}</small></p>
        </div>
    </div>
@endsection
