<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer View - Consumer Interface</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { color: #4CAF50; }
        .info { background-color: #e6ffe6; border-left: 5px solid #4CAF50; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Halaman Pelanggan (CONSUMER)</h1>
    
    <div class="info">
        <p><strong>Fungsi:</strong> Tampilan ini akan menggunakan **JavaScript** untuk:</p>
        <ul>
            <li>Mengambil daftar menu dari **Product Service** (GET http://localhost:8000/api/menus).</li>
            <li>Mengirim data pesanan ke **Order Service** (POST http://localhost:3000/api/orders).</li>
        </ul>
    </div>
    
    <h2>Daftar Menu (Ambil dari API):</h2>
    <div id="menu-list">
        <p><em>Loading menu...</em></p>
    </div>

    <h2>Formulir Pesanan</h2>
    <form action="" method="POST">
        <label for="menu_id">ID Menu yang Dipesan:</label><br>
        <input type="number" id="menu_id" name="menu_id"><br><br>
        
        <label for="quantity">Jumlah:</label><br>
        <input type="number" id="quantity" name="quantity"><br><br>
        
        <button type="submit">POST Pesanan ke Order Service (3000)</button>
    </form>
    
    <hr>
    <a href="/">Kembali ke Halaman Awal</a>
</body>

    <script>
        // Nanti, Tim Frontend Anda akan mengisi logika AJAX/Fetch API di sini
        // untuk memanggil http://localhost:8000/api/menus
        // dan POST ke http://localhost:3000/api/orders
    </script>
</body>
</html>