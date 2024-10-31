<!DOCTYPE html>
<html>

<head>
    <title>Registrasi Berhasil</title>
</head>

<body>
    <h1>Halo, {{ $data['name'] }}!</h1>
    <p>Terima kasih telah mendaftar di aplikasi kami. Berikut adalah informasi akun Anda:</p>
    <ul>
        <li>Nama: {{ $data['name'] }}</li>
        <li>Email: {{ $data['email'] }}</li>
    </ul>
    <p>Silakan login ke aplikasi kami untuk mulai menggunakan layanan kami.</p>
</body>

</html>
