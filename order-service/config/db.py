import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv() # Memuat variabel dari .env

def get_db_connection():
    # Mengembalikan objek koneksi database menggunakan variabel dari .env
    try:
        conn = mysql.connector.connect(
            host=os.environ.get('DB_HOST'),
            user=os.environ.get('DB_USER'),
            password=os.environ.get('DB_PASSWORD'),
            database=os.environ.get('DB_DATABASE')
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Error: Koneksi DB Gagal: {err}")
        return None