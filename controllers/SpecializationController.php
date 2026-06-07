```php
<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class SpecializationController
{
    private $specializationModel;

    public function __construct()
    {
        Auth::requireRole('admin');

        $this->specializationModel =
            new SpecializationModel();
    }

    public function index()
    {
        $specializations =
            $this->specializationModel
            ->getAll();

        require __DIR__ .
            '/../views/specializations/index.php';
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

            $this->specializationModel
                ->create(
                    trim($_POST['name'])
                );

            header(
                'Location: index.php?page=specializations'
            );
            exit;
        }

        require __DIR__ .
            '/../views/specializations/create.php';
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if (
            $this->specializationModel
            ->isSafeToDelete($id)
        ) {

            $this->specializationModel
                ->delete($id);
        }

        header(
            'Location: index.php?page=specializations'
        );
        exit;
    }
}