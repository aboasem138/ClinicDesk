<?php
// models/SpecializationModel.php

require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel {

    // جلب جميع التخصصات
    public function getAll() {
        $sql = "SELECT * FROM specializations ORDER BY id DESC";
        $result = $this->db->query($sql);
        
        $specs = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $specs[] = $row;
            }
        }
        return $specs;
    }

    // إضافة تخصص جديد مع فحص التكرار
    public function create($name) {
        // فحص هل الاسم موجود مسبقاً لمنع تكراره
        $checkSql = "SELECT id FROM specializations WHERE name = ? LIMIT 1";
        $checkResult = $this->db->query($checkSql, "s", [$name]);
        if ($checkResult && $checkResult->num_rows > 0) {
            return false; // التخصص موجود بالفعل
        }

        $sql = "INSERT INTO specializations (name) VALUES (?)";
        return $this->db->query($sql, "s", [$name]);
    }

    // حذف تخصص بواسطة الـ ID
    public function delete($id) {
        $sql = "DELETE FROM specializations WHERE id = ?";
        return $this->db->query($sql, "i", [$id]);
    }
}