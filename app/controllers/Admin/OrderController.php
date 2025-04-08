<?php

namespace App\Controllers\Admin;

use App\Models\Order;
use Exception;

class OrderController {
    public function getOrderDetail($orderId) {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (empty($orderId)) {
                throw new Exception('Thiếu mã đơn hàng');
            }

            $orderModel = $this->loadModel('Order');
            
            // Lấy thông tin đơn hàng
            $order = $orderModel->getOrderById($orderId);
            if (!$order) {
                throw new Exception('Không tìm thấy đơn hàng');
            }
            
            // Lấy chi tiết đơn hàng
            $orderDetails = $orderModel->getOrderDetails($orderId);
            
            // Debug để kiểm tra dữ liệu
            error_log('Order data: ' . print_r($order, true));
            error_log('Order details: ' . print_r($orderDetails, true));
            
            $response = [
                'order' => $order,
                'orderDetails' => $orderDetails
            ];
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
} 