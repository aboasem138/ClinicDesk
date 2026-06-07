<?php
// controllers/ReportController.php

require_once __DIR__ . '/../core/Paginator.php';

class ReportController {
    private $db;

    public function __construct() {
        // حماية النظام: الآدمن فقط من يمكنه رؤية وتصدير التقارير العامة
        Auth::requireRole('admin');
        $this->db = Database::getInstance();
    }

    // عرض شاشة التقارير مع تطبيق الـ Paginator المطور
    public function index() {
        $paginator = new Paginator(5); // عرض 5 مواعيد فقط في كل صفحة لتجربة التنقل

        $countSql = "SELECT COUNT(id) FROM appointments";
        $dataSql  = "SELECT appointments.*, p.name AS patient_name, d_user.name AS doctor_name 
                     FROM appointments 
                     INNER JOIN users p ON appointments.patient_id = p.id 
                     INNER JOIN doctors d ON appointments.doctor_id = d.id 
                     INNER JOIN users d_user ON d.user_id = d_user.id 
                     ORDER BY appointments.appt_date DESC";

        // تشغيل التقسيم لجلب مواعيد الصفحة الحالية فقط
        $appointmentsResult = $paginator->paginate($countSql, $dataSql);
        
        $appointments = [];
        if ($appointmentsResult && $appointmentsResult->num_rows > 0) {
            while ($row = $appointmentsResult->fetch_assoc()) {
                $appointments[] = $row;
            }
        }

        // توليد أزرار التنقل
        $paginationLinks = $paginator->renderLinks(BASE_URL . "?page=reports&action=index");

        require_once __DIR__ . '/../views/admin/reports.php';
    }

    // تصدير كل المواعيد بصيغة ملف CSV للإكسل
    public function exportCSV() {
        // 1. جلب البيانات بالكامل بدون LIMIT
        $sql = "SELECT appointments.id, p.name AS patient_name, d_user.name AS doctor_name, appointments.appt_date, appointments.appt_time, appointments.status 
                FROM appointments 
                INNER JOIN users p ON appointments.patient_id = p.id 
                INNER JOIN doctors d ON appointments.doctor_id = d.id 
                INNER JOIN users d_user ON d.user_id = d_user.id 
                ORDER BY appointments.id ASC";
                
        $result = $this->db->query($sql);

        // 2. تعديل الـ HTTP Headers لتنبيه المتصفح أن هذا ملف قابل للتحميل (مهمة جداً في المناقشة!)
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=appointments_report_' . date('Y-m-d') . '.csv');

        // 3. فتح مجرى المخرجات المباشر (Output Stream) للتحميل الفوري الموفر للذاكرة
        $output = fopen('php://output', 'w');

        // إضافة ترويسة ملف الـ CSV (اسم كل عمود) مع تضمين الـ BOM لدعم اللغة العربية في Excel بشكل سليم
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['رقم الموعد', 'اسم المريض', 'اسم الطبيب', 'تاريخ الموعد', 'وقت الموعد', 'الحالة']);

        // 4. كتابة السطور والبيانات داخل الملف
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, [
                    $row['id'],
                    $row['patient_name'],
                    $row['doctor_name'],
                    $row['appt_date'],
                    $row['appt_time'],
                    ucfirst($row['status'])
                ]);
            }
        }

        fclose($output);
        exit(); // إيقاف تنفيذ السكريبت لكي لا يتم طباعة أي وسم HTML داخل الملف المخزن
    }
}