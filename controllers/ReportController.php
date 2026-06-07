```php
<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class ReportController
{
    private $appointmentModel;

    public function __construct()
    {
        Auth::requireRole('admin');

        $this->appointmentModel =
            new AppointmentModel();
    }

    public function index()
    {
        $appointments =
            $this->appointmentModel
            ->getAll(1);

        if (
            isset($_GET['export']) &&
            $_GET['export'] === 'csv'
        ) {

            header(
                'Content-Type: text/csv'
            );

            header(
                'Content-Disposition: attachment; filename="appointments_report.csv"'
            );

            $output =
                fopen(
                    'php://output',
                    'w'
                );

            fputcsv(
                $output,
                [
                    'ID',
                    'Date',
                    'Time',
                    'Status'
                ]
            );

            foreach ($appointments as $row) {

                fputcsv(
                    $output,
                    [
                        $row['id'],
                        $row['appt_date'],
                        $row['appt_time'],
                        $row['status']
                    ]
                );
            }

            fclose($output);
            exit;
        }

        require __DIR__ .
            '/../views/reports/index.php';
    }
}