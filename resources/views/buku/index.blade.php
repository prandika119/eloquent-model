@extends('auth.layouts')
@section('content')
    ;
    <div class="m-3">
        <h1 class="text-center m-3">Data Buku</h1>
        {{-- pesan sukses --}}
        @if (Session::has('pesan'))
            <div class="alert alert-success">{{ Session::get('pesan') }}</div>
        @endif

        @auth
            <a href="{{ route('buku.create') }}" class="btn btn-primary float-end">Tambah Buku</a>
        @endauth
        <table class="table table-stripped" id="table-data">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                    @auth
                        <th>Aksi</th>
                    @endauth
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
                        @auth
                            <td class="d-flex">
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin mau dihapus')" type="submit"
                                        class="btn btn-danger">Hapus</button>
                                </form>
                                <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-secondary">Edit</a>
                            </td>
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="h4 text-center">jumlah buku: <span>{{ $jumlah_buku }}</span></p>
        <p class="h4 text-center">jumlah harga buku: <span>{{ $data_buku->pluck('harga')->sum() }}</span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-data').DataTable();
        });
    </script>
@endsection
