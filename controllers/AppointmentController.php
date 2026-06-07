<?php
// controllers/AppointmentController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/UserModel.php'; // لجلب قائمة الأطباء المتاحين للحجز

class AppointmentController {
    private $apptModel;
    private $userModel;

    public function __construct() {
        // حماية: يجب أن يكون المستخدم مريضاً للوصول لهذه العمليات
        Auth::requireRole('patient');
        $this->apptModel = new AppointmentModel();
        $this->userModel = new UserModel();
    }

    // عرض جدول مواعيد المريض الحالي
    public function index() {
        $patientId = Auth::user('id');
        $appointments = $this->apptModel->getByPatient($patientId);
        
        require_once __DIR__ . '/../views/patient/appointments.php';
    }

    // عرض شاشة حجز موعد جديد
    public function createView() {
        // جلب قائمة الأطباء ليختار المريض طبيبه منهم
        $doctors = $this->userModel->getAllDoctors();
        require_once __DIR__ . '/../views/patient/book.php';
    }

    // معالجة طلب الحجز (POST)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=appointments");
            exit();
        }

        if (!isset($_POST['csrf_token']) || !CSRF::validate($_POST['csrf_token'])) {
            die("خطأ أمني: توكن غير صالح.");
        }

        $patientId = Auth::user('id');
        $doctorId  = (int)$_POST['doctor_id'];
        $appt_date = trim($_POST['appt_date']);
        $appt_time = trim($_POST['appt_time']);
        $reason    = trim($_POST['reason']);

        if ($doctorId <= 0 || empty($appt_date) || empty($appt_time)) {
            $_SESSION['error'] = "يرجى اختيار الطبيب، التاريخ، والوقت بالشكل الصحيح.";
            header("Location: " . BASE_URL . "?page=appointments&action=book");
            exit();
        }

        // تنفيذ الحجز عبر الموديل
        $result = $this->apptModel->create($patientId, $doctorId, $appt_date, $appt_time, $reason);

        if ($result === "conflict") {
            $_SESSION['error'] = "عذراً، هذا الطبيب لديه موعد آخر في نفس الوقت والتاريخ المختارين. يرجى اختيار وقت آخر.";
            header("Location: " . BASE_URL . "?page=appointments&action=book");
        } elseif ($result) {
            $_SESSION['success'] = "تم تقديم طلب حجز الموعد بنجاح، بانتظار تأكيد الطبيب.";
            header("Location: " . BASE_URL . "?page=appointments");
        } else {
            $_SESSION['error'] = "حدث خطأ غير متوقع أثناء الحجز.";
            header("Location: " . BASE_URL . "?page=appointments&action=book");
        }
        exit();
    }
}