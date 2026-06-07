<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../core/CSRF.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        Auth::requireRole('admin');
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $page = (int)($_GET['p'] ?? 1);

        $users = $this->userModel->getAllPaginated($page);

        require __DIR__ . '/../views/users/index.php';
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

            $this->userModel->create([
                'name'     => $_POST['name'],
                'email'    => $_POST['email'],
                'password' => password_hash(
                    $_POST['password'],
                    PASSWORD_BCRYPT
                ),
                'role'     => $_POST['role'],
                'phone'    => $_POST['phone']
            ]);

            header('Location: index.php?page=users');
            exit;
        }

        require __DIR__ . '/../views/users/create.php';
    }

    public function edit()
    {
        $id = (int)$_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
    !CSRF::validateToken(
        $_POST['csrf_token'] ?? ''
    )
) {
    die('Invalid CSRF Token');
}
            $this->userModel->update(
                $id,
                [
                    'name'   => $_POST['name'],
                    'phone'  => $_POST['phone'],
                    'avatar' => null
                ]
            );

            header('Location: index.php?page=users');
            exit;
        }

        $user = $this->userModel->findById($id);

        require __DIR__ . '/../views/users/edit.php';
    }

    public function toggle()
    {
        $id = (int)$_GET['id'];

        $this->userModel->toggleActive($id);

        header('Location: index.php?page=users');
        exit;
    }
}