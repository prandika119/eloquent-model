<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Latihan Eloquent</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />


<body>
    <div class="m-3">
        <h1 class="text-center m-3">Data Buku</h1>
        {{-- pesan sukses --}}
        @if (Session::has('pesan'))
            <div class="alert alert-success">{{ Session::get('pesan') }}</div>
        @endif

        @if ($cari)
            @if (count($data_buku))
                <div class="alert alert-success">Ditemukan <strong>{{ count($data_buku) }}</strong> data dengan
                    kata: <strong>{{ $cari }}</strong>
                </div>
            @else
                <div class="alert alert-warning">
                    <h4>Data {{ $cari }} tidak ditemukan</h4>
                    <a href="/buku" class="btn btn-warning">Kembali</a>
                </div>
            @endif
        @endif

        <form action="{{ route('buku.search') }}" method="get">
            @csrf
            <input type="text" name="kata" class="form-control" placeholder="Cari ... "
                style="width: 30%;
            display: inline; margin-top: 10px; margin-bottom: 10px;">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <a href="{{ route('buku.create') }}" class="btn btn-primary float-end">Tambah Buku</a>
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_buku as $buku)
                    @php
                        $no++;
                    @endphp
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ 'Rp. ' . number_format($buku->harga, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d-m-Y') }}</td>
                        <td class="d-flex">
                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin mau dihapus')" type="submit"
                                    class="btn btn-danger">Hapus</button>
                            </form>
                            <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-secondary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $data_buku->links() }}</div>
        <p class="h4 text-center">jumlah buku: <span>{{ $jumlah_buku }}</span></p>
        <p class="h4 text-center">jumlah harga buku: <span>{{ $data_buku->pluck('harga')->sum() }}</span></p>
    </div>
</body>

</html>
