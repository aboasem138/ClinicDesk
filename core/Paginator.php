<?php
// core/Paginator.php

class Paginator {
    private $db;
    private $limit;
    private $page;
    private $total_rows;
    private $total_pages;

    public function __construct($limit = 10) {
        $this->db = Database::getInstance();
        $this->limit = $limit;
        // جلب رقم الصفحة الحالية من الرابط، والافتراضي هو الصفحة رقم 1
        $this->page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
    }

    /**
     * دالة لتشغيل الاستعلام مع حساب الصفحات وإعادة النتائج فقط للصفحة الحالية
     */
    public function paginate($countSql, $dataSql, $types = "", array $params = []) {
        // 1. جلب العدد الإجمالي للصفوف أولاً
        $countResult = $this->db->query($countSql, $types, $params);
        $row = $countResult->fetch_row();
        $this->total_rows = $row[0];

        // 2. حساب إجمالي عدد الصفحات
        $this->total_pages = ceil($this->total_rows / $this->limit);
        
        // التأكد من أن الصفحة المطلوبة لا تتعدى الإجمالي
        if ($this->page > $this->total_pages && $this->total_pages > 0) {
            $this->page = $this->total_pages;
        }

        // 3. حساب نقطة البداية (Offset) للاستعلام
        $offset = ($this->page - 1) * $this->limit;

        // 4. تعديل استعلام البيانات وإضافة LIMIT و OFFSET
        $dataSql .= " LIMIT {$this->limit} OFFSET {$offset}";

        // 5. تنفيذ استعلام البيانات الفعلي وإعادة النتيجة
        return $this->db->query($dataSql, $types, $params);
    }

    /**
     * دالة توليد أزرار التنقل (Pagination HTML) المتوافقة مع ثيم AdminLTE
     */
    public function renderLinks($baseUrlPath) {
        if ($this->total_pages <= 1) return '';

        $html = '<ul class="pagination pagination-sm m-0 float-left">';
        
        // زر الصفحة السابقة
        if ($this->page > 1) {
            $prev = $this->page - 1;
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrlPath . '&p=' . $prev . '">&raquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>';
        }

        // أرقام الصفحات
        for ($i = 1; $i <= $this->total_pages; $i++) {
            $active = ($this->page == $i) ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $baseUrlPath . '&p=' . $i . '">' . $i . '</a></li>';
        }

        // زر الصفحة التالية
        if ($this->page < $this->total_pages) {
            $next = $this->page + 1;
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrlPath . '&p=' . $next . '">&laquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>';
        }

        $html .= '</ul>';
        return $html;
    }
}