<?php
// models/UserModel.php

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {

    /**
     * البحث عن مستخدم بواسطة البريد الإلكتروني (مهمة جداً لعملية الـ Login)
     * * @param string $email
     * @return array|null يعيد مصفوفة ببيانات المستخدم أو null إذا لم يجده
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        
        // تنفيذ الاستعلام بأمان (s تعني string)
        $result = $this->db->query($sql, "s", [$email]);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // تحويل النتيجة لمصفوفة associative
        }
        
        return null;
    }

    /**
     * البحث عن مستخدم بواسطة الـ ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $result = $this->db->query($sql, "i", [$id]); // (i تعني integer)
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    // جلب جميع الأطباء مع تخصصاتهم وبياناتهم الأساسية بعملية JOIN
    public function getAllDoctors() {
        $sql = "SELECT users.*, doctors.bio, doctors.consultation_fee, doctors.available_days, specializations.name AS specialization_name 
                FROM users 
                INNER JOIN doctors ON users.id = doctors.user_id 
                INNER JOIN specializations ON doctors.specialization_id = specializations.id 
                WHERE users.role = 'doctor' 
                ORDER BY users.id DESC";
        $result = $this->db->query($sql);
        
        $doctors = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }

    // إنشاء حساب طبيب كامل (استخدام Transaction لضمان إدخال الجدولين معاً أو إلغاء العملية كاملة عند الخطأ)
    public function createDoctor($userData, $doctorData) {
        // 1. فحص هل الإيميل مكرر
        if ($this->findByEmail($userData['email'])) {
            return "email_exists";
        }

        // تشفير كلمة المرور بـ BCRYPT حصراً للأمان كما يطلب النظام
        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        // نقوم بتنفيذ الاستعلام الأول لإدخال المستخدم في جدول users
        $sqlUser = "INSERT INTO users (name, email, password, role, phone, is_active) VALUES (?, ?, ?, 'doctor', ?, 1)";
        $resUser = $this->db->query($sqlUser, "ssss", [
            $userData['name'], 
            $userData['email'], 
            $hashedPassword, 
            $userData['phone']
        ]);

        if (!$resUser) return false;

        // جلب الـ ID الذي تم إنشاؤه تلقائياً للطبيب
        $newUserId = $this->db->lastInsertId();

        // 2. إدخال التفاصيل الطبية في جدول doctors وربطه بـ user_id الجديد
        $sqlDoc = "INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, available_days) VALUES (?, ?, ?, ?, ?)";
        $resDoc = $this->db->query($sqlDoc, "iidss", [
            $newUserId,
            $doctorData['specialization_id'],
            $doctorData['bio'],
            $doctorData['consultation_fee'],
            $doctorData['available_days']
        ]);

        return $resDoc;
    }

    // تبديل حالة المستخدم (تفعيل / تعطيل) Toggle Active Status
    public function toggleStatus($id) {
        // جلب الحالة الحالية أولاً للتعاكس معها
        $user = $this->findById($id);
        if (!$user) return false;

        $newStatus = ($user['is_active'] == 1) ? 0 : 1;
        $sql = "UPDATE users SET is_active = ? WHERE id = ?";
        return $this->db->query($sql, "ii", [$newStatus, $id]);
    }
}