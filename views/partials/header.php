<?php
// views/partials/header.php
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_NAME; ?> | لوحة التحكم</title>

    <!-- Google Font: Cairo (خط ممتاز ومريح جداً للعين في الواجهات العربية) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (الأيقونات) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <!-- Bootstrap 4 RTL (نسخة تدعم اليمين لليصار) -->
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">

    <!-- Theme style AdminLTE -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/adminlte/dist/css/adminlte.min.css">

    <!-- أسطر سحرية لإجبار AdminLTE يدعم الـ RTL ويقلب القائمة لليمين -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.rtl.min.css">

    <style>
    body {
        font-family: 'Cairo', sans-serif;
        /* قمنا بتغيير الخط ليكون متناسقاً واحترافياً */
        text-align: right;
    }

    /* تعديل اتجاهات الأيقونات والأسهم في القائمة الجانبية لتناسب اليمين */
    .nav-sidebar .nav-link>p>.right {
        right: auto;
        left: 1rem;
        transform: rotate(180deg);
    }

    .nav-sidebar .menu-open>.nav-link i.right {
        transform: rotate(90deg);
    }

    .brand-link .brand-image {
        float: right;
        margin-left: .8rem;
        margin-right: -0.5rem;
    }
    </style>
</head>

<!-- أضفنا كلاس "rtl" هنا وهو المسؤول عن توجيه القالب بالكامل -->

<body class="hold-transition sidebar-mini rtl">
    <div class="wrapper">