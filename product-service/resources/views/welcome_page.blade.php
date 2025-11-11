<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Delivery System | Welcome</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f4f4f9; }
        .container { text-align: center; padding: 30px; border-radius: 10px; background-color: white; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; margin-bottom: 30px; }
        .buttons button {
            padding: 15px 30px;
            margin: 10px;
            font-size: 1.1em;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s;
        }
        #btn-customer { background-color: #4CAF50; }
        #btn-customer:hover { background-color: #45a049; }
        #btn-restaurant { background-color: #FF9800; }
        #btn-restaurant:hover { background-color: #e68a00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pilih Akses Anda</h1>
        <p>Selamat datang di layanan Food Delivery System (API Project).</p>
        
        <div class="buttons">
            <button id="btn-customer" onclick="window.location.href='/customer'">
                <i class="fas fa-user"></i> Akses sebagai **CUSTOMER**
            </button>
            <button id="btn-restaurant" onclick="window.location.href='/menus'">
                <i class="fas fa-utensils"></i> Akses sebagai **RESTAURANT**
            </button>
        </div>
    </div>
</body>
</html>