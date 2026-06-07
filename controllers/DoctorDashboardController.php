<?php
// controllers/DoctorDashboardController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';

class DoctorDashboardController {
    private $apptModel;
    private $prescModel;

    public function __construct() {
        // حماية أمنية: الطبيب فقط من يصل لهذه الصفحة
        Auth::requireRole('doctor');
        $this->apptModel = new AppointmentModel();
        $this->prescModel = new PrescriptionModel();
    }

    // عرض مواعيد الطبيب الحالي
    public function index() {
        $doctorUserId = Auth::user('id');
        $appointments = $this->apptModel->getByDoctor($doctorUserId);
        require_once __DIR__ . '/../views/doctor/appointments.php';
    }

    // تحديث حالة الموعد (تأكيد أو إلغاء)
    public function changeStatus() {
        $apptId = isset($_GET['id']) ? (int)$GET['id'] : 0;
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $doctorUserId = Auth::user('id');

        $allowedStatuses = ['confirmed', 'cancelled'];
        if ($apptId > 0 && in_array($status, $allowedStatuses)) {
            if ($this->apptModel->updateStatus($apptId, $doctorUserId, $status)) {
                $_SESSION['success'] = "تم تحديث حالة الموعد بنجاح.";
            } else {
                $_SESSION['error'] = "فشل التحديث أو غير مصرح لك بتعديل هذا الموعد.";
            }
        }
        header("Location: " . BASE_URL . "?page=doctor_appts");
        exit();
    }

    // معالجة إتمام الكشف وإضافة الروشتة مع الرفع الآمن للملف
    public function addPrescription() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=doctor_appts");
            exit();
        }

        if (!isset($_POST['csrf_token']) || !CSRF::validate($_POST['csrf_token'])) {
            die("خطأ أمني: توكن غير صالح.");
        }

        $apptId       = (int)$_POST['appointment_id'];
        $diagnosis    = trim($_POST['diagnosis']);
        $medications  = trim($_POST['medications']);
        $notes        = trim($_POST['notes']);
        $doctorUserId = Auth::user('id');
        $filePath     = null;

        if ($apptId <= 0 || empty($diagnosis) || empty($medications)) {
            $_SESSION['error'] = "التشخيص والأدوية حقول إجبارية لإتمام الكشف.";
            header("Location: " . BASE_URL . "?page=doctor_appts");
            exit();
        }

        // --- نظام الرفع الآمن لملف الـ PDF المرفق ---
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['pdf_file']['tmp_name'];
            $fileName    = $_FILES['pdf_file']['name'];
            $fileSize    = $_FILES['pdf_file']['size'];
            
            // 1. فحص الحجم (الحد الأقصى 2 ميجابايت مثلاً)
            if ($fileSize > 2 * 1024 * 1024) {
                $_SESSION['error'] = "حجم ملف الروشتة كبير جداً، الحد الأقصى 2 ميجابايت.";
                header("Location: " . BASE_URL . "?page=doctor_appts");
                exit();
            }

            // 2. فحص نوع الملف الحقيقي (MIME Type) باستخدام finfo_file لضمان أنه PDF حقيقي (شرط أساسي في المشروع!)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);

            if ($mimeType !== 'application/pdf') {
                $_SESSION['error'] = "الملف المرفوع ليس ملف PDF صالح وأمن.";
                header("Location: " . BASE_URL . "?page=doctor_appts");
                exit();
            }

            // 3. تغيير اسم الملف لاسم عشوائي مشفر لمنع استبدال الملفات أو تخمينها
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // المجلد المستهدف
            $uploadFileDir = __DIR__ . '/../public/uploads/prescriptions/';
            
            // إنشاء المجلد إن لم يكن موجوداً
            if(!is_dir($uploadFileDir)){
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $filePath = 'public/uploads/prescriptions/' . $newFileName;
            }
        }

        // حفظ الروشتة في الداتابيز وتحديث الموعد إلى مكتمل
        $dbResult = $this->prescModel->create($apptId, $diagnosis, $medications, $notes, $filePath);
        if ($dbResult) {
            $this->apptModel->updateStatus($apptId, $doctorUserId, 'completed');
            $_SESSION['success'] = "تم إتمام الكشف بنجاح وإرسال الروشتة للمريض.";
        } else {
            $_SESSION['error'] = "حدث خطأ أثناء حفظ الروشتة، قد تكون مضافة مسبقاً.";
        }

        header("Location: " . BASE_URL . "?page=doctor_appts");
        exit();
    }
}