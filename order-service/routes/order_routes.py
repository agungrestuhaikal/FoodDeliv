from flask import Blueprint
# Import Controller (akan dibuat di Langkah 3)
from controllers import order_controller 

# Membuat Blueprint untuk Order Service API
order_bp = Blueprint('order', __name__)

# POST /api/orders: Endpoint Inti Transaksi (Tugas Anda)
order_bp.route('/orders', methods=['POST'])(order_controller.create_order)

# GET /api/orders/restaurant/<int:restaurant_id>: Tugas Tim Lain
order_bp.route('/orders/restaurant/<int:restaurant_id>', methods=['GET'])(order_controller.get_orders_by_restaurant)

# PUT /api/orders/<int:order_id>/status: Tugas Tim Lain
order_bp.route('/orders/<int:order_id>/status', methods=['PUT'])(order_controller.update_order_status)

# DELETE /api/orders/<int:order_id>: Tugas Tim Lain
order_bp.route('/orders/<int:order_id>', methods=['DELETE'])(order_controller.delete_order)