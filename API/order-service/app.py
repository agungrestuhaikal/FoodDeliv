# API/order-service/app.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import sqlite3
import os
import requests

app = Flask(__name__)
CORS(app)

BASE_DIR = os.path.abspath(os.path.dirname(__file__))
DB_NAME = os.path.join(BASE_DIR, "database.db")
MENU_SERVICE_URL = "http://127.0.0.1:5002"

def get_db_connection():
    conn = sqlite3.connect(DB_NAME)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                menu_id INTEGER NOT NULL,
                menu_name TEXT NOT NULL,
                quantity INTEGER DEFAULT 1,
                total_price REAL NOT NULL,
                customer_name TEXT NOT NULL,
                status TEXT DEFAULT 'done',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        """)
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS reviews (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                menu_id INTEGER NOT NULL,
                customer_name TEXT NOT NULL,
                rating INTEGER NOT NULL CHECK(rating BETWEEN 1 AND 5),
                comment TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        """)
        conn.commit()
    print(f"Database initialized: {DB_NAME}")

# =============================================
# ORDER
# =============================================

@app.route('/orders', methods=['POST'])
def create_order():
    if not request.is_json:
        return jsonify({"error": "JSON required"}), 400

    data = request.get_json()
    menu_id = data.get('menu_id')
    quantity = int(data.get('quantity', 1))
    customer_name = data.get('customer_name')

    if not all([menu_id, customer_name]):
        return jsonify({"error": "menu_id and customer_name required"}), 400

    # Ambil detail menu dari menu service
    try:
        menu_resp = requests.get(f"{MENU_SERVICE_URL}/menus/{menu_id}")
        if menu_resp.status_code != 200:
            return jsonify({"error": "Menu not found"}), 404
        menu = menu_resp.json()
    except Exception as e:
        print(f"Menu service error: {e}")
        return jsonify({"error": "Menu service unavailable"}), 500

    total_price = menu['price'] * quantity

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO orders (menu_id, menu_name, quantity, total_price, customer_name, status)
            VALUES (?, ?, ?, ?, ?, 'done')
        """, (menu_id, menu['name'], quantity, total_price, customer_name))
        conn.commit()

    return jsonify({"message": "Pesanan berhasil!"}), 201

@app.route('/orders', methods=['GET'])
def get_orders():
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM orders ORDER BY created_at DESC")
        orders = cursor.fetchall()
    return jsonify([dict(o) for o in orders]), 200

# =============================================
# REVIEW
# =============================================

@app.route('/reviews', methods=['POST'])
def create_review():
    if not request.is_json:
        return jsonify({"error": "JSON required"}), 400

    data = request.get_json()
    menu_id = data.get('menu_id')
    customer_name = data.get('customer_name')
    rating = data.get('rating')
    comment = data.get('comment', '')

    try:
        rating = int(rating)
    except:
        return jsonify({"error": "Invalid rating"}), 400

    if not all([menu_id, customer_name, rating]) or rating not in range(1, 6):
        return jsonify({"error": "Invalid data"}), 400

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO reviews (menu_id, customer_name, rating, comment)
            VALUES (?, ?, ?, ?)
        """, (menu_id, customer_name, rating, comment))
        conn.commit()

    return jsonify({"message": "Ulasan berhasil!"}), 201

@app.route('/reviews', methods=['GET'])
def get_reviews():
    menu_id = request.args.get('menu_id')
    with get_db_connection() as conn:
        cursor = conn.cursor()
        if menu_id:
            cursor.execute("SELECT * FROM reviews WHERE menu_id = ? ORDER BY created_at DESC", (menu_id,))
        else:
            cursor.execute("SELECT * FROM reviews ORDER BY created_at DESC")
        reviews = cursor.fetchall()
    return jsonify([dict(r) for r in reviews]), 200

# =============================================
# RUN
# =============================================

if __name__ == '__main__':
    if not os.path.exists(DB_NAME):
        init_db()
    print("Order Service: http://127.0.0.1:5003")
    app.run(host='127.0.0.1', port=5003, debug=True)
