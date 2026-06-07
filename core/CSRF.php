<?php
// core/CSRF.php

class CSRF {

    // توليد توكن عشوائي وحفظه في الجلسة
    public static function generate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // توليد نص عشوائي قوي وآمن تشفيرياً باستخدام random_bytes
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    // دالة مساعدة لطباعة حقل مخفي (Hidden Input) داخل الفورم تلقائياً
    public static function insertField() {
        $token = self::generate();
        echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    // التحقق من أن التوكن القادم من الفورم يطابق التوكن المخزن في الجلسة
    public static function validate($tokenFromRequest) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || empty($tokenFromRequest)) {
            return false;
        }

        // استخدام hash_equals لمنع ثغرات التوقيت (Timing Attacks) أثناء مقارنة النصوص
        return hash_equals($_SESSION['csrf_token'], $tokenFromRequest);
    }
}