<?php
// controllers/AuthController.php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // عرض صفحة تسجيل الدخول
    public function loginView() {
        // إذا كان المستخدم مسجل دخوله بالفعل، ننقله للـ dashboard مباشرة
        if (Auth::check()) {
            header("Location: " . BASE_URL . "?page=dashboard");
            exit();
        }
        
        // تضمين ملف الـ View الخاص باللوجن
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // معالجة البيانات القادمة من فورم تسجيل الدخول (POST)
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=auth&action=login");
            exit();
        }

        // 1. التحقق من توكن الـ CSRF للأمان
        $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        if (!CSRF::validate($token)) {
            die("خطأ أمني: طلب غير مصرح به (CSRF Token Invalid).");
        }

        // 2. استقبال وتطهير المدخلات (Sanitization الصامتة)
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // 3. التحقق من تعبئة الحقول
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "يرجى كتابة البريد الإلكتروني وكلمة المرور.";
            header("Location: " . BASE_URL . "?page=auth&action=login");
            exit();
        }

        // 4. البحث عن المستخدم في قاعدة البيانات أولاً
        $user = $this->userModel->findByEmail($email);

        // 5. التحقق المؤقت من الحساب وكلمة المرور (نص عادي للتجربة السريعة والمضمونة)
        if ($user && $password === $user['password']) {
            
            if ((int)$user['is_active'] !== 1) {
                $_SESSION['error'] = "هذا الحساب معطل حالياً. يرجى مراجعة المسؤول.";
                header("Location: " . BASE_URL . "?page=auth&action=login");
                exit();
            }

            // تسجيل الدخول بنجاح وتخزين البيانات بالسيشين وتجديد المعرف للأمان
            Auth::login($user);
            
            // التوجيه للوحة التحكم
            header("Location: " . BASE_URL . "?page=dashboard");
            exit();
        } else {
            // رسالة خطأ عامة وموحدة
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
            header("Location: " . BASE_URL . "?page=auth&action=login");
            exit();
        }
    }

    // تسجيل الخروج
    public function logout() {
        Auth::logout();
        header("Location: " . BASE_URL . "?page=auth&action=login");
        exit();
    }
}