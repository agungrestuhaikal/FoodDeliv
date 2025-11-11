# menu_service/app.py
import sqlite3
import os
import contextlib
from flask import Flask, request, jsonify

app = Flask(__name__)

DB_NAME = "menu_data.db"
DB_PATH = os.path.join(os.path.dirname(__file__), DB_NAME)

@contextlib.contextmanager
def get_db_connection():
    conn = sqlite3.connect(DB_PATH)
    try:
        yield conn
    finally:
        conn.close()

def init_db():
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS menus (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT,
                price REAL NOT NULL,
                category TEXT NOT NULL,
                image_url TEXT
            )
        """)
        conn.commit()
    print(f"Menu Service: Database '{DB_NAME}' initialized")

# CREATE
@app.route('/menus', methods=['POST'])
def create_menu():
    if not request.is_json:
        return jsonify({"error": "Request must be JSON"}), 400
    
    data = request.get_json()
    name = data.get('name')
    description = data.get('description')
    price = data.get('price')
    category = data.get('category')
    image_url = data.get('image_url')

    if not name or price is None or not category:
        return jsonify({"error": "Name, price, and category are required"}), 400

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO menus (name, description, price, category, image_url)
            VALUES (?, ?, ?, ?, ?)
        """, (name, description, price, category, image_url))
        conn.commit()
        menu_id = cursor.lastrowid

    return jsonify({"id": menu_id, **data}), 201

# READ ALL
@app.route('/menus', methods=['GET'])
def get_all_menus():
    with get_db_connection() as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM menus")
        menus = cursor.fetchall()

    return jsonify([dict(m) for m in menus]), 200

# READ BY ID
@app.route('/menus/<int:menu_id>', methods=['GET'])
def get_menu(menu_id):
    with get_db_connection() as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM menus WHERE id = ?", (menu_id,))
        menu = cursor.fetchone()

    if not menu:
        return jsonify({"error": "Menu not found"}), 404
    
    return jsonify(dict(menu)), 200

# UPDATE
@app.route('/menus/<int:menu_id>', methods=['PUT'])
def update_menu(menu_id):
    if not request.is_json:
        return jsonify({"error": "Request must be JSON"}), 400
    
    data = request.get_json()

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT id FROM menus WHERE id = ?", (menu_id,))
        exists = cursor.fetchone()

        if not exists:
            return jsonify({"error": "Menu not found"}), 404
        
        cursor.execute("""
            UPDATE menus
            SET name = ?, description = ?, price = ?, category = ?, image_url = ?
            WHERE id = ?
        """, (data["name"], data["description"], data["price"], data["category"], data.get("image_url"), menu_id))
        conn.commit()

    return jsonify({"message": "Menu updated successfully"}), 200

# DELETE
@app.route('/menus/<int:menu_id>', methods=['DELETE'])
def delete_menu(menu_id):
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT id FROM menus WHERE id = ?", (menu_id,))
        exists = cursor.fetchone()

        if not exists:
            return jsonify({"error": "Menu not found"}), 404
        
        cursor.execute("DELETE FROM menus WHERE id = ?", (menu_id,))
        conn.commit()

    return jsonify({"message": "Menu deleted successfully"}), 200

@app.route('/orders', methods=['POST'])
def create_order():
    if not request.is_json:
        return jsonify({"error": "Request must be JSON"}), 400
    
    data = request.get_json()
    menu_id = data.get('menu_id')
    quantity = data.get('quantity', 1)
    customer_name = data.get('customer_name')
    table_number = data.get('table_number')

    if not menu_id or not customer_name:
        return jsonify({"error": "menu_id and customer_name required"}), 400

    # Cek menu ada
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT id, name, price FROM menus WHERE id = ?", (menu_id,))
        menu = cursor.fetchone()
        if not menu:
            return jsonify({"error": "Menu not found"}), 404

        total_price = menu[2] * quantity

        cursor.execute("""
            INSERT INTO orders (menu_id, menu_name, quantity, total_price, customer_name, table_number, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        """, (menu_id, menu[1], quantity, total_price, customer_name, table_number, 'pending'))
        conn.commit()
        order_id = cursor.lastrowid

    return jsonify({
        "id": order_id,
        "menu_id": menu_id,
        "quantity": quantity,
        "total_price": total_price,
        "customer_name": customer_name,
        "table_number": table_number,
        "status": "pending"
    }), 201

@app.route('/orders', methods=['GET'])
def get_all_orders():
    with get_db_connection() as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("""
            SELECT o.*, m.name as menu_name 
            FROM orders o 
            JOIN menus m ON o.menu_id = m.id 
            ORDER BY o.created_at DESC
        """)
        orders = cursor.fetchall()

    return jsonify([dict(o) for o in orders]), 200

@app.route('/orders/<int:order_id>/status', methods=['PUT'])
def update_order_status(order_id):
    if not request.is_json:
        return jsonify({"error": "JSON required"}), 400
    
    data = request.get_json()
    status = data.get('status')
    if status not in ['pending', 'confirmed', 'done', 'cancelled']:
        return jsonify({"error": "Invalid status"}), 400

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("UPDATE orders SET status = ? WHERE id = ?", (status, order_id))
        if cursor.rowcount == 0:
            return jsonify({"error": "Order not found"}), 404
        conn.commit()

    return jsonify({"message": "Status updated", "new_status": status}), 200

if __name__ == '__main__':
    init_db()
    app.run(port=5002, debug=True)
