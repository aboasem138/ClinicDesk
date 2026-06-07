<?php
// core/Auth.php

class Auth {
    
    // بدء الجلسة بشكل آمن إذا لم تكن قد بدأت بعد
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // إعدادات أمان إضافية للـ Cookie الخاصة بالجلسة
            ini_set('session.cookie_httponly', 1); // تمنع الوصول للـ Cookie عبر الجافاسكريبت لحماية من الـ XSS
            ini_set('session.use_only_cookies', 1); // إجبار النظام على استخدام الكوكيز فقط وليس روابط URL
            
            session_start();
        }
    }

    // تسجيل دخول المستخدم وحفظ بياناته في الجلسة
    public static function login($user) {
        self::init();
        
        // تجديد معرف الجلسة لمنع ثغرة Session Fixation (مهمة جداً للمناقشة!)
        session_regenerate_id(true);
        
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email']= $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time(); // لتتبع وقت الخمول لاحقاً إن لزم الأمر
    }

    // تسجيل الخروج وتدمير الجلسة بالكامل
    public static function logout() {
        self::init();
        
        // تفريغ مصفوفة السيشين
        $_SESSION = array();

        // حذف كوكيز الجلسة من المتصفح
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // تدمير السيشين نهائياً
        session_destroy();
    }

    // فحص هل المستخدم مسجل دخوله حالياً أم لا
    public static function check() {
        self::init();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // جلب بيانات المستخدم الحالي المخزنة في السيشين
    public static function user($key = null) {
        self::init();
        if (!self::check()) return null;
        
        if ($key) {
            return isset($_SESSION['user_' . $key]) ? $_SESSION['user_' . $key] : (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
        }
        
        return $_SESSION;
    }

    // إجبار المستخدم على أن يكون مسجلاً للدخول، وإلا يتم تحويله لصفحة اللوجن
    public static function requireLogin() {
        if (!self::check()) {
            header("Location: " . BASE_URL . "?page=auth&action=login");
            exit();
        }
    }

    // التحقق من دور المستخدم (مثلاً هل هو آدمن؟) وإذا لم يكن كذلك يتم منعه فوراً
    public static function requireRole($allowedRoles) {
        // أولاً يجب أن يكون مسجل دخول
        self::requireLogin();
        
        $userRole = self::user('role');
        
        // إذا تم تمرير دور واحد كنص، نحوله لمصفوفة
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        // فحص هل دور المستخدم الحالي موجود ضمن الأدوار المسموح لها بدخول الصفحة
        if (!in_array($userRole, $allowedRoles)) {
            // إرسال كود استجابة غير مصرح به 403 وعرض رسالة خطأ
            http_response_code(403);
            die("<h1>403 - غير مسموح لك بالوصول إلى هذه الصفحة.</h1>");
        }
    }
}