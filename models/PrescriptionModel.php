<?php
// models/PrescriptionModel.php

require_once __DIR__ . '/BaseModel.php';

class PrescriptionModel extends BaseModel {

    // إضافة روشتة جديدة وتحديث حالة الموعد إلى مكتمل (Completed)
    public function create($apptId, $diagnosis, $medications, $notes, $filePath) {
        $sql = "INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path) VALUES (?, ?, ?, ?, ?)";
        return $this->db->query($sql, "issss", [$apptId, $diagnosis, $medications, $notes, $filePath]);
    }
}