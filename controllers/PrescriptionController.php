<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class PrescriptionController
{
    private $prescriptionModel;
    private $appointmentModel;

    public function __construct()
    {
        Auth::requireRole(
            'admin',
            'doctor',
            'patient'
        );

        $this->prescriptionModel =
            new PrescriptionModel();

        $this->appointmentModel =
            new AppointmentModel();
    }

    public function index()
    {
        Auth::requireRole('patient');

        $user = Auth::currentUser();

        $prescriptions =
            $this->prescriptionModel
            ->getByPatient($user['id']);

        require __DIR__ .
            '/../views/prescriptions/index.php';
    }

    public function create()
    {
        Auth::requireRole('doctor');

        $appointmentId =
            (int)$_GET['appointment_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdf = null;

if (
    isset($_FILES['prescription_file']) &&
    $_FILES['prescription_file']['error'] === 0
) {

    $pdf =
        time() . "_" .
        basename(
            $_FILES['prescription_file']['name']
        );

    move_uploaded_file(
        $_FILES['prescription_file']['tmp_name'],
        __DIR__ .
        '/../public/uploads/prescriptions/' .
        $pdf
    );
}

            $this->prescriptionModel->create([
[
    'appointment_id' => $appointmentId,
    'diagnosis' => $_POST['diagnosis'],
    'medications' => $_POST['medications'],
    'notes' => $_POST['notes'],
    'file_path' => $pdf
]
            ]);

            header(
                'Location: index.php?page=appointments'
            );
            exit;
        }

        require __DIR__ .
            '/../views/prescriptions/create.php';
    }
}