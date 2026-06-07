<?php
// models/AppointmentModel.php

require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel {

    // جلب مواعيد مريض معين مع بيانات الطبيب والتخصص (JOIN)
    public function getByPatient($patientId) {
        $sql = "SELECT appointments.*, users.name AS doctor_name, specializations.name AS specialization_name 
                FROM appointments 
                INNER JOIN doctors ON appointments.doctor_id = doctors.id 
                INNER JOIN users ON doctors.user_id = users.id 
                INNER JOIN specializations ON doctors.specialization_id = specializations.id 
                WHERE appointments.patient_id = ? 
                ORDER BY appointments.appt_date DESC, appointments.appt_time DESC";
                
        $result = $this->db->query($sql, "i", [$patientId]);
        
        $appts = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appts[] = $row;
            }
        }
        return $appts;
    }

    // حجز موعد جديد مع فحص التضارب (Double Booking)
    public function create($patientId, $doctorId, $date, $time, $reason) {
        // 1. فحص هل الطبيب محجوز في نفس التاريخ والوقت؟
        $checkSql = "SELECT id FROM appointments WHERE doctor_id = ? AND appt_date = ? AND appt_time = ? AND status != 'cancelled' LIMIT 1";
        $checkResult = $this->db->query($checkSql, "iss", [$doctorId, $date, $time]);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            return "conflict"; // هناك تضارب في الموعد!
        }

        // 2. إذا كان الوقت متاحاً، يتم إدراج الموعد بحالة 'pending' افتراضياً
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        return $this->db->query($sql, "iisss", [$patientId, $doctorId, $date, $time, $reason]);
    }
    // جلب المواعيد الخاصة بطبيب معين بناءً على الـ user_id الخاص به (JOIN)
    public function getByDoctor($doctorUserId) {
        $sql = "SELECT appointments.*, users.name AS patient_name, users.phone AS patient_phone 
                FROM appointments 
                INNER JOIN users ON appointments.patient_id = users.id 
                INNER JOIN doctors ON appointments.doctor_id = doctors.id 
                WHERE doctors.user_id = ? 
                ORDER BY appointments.appt_date DESC, appointments.appt_time ASC";
                
        $result = $this->db->query($sql, "i", [$doctorUserId]);
        
        $appts = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appts[] = $row;
            }
        }
        return $appts;
    }

    // تحديث حالة الموعد مع فحص الملكية (الملكية تعني: هل الموعد يخص هذا الطبيب فعلاً؟)
    public function updateStatus($apptId, $doctorUserId, $newStatus) {
        // فحص الملكية أولاً للأمان لمنع الطبيب من تعديل مواعيد أطباء آخرين عبر تغيير الـ ID في الرابط
        $checkSql = "SELECT appointments.id FROM appointments 
                     INNER JOIN doctors ON appointments.doctor_id = doctors.id 
                     WHERE appointments.id = ? AND doctors.user_id = ? LIMIT 1";
        $checkResult = $this->db->query($checkSql, "ii", [$apptId, $doctorUserId]);
        
        if (!$checkResult || $checkResult->num_rows === 0) {
            return false; // الموعد لا يخص هذا الطبيب!
        }

        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        return $this->db->query($sql, "si", [$newStatus, $apptId]);
    }
}