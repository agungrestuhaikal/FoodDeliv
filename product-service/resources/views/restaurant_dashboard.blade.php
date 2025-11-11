<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Dashboard - Provider Interface</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { color: #FF9800; }
        .info { background-color: #fff8e1; border-left: 5px solid #FF9800; padding: 10px; margin-bottom: 20px; }
        input, button { padding: 8px; margin-top: 5px; }
    </style>
</head>
<body>
    <h1>Dashboard Restoran (PROVIDER DATA)</h1>
    
    <div class="info">
        <p><strong>Fungsi:</strong> Halaman ini mensimulasikan:</p>
        <ul>
            <li>Formulir untuk **POST /api/menus** (menambahkan menu baru) ke Product Service.</li>
            <li>Tampilan pesanan masuk yang diambil dari **Order Service** (GET http://localhost:3000/api/orders/restaurant/1).</li>
        </ul>
    </div>

    <h2>Tambah Menu Baru (Uji POST /api/menus)</h2>
    <form action="" method="POST">
        <label for="name">Nama Menu:</label><br>
        <input type="text" id="name" name="name"><br>
        
        <label for="price">Harga:</label><br>
        <input type="number" id="price" name="price"><br>
        
        <button type="submit">POST ke Product Service (8000)</button>
    </form>
    
    <hr>
    <a href="/">Kembali ke Halaman Awal</a>
</body>
</html>