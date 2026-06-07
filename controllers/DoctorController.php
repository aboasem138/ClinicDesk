```php
<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class DoctorController
{
    private $doctorModel;
    private $userModel;
    private $specializationModel;

    public function __construct()
    {
        Auth::requireRole('admin');

        $this->doctorModel = new DoctorModel();
        $this->userModel = new UserModel();
        $this->specializationModel = new SpecializationModel();
    }

    public function index()
    {
        $page = (int)($_GET['p'] ?? 1);

        $doctors = $this->doctorModel->getAllPaginated($page);

        require __DIR__ . '/../views/doctors/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                !CSRF::validateToken(
                    $_POST['csrf_token'] ?? ''
                )
            ) {
                die('Invalid CSRF Token');
            }

            $days = implode(
                ",",
                $_POST['available_days'] ?? []
            );
            $photo = null;

if (
    isset($_FILES['photo']) &&
    $_FILES['photo']['error'] === 0
) {

    $photo =
        time() . "_" .
        basename(
            $_FILES['photo']['name']
        );

    move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        __DIR__ .
        '/../public/uploads/doctor_photos/' .
        $photo
    );
}

            $this->doctorModel->create([
                'user_id' => $_POST['user_id'],
                'specialization_id' => $_POST['specialization_id'],
                'bio' => $_POST['bio'],
                'consultation_fee' => $_POST['consultation_fee'],
                'available_days' => $days,
                'photo' => $photo,
            ]);

            header('Location: index.php?page=doctors');
            exit;
        }

        $users = $this->userModel->getDoctorsOnly();

        $specializations =
            $this->specializationModel->getAll();

        require __DIR__ .
            '/../views/doctors/create.php';
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                !CSRF::validateToken(
                    $_POST['csrf_token'] ?? ''
                )
            ) {
                die('Invalid CSRF Token');
            }

            $days = implode(
                ",",
                $_POST['available_days'] ?? []
            );

            $this->doctorModel->update(
                $id,
                [
                    'specialization_id' =>
                        $_POST['specialization_id'],

                    'bio' =>
                        $_POST['bio'],

                    'consultation_fee' =>
                        $_POST['consultation_fee'],

                    'available_days' =>
                        $days
                ]
            );

            header(
                'Location: index.php?page=doctors'
            );
            exit;
        }

        $doctor =
            $this->doctorModel
            ->findById($id);

        $specializations =
            $this->specializationModel->getAll();

        require __DIR__ .
            '/../views/doctors/edit.php';
    }
}