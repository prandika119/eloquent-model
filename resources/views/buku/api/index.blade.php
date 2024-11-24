<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>

<body>
    <div class="container">
        <h4 class="mt-3 text-center">Daftar Buku</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                </tr>
            </thead>
            <tbody id="buku-table-body">
                <!-- Data buku akan dimuat di sini -->
            </tbody>
        </table>
        <p class="h4 text-center">Jumlah buku: <span id="jumlah-buku"></span></p>
        <p class="h4 text-center">Jumlah harga buku: <span id="jumlah-harga-buku"></span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/buku')
                .then(response => response.json())
                .then(res => {
                    const bukuTableBody = document.getElementById('buku-table-body');
                    const jumlahBuku = document.getElementById('jumlah-buku');
                    const jumlahHargaBuku = document.getElementById('jumlah-harga-buku');

                    let totalHarga = 0;

                    res.data.data.forEach(buku => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                            <td>${buku.judul}</td>
                            <td>${buku.penulis}</td>
                            <td>${buku.harga}</td>
                            <td>${buku.tgl_terbit}</td>
                        `;

                        bukuTableBody.appendChild(tr);
                        totalHarga += buku.harga;
                    });

                    jumlahBuku.textContent = res.data.data.length;
                    jumlahHargaBuku.textContent = totalHarga;
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
</body>

</html>
