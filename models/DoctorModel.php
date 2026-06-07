```php
<?php

require_once __DIR__ . '/BaseModel.php';

class DoctorModel extends BaseModel
{
    public function findByUserId(int $userId): ?array
    {
        $sql = "
            SELECT d.*,
                   u.name,
                   u.email,
                   u.phone,
                   s.name AS specialization_name
            FROM doctors d
            JOIN users u
                ON d.user_id = u.id
            JOIN specializations s
                ON d.specialization_id = s.id
            WHERE d.user_id = ?
        ";

        $result = $this->execute(
            $sql,
            "i",
            [$userId]
        );

        return $result->fetch_assoc() ?: null;
    }

    public function getAll(): array
    {
        $sql = "
            SELECT d.id,
                   u.name,
                   s.name AS specialization
            FROM doctors d
            JOIN users u
                ON d.user_id = u.id
            JOIN specializations s
                ON d.specialization_id = s.id
            ORDER BY u.name
        ";

        $result = $this->execute($sql);

        $doctors = [];

        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        return $doctors;
    }

    public function getAllPaginated(int $page): array
    {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT d.*,
                   u.name,
                   u.email,
                   s.name AS specialization_name
            FROM doctors d
            JOIN users u
                ON d.user_id = u.id
            JOIN specializations s
                ON d.specialization_id = s.id
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute(
            $sql,
            "ii",
            [$limit, $offset]
        );

        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO doctors
            (
                user_id,
                specialization_id,
                bio,
                consultation_fee,
                available_days,
                photo
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $this->execute(
            $sql,
            "iisdss",
            [
                $data['user_id'],
                $data['specialization_id'],
                $data['bio'],
                $data['consultation_fee'],
                $data['available_days'],
                $data['photo']
            ]
        );

        return $this->db->lastInsertId();
    }

    public function update(
        int $doctorId,
        array $data
    ): bool
    {
        $sql = "
            UPDATE doctors
            SET specialization_id=?,
                bio=?,
                consultation_fee=?,
                available_days=?
            WHERE id=?
        ";

        return $this->execute(
            $sql,
            "isdsi",
            [
                $data['specialization_id'],
                $data['bio'],
                $data['consultation_fee'],
                $data['available_days'],
                $doctorId
            ]
        );
    }

    public function getAvailableDays(
        int $doctorId
    ): array
    {
        $result = $this->execute(
            "SELECT available_days
             FROM doctors
             WHERE id=?",
            "i",
            [$doctorId]
        );

        $row = $result->fetch_assoc();

        if (!$row) {
            return [];
        }

        return explode(
            ",",
            $row['available_days']
        );
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT d.*,
                   u.name,
                   u.email,
                   u.phone,
                   s.name AS specialization_name
            FROM doctors d
            JOIN users u
                ON d.user_id = u.id
            JOIN specializations s
                ON d.specialization_id = s.id
            WHERE d.id = ?
        ";

        $result = $this->execute(
            $sql,
            "i",
            [$id]
        );

        return $result->fetch_assoc() ?: null;
    }
}