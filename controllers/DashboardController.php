```php
<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class DashboardController
{
    private $dashboardModel;
    private $appointmentModel;

    public function __construct()
    {
        $this->dashboardModel =
            new DashboardModel();

        $this->appointmentModel =
            new AppointmentModel();
    }

    public function index()
    {
        Auth::requireRole(
            'admin',
            'doctor',
            'patient'
        );

        $role = Auth::role();

        if ($role === 'admin') {

            $stats =
                $this->dashboardModel
                ->userStats();

            $todayAppointments =
                $this->appointmentModel
                ->countTodayAppointments();

            require_once __DIR__ .
                '/../views/dashboard/admin.php';

        } elseif ($role === 'doctor') {

            require_once __DIR__ .
                '/../views/dashboard/doctor.php';

        } else {

            require_once __DIR__ .
                '/../views/dashboard/patient.php';
        }
    }
}