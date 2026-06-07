<?php
// models/BaseModel.php

class BaseModel {
    // خاصية محمية تسمح للكلاسات التي ترث هذا الكلاس بالوصول للـ DB
    protected $db;

    public function __construct() {
        // جلب الاتصال الموحد من كلاس Database
        $this->db = Database::getInstance();
    }
}