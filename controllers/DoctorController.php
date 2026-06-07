<?php
// controllers/DoctorController.php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class DoctorController {
    private $userModel;
    private $specModel;

    public function __construct() {
        // حماية النظام: آدمن فقط من يدير الأطباء
        Auth::requireRole('admin');
        $this->userModel = new UserModel();
        $this->specModel = new SpecializationModel();
    }

    // عرض شاشة إدارة الأطباء
    public function index() {
        $doctors = $this->userModel->getAllDoctors();
        $specializations = $this->specModel->getAll(); // لعرضها في قائمة الخيارات عند الإضافة
        require_once __DIR__ . '/../views/admin/doctors.php';
    }

    // معالجة إضافة طبيب جديد
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=doctors");
            exit();
        }

        // حماية CSRF
        if (!isset($_POST['csrf_token']) || !CSRF::validate($_POST['csrf_token'])) {
            die("خطأ أمني: توكن غير صالح.");
        }

        // تجميع وتطهير البيانات القادمة من الفورم
        $userData = [
            'name'     => trim($_POST['name']),
            'email'    => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'phone'    => trim($_POST['phone'])
        ];

        // تجميع الأيام المختارة وتحويلها لنص يفصل بينه فاصلة مثل (Sun,Mon,Tue)
        $days = isset($_POST['available_days']) ? implode(',', $_POST['available_days']) : 'Sun,Mon,Tue,Wed,Thu';

        $doctorData = [
            'specialization_id' => (int)$_POST['specialization_id'],
            'bio'               => trim($_POST['bio']),
            'consultation_fee'  => (float)$_POST['consultation_fee'],
            'available_days'    => $days
        ];

        // التحقق من الحقول الإجبارية
        if (empty($userData['name']) || empty($userData['email']) || empty($userData['password']) || $doctorData['specialization_id'] <= 0) {
            $_SESSION['error'] = "يرجى تعبئة جميع الحقول الأساسية المطلوبة.";
            header("Location: " . BASE_URL . "?page=doctors");
            exit();
        }

        $result = $this->userModel->createDoctor($userData, $doctorData);

        if ($result === "email_exists") {
            $_SESSION['error'] = "البريد الإلكتروني مسجل مسبقاً لمستخدم آخر.";
        } elseif ($result) {
            $_SESSION['success'] = "تم إنشاء حساب الطبيب وإضافته للنظام بنجاح.";
        } else {
            $_SESSION['error'] = "حدث خطأ أثناء حفظ البيانات بقاعدة البيانات.";
        }

        header("Location: " . BASE_URL . "?page=doctors");
        exit();
    }

    // تبديل حالة الطبيب (تفعيل/تعطيل)
    public function toggle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // منع الآدمن من تعطيل حسابه الشخصي
        if ($id === (int)Auth::user('id')) {
            $_SESSION['error'] = "لا يمكنك تجميد أو تعطيل حسابك الحالي!";
            header("Location: " . BASE_URL . "?page=doctors");
            exit();
        }

        if ($id > 0) {
            if ($this->userModel->toggleStatus($id)) {
                $_SESSION['success'] = "تم تحديث حالة الحساب بنجاح.";
            } else {
                $_SESSION['error'] = "فشل تحديث حالة الحساب.";
            }
        }

        header("Location: " . BASE_URL . "?page=doctors");
        exit();
    }
}