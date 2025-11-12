import sqlite3
import os
import contextlib
import time
from flask import Flask, request, jsonify, send_from_directory
from werkzeug.utils import secure_filename
from flask_cors import CORS

app = Flask(__name__)

CORS(app, resources={
    r"/*": {
        "origins": ["http://127.0.0.1:8000", "http://localhost:8000"],
        "methods": ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
        "allow_headers": ["Content-Type", "Authorization"]
    }
})

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
    print("Menu Service: DB initialized")

UPLOAD_FOLDER = os.path.join(os.path.dirname(__file__), 'uploads')
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

@app.route('/menus', methods=['POST'])
def create_menu():
    if 'multipart/form-data' not in request.content_type:
        return jsonify({"error": "Use form-data"}), 415

    name = request.form.get('name')
    price = request.form.get('price')
    category = request.form.get('category')
    description = request.form.get('description', '')
    image_file = request.files.get('image')

    if not all([name, price, category, image_file]):
        return jsonify({"error": "name, price, category, image required"}), 400

    try:
        price = float(price)
    except:
        return jsonify({"error": "price must be number"}), 400

    if not allowed_file(image_file.filename):
        return jsonify({"error": "File type not allowed"}), 400

    filename = secure_filename(image_file.filename)
    name_part, ext = os.path.splitext(filename)
    unique_filename = f"{name_part}_{int(time.time())}{ext}"
    filepath = os.path.join(app.config['UPLOAD_FOLDER'], unique_filename)
    image_file.save(filepath)

    image_url = f"http://127.0.0.1:5002/uploads/{unique_filename}"

    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO menus (name, description, price, category, image_url)
            VALUES (?, ?, ?, ?, ?)
        """, (name, description, price, category, image_url))
        conn.commit()
        menu_id = cursor.lastrowid

    return jsonify({
        "id": menu_id,
        "name": name,
        "description": description,
        "price": price,
        "category": category,
        "image_url": image_url
    }), 201


@app.route('/menus/<int:menu_id>', methods=['PUT'])
def update_menu(menu_id):
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT image_url FROM menus WHERE id = ?", (menu_id,))
        old = cursor.fetchone()
        if not old:
            return jsonify({"error": "Not found"}), 404
    old_image_url = old[0]

    if 'multipart/form-data' not in request.content_type:
        return jsonify({"error": "Use form-data"}), 415

    name = request.form.get('name')
    price = request.form.get('price')
    category = request.form.get('category')
    description = request.form.get('description')

    update_data = {}
    if name: update_data['name'] = name
    if description is not None: update_data['description'] = description
    if price is not None:
        try:
            update_data['price'] = float(price)
        except:
            return jsonify({"error": "Invalid price"}), 400
    if category: update_data['category'] = category

    final_image_url = old_image_url
    image_file = request.files.get('image')

    if image_file and image_file.filename:
        if not allowed_file(image_file.filename):
            return jsonify({"error": "Invalid file type"}), 400
        filename = secure_filename(image_file.filename)
        name_part, ext = os.path.splitext(filename)
        unique_filename = f"{name_part}_{int(time.time())}{ext}"
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], unique_filename)
        image_file.save(filepath)
        final_image_url = f"http://127.0.0.1:5002/uploads/{unique_filename}"
        update_data['image_url'] = final_image_url

    if update_data:
        set_clause = ", ".join([f"{k} = ?" for k in update_data])
        values = list(update_data.values()) + [menu_id]
        with get_db_connection() as conn:
            cursor = conn.cursor()
            cursor.execute(f"UPDATE menus SET {set_clause} WHERE id = ?", values)
            conn.commit()

    if old_image_url and final_image_url != old_image_url:
        old_path = old_image_url.replace("http://127.0.0.1:5002/uploads/", os.path.join(app.config['UPLOAD_FOLDER'], ""))
        if os.path.exists(old_path):
            try:
                os.remove(old_path)
            except:
                pass

    return jsonify({"message": "Updated"}), 200

@app.route('/menus', methods=['GET'])
def get_all_menus():
    with get_db_connection() as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM menus")
        menus = cursor.fetchall()
    return jsonify([dict(m) for m in menus]), 200

@app.route('/menus/<int:menu_id>', methods=['GET'])
def get_menu(menu_id):
    with get_db_connection() as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM menus WHERE id = ?", (menu_id,))
        menu = cursor.fetchone()
    if not menu:
        return jsonify({"error": "Not found"}), 404
    return jsonify(dict(menu)), 200

@app.route('/menus/<int:menu_id>', methods=['DELETE'])
def delete_menu(menu_id):
    with get_db_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT image_url FROM menus WHERE id = ?", (menu_id,))
        menu = cursor.fetchone()
        if not menu:
            return jsonify({"error": "Not found"}), 404
        cursor.execute("DELETE FROM menus WHERE id = ?", (menu_id,))
        conn.commit()

    if menu[0]:
        filepath = menu[0].replace("http://127.0.0.1:5002/uploads/", os.path.join(app.config['UPLOAD_FOLDER'], ""))
        if os.path.exists(filepath):
            try:
                os.remove(filepath)
            except:
                pass

    return jsonify({"message": "Deleted"}), 200

if __name__ == '__main__':
    init_db()
    print("Menu Service: http://127.0.0.1:5002")
    app.run(host='127.0.0.1', port=5002, debug=True)