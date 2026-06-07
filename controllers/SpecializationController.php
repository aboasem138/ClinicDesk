<?php
// controllers/SpecializationController.php

require_once __DIR__ . '/../models/SpecializationModel.php';

class SpecializationController {
    private $specModel;

    public function __construct() {
        // حماية أمنية: لا يمكن لأي شخص دخول هذا الكنترولر إلا إذا كان آدمن
        Auth::requireRole('admin');
        $this->specModel = new SpecializationModel();
    }

    // عرض صفحة التخصصات
    public function index() {
        $specializations = $this->specModel->getAll();
        require_once __DIR__ . '/../views/admin/specializations.php';
    }

    // معالجة إضافة تخصص جديد
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=specializations");
            exit();
        }

        // فحص توكن الحماية CSRF
        if (!isset($_POST['csrf_token']) || !CSRF::validate($_POST['csrf_token'])) {
            die("خطأ أمني: طلب غير مصرح به.");
        }

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';

        if (empty($name)) {
            $_SESSION['error'] = "اسم التخصص مطلوب ولا يمكن تركه فارغاً.";
        } else {
            $result = $this->specModel->create($name);
            if ($result) {
                $_SESSION['success'] = "تم إضافة التخصص بنجاح.";
            } else {
                $_SESSION['error'] = "هذا التخصص موجود بالفعل في النظام.";
            }
        }

        header("Location: " . BASE_URL . "?page=specializations");
        exit();
    }

    // معالجة حذف تخصص
    public function destroy() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id > 0) {
            // ملاحظة للمناقشة: في قاعدة البيانات وضعنا ON DELETE RESTRICT للتخصصات
            // يعني لو التخصص مربوط بطبيب، السيرفر هيرفض الحذف تلقائياً وهيطلع خطأ آمن
            try {
                $result = $this->specModel->delete($id);
                if ($result) {
                    $_SESSION['success'] = "تم حذف التخصص بنجاح.";
                } else {
                    $_SESSION['error'] = "فشل عملية الحذف.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "لا يمكن حذف هذا التخصص لأنه مرتبط بأطباء مسجلين حالياً.";
            }
        }

        header("Location: " . BASE_URL . "?page=specializations");
        exit();
    }
}