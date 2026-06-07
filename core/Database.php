<?php
// core/Database.php

class Database {
    // الخاصية التي ستحتفظ بالكائن الوحيد من هذا الكلاس
    private static $instance = null;
    // متغير الاتصال بـ mysqli
    private $conn;

    // 1. الكونستركتور الخاص (Private) يمنع إنشاء الكائن عبر كود الخارجي باستخدام new
    private function __construct() {
        // الاتصال بقاعدة البيانات باستخدام الثوابت المعرفة في ملف الإعدادات
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // فحص نجاح الاتصال وإلقاء استثناء آمن في حال الفشل دون إظهار تفاصيل حساسة للعيان
        if ($this->conn->connect_error) {
            throw new RuntimeException("فشل الاتصال بقاعدة البيانات. يرجى مراجعة المسؤول.");
        }

        // ضبط الترميز ليدعم اللغة العربية بشكل سليم
        $this->conn->set_charset("utf8mb4");
    }

    // يمنع نسخ الكائن للأمان (Clone)
    private function __clone() {}

    // 2. الدالة الساكنة للحصول على الكائن الوحيد من الكلاس (The Singleton Provider)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * دالة موحدة لتنفيذ كافة الاستعلامات باستخدام Prepared Statements
     * 
     * @param string $sql جملة الاستعلام الاستفهامية مثل SELECT * FROM users WHERE id = ?
     * @param string $types أنواع البيانات الممررة مثل "isi" (int, string, int)
     * @param array $params المصفوفة المحتوية على القيم الفعلية للمتغيرات
     */
    public function query($sql, $types = "", array $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        // إذا كان هناك معاملات ممررة، نقوم بربطها ديناميكياً باستخدام الـ Spread Operator (...)
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        // تنفيذ الاستعلام
        $executeResult = $stmt->execute();

        // إذا كان الاستعلام من نوع SELECT، نعيد النتيجة ككائن للتعامل معها
        if (stripos($sql, 'SELECT') === 0) {
            $result = $stmt->get_result();
            $stmt->close();
            return $result; // يعيد كائن mysqli_result
        }

        // للاستعلامات الأخرى (INSERT, UPDATE, DELETE) نعيد نجاح أو فشل العملية
        $stmt->close();
        return $executeResult;
    }

    // دالة لجلب آخر معرف (ID) تم إدخاله تلقائياً في قاعدة البيانات
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
}