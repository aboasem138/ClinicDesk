<?php

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    public function findById(int $id): ?array
    {
        $result = $this->execute(
            "SELECT * FROM users WHERE id = ?",
            "i",
            [$id]
        );

        return $result->fetch_assoc() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $result = $this->execute(
            "SELECT * FROM users WHERE email = ?",
            "s",
            [$email]
        );

        return $result->fetch_assoc() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO users
            (
                name,
                email,
                password,
                role,
                phone
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $this->execute(
            $sql,
            "sssss",
            [
                $data['name'],
                $data['email'],
                $data['password'],
                $data['role'],
                $data['phone']
            ]
        );

        return $this->db->lastInsertId();
    }

    public function update(
        int $id,
        array $data
    ): bool
    {
        return $this->execute(
            "UPDATE users
             SET name=?,
                 phone=?,
                 avatar=?
             WHERE id=?",
            "sssi",
            [
                $data['name'],
                $data['phone'],
                $data['avatar'],
                $id
            ]
        );
    }

    public function updatePassword(
        int $id,
        string $newHash
    ): bool
    {
        return $this->execute(
            "UPDATE users
             SET password=?
             WHERE id=?",
            "si",
            [
                $newHash,
                $id
            ]
        );
    }

    public function getAllPaginated(
        int $page,
        string $role = ''
    ): array
    {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        if ($role !== '') {

            $result = $this->execute(
                "SELECT *
                 FROM users
                 WHERE role = ?
                 LIMIT ? OFFSET ?",
                "sii",
                [
                    $role,
                    $limit,
                    $offset
                ]
            );

        } else {

            $result = $this->execute(
                "SELECT *
                 FROM users
                 LIMIT ? OFFSET ?",
                "ii",
                [
                    $limit,
                    $offset
                ]
            );
        }

        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function countAll(
        string $role = ''
    ): int
    {
        if ($role !== '') {

            $result = $this->execute(
                "SELECT COUNT(*) total
                 FROM users
                 WHERE role=?",
                "s",
                [$role]
            );

        } else {

            $result = $this->execute(
                "SELECT COUNT(*) total
                 FROM users"
            );
        }

        $row = $result->fetch_assoc();

        return (int)$row['total'];
    }

    public function toggleActive(
        int $id
    ): bool
    {
        return $this->execute(
            "UPDATE users
             SET is_active =
             CASE
                WHEN is_active = 1 THEN 0
                ELSE 1
             END
             WHERE id=?",
            "i",
            [$id]
        );
    }
    public function getDoctorsOnly(): array
{
    $result = $this->execute(
        "SELECT id,name
         FROM users
         WHERE role='doctor'"
    );

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}
}