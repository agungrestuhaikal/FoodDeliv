import os
import requests # Untuk API Call ke Laravel
from flask import request, jsonify
from config.db import get_db_connection # Koneksi DB
from dotenv import load_dotenv

load_dotenv()
PRODUCT_SERVICE_URL = os.environ.get('PRODUCT_SERVICE_URL')

# TUGAS UTAMA ANDA: Implementasi POST /api/orders
def create_order():
    # TODO: Logika POST dan API Call ke Product Service
    
    # Ambil data dari request POST
    data = request.json
    # Asumsi: data harus memiliki 'menu_id', 'quantity', 'customer_name', dll.
    
    # Kerangka respon:
    return jsonify({"message": "Endpoint POST /api/orders siap. Implementasi logika API Call sedang berlangsung."}), 201


# TUGAS TIM LAIN: Implementasi GET
def get_orders_by_restaurant(restaurant_id):
    # TODO: Tim lain mengisi logika GET
    return jsonify({"message": "Endpoint GET siap."}), 200

# TUGAS TIM LAIN: Implementasi PUT
def update_order_status(order_id):
    # TODO: Tim lain mengisi logika PUT
    return jsonify({"message": "Endpoint PUT siap."}), 200

# TUGAS TIM LAIN: Implementasi DELETE
def delete_order(order_id):
    # TODO: Tim lain mengisi logika DELETE
    return jsonify({"message": "Endpoint DELETE siap."}), 200