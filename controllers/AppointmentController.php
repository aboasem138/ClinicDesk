<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

class AppointmentController
{
    private $appointmentModel;
    private $doctorModel;

    public function __construct()
    {
        Auth::requireRole(
            'admin',
            'doctor',
            'patient'
        );

        $this->appointmentModel =
            new AppointmentModel();

        $this->doctorModel =
            new DoctorModel();
    }

    public function index()
    {
        $role = Auth::role();

        $page = (int)($_GET['p'] ?? 1);

        $user = Auth::currentUser();

        if ($role === 'admin') {

            $appointments =
                $this->appointmentModel
                ->getAll($page);

        } elseif ($role === 'patient') {

            $appointments =
                $this->appointmentModel
                ->getByPatient(
                    $user['id'],
                    $page
                );

        } else {

            $doctor =
                $this->doctorModel
                ->findByUserId(
                    $user['id']
                );

            $appointments =
                $this->appointmentModel
                ->getByDoctor(
                    $doctor['id'],
                    $page
                );
        }

        require __DIR__ .
            '/../views/appointments/index.php';
    }

    public function create()
    {
        Auth::requireRole('patient');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                $this->appointmentModel
                ->hasConflict(
                    $_POST['doctor_id'],
                    $_POST['appt_date'],
                    $_POST['appt_time']
                )
            ) {

                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' =>
                    'This slot is already booked'
                ];

                header(
                    'Location: index.php?page=appointments&action=create'
                );
                exit;
            }
            if (
    !CSRF::validateToken(
        $_POST['csrf_token'] ?? ''
    )
) {
    die('Invalid CSRF Token');
}

            $this->appointmentModel->book([
                'patient_id' =>
                    Auth::currentUser()['id'],

                'doctor_id' =>
                    $_POST['doctor_id'],

                'appt_date' =>
                    $_POST['appt_date'],

                'appt_time' =>
                    $_POST['appt_time'],

                'reason' =>
                    $_POST['reason']
            ]);

            header(
                'Location: index.php?page=appointments'
            );
            exit;
        }

        $doctors =
            $this->doctorModel->getAll();

        require __DIR__ .
            '/../views/appointments/create.php';
    }

    public function status()
    {
        Auth::requireRole(
            'admin',
            'doctor'
        );

        $id = (int)$_GET['id'];

        $status = $_GET['status'];

        $this->appointmentModel
            ->updateStatus(
                $id,
                $status
            );

        header(
            'Location: index.php?page=appointments'
        );
        exit;
    }
}