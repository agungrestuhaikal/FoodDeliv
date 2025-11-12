<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Delivery System | Pilih Akses</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-image: url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?fit=crop&w=1200&q=80'); /* Ganti dengan URL gambar Anda */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 1;
        }
        .container-box {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 550px;
        }
        .container-box h1 {
            font-weight: 700;
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 5px;
        }
        .container-box p {
            font-size: 1.05rem;
            color: #555;
            margin-bottom: 25px;
        }
        .buttons .btn {
            padding: 18px 45px;
            font-size: 1.15em;
            margin: 10px;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            font-weight: 600;
            border: none;
        }
        .buttons .btn-success {
            background-color: #4CAF50;
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
        }
        .buttons .btn-warning {
            background-color: #FF9800;
            box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4);
        }
        .buttons .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container-box">
        <h1 class="mb-3">Pilih Akses Anda</h1>
        <p class="border-bottom pb-3 mb-4">
            Selamat datang di layanan **Food Delivery System**.
        </p>
        
        <div class="buttons d-flex justify-content-center">
            <button id="btn-customer" 
                    class="btn btn-success btn-lg shadow" 
                    onclick="window.location.href='/order'">
                <i class="fas fa-shopping-basket me-2"></i> Akses sebagai **CUSTOMER**
            </button>
            <button id="btn-restaurant" 
                    class="btn btn-warning btn-lg shadow text-white" 
                    onclick="window.location.href='{{ route('restaurant.dashboard') }}'">
                <i class="fas fa-utensils me-2"></i> Akses sebagai **RESTAURANT**
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnRestaurant = document.getElementById('btn-restaurant');
            btnRestaurant.onclick = function(e) {
                e.preventDefault();
                const targetUrl = "{{ route('restaurant.dashboard') }}";
                window.location.href = targetUrl;
            };

            const btnCustomer = document.getElementById('btn-customer');
            btnCustomer.onclick = function(e) {
                e.preventDefault();
                window.location.href = '/order';
            };
        });
    </script>
</body>
</html>