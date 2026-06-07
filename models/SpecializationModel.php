<?php

require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel
{
    public function getAll(): array
    {
        $result = $this->execute(
            "SELECT * FROM specializations ORDER BY name"
        );

        $items = [];

        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }

    public function create(string $name): bool
    {
        return $this->execute(
            "INSERT INTO specializations (name)
             VALUES (?)",
            "s",
            [$name]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute(
            "DELETE FROM specializations
             WHERE id=?",
            "i",
            [$id]
        );
    }

    public function isSafeToDelete(int $id): bool
    {
        $result = $this->execute(
            "SELECT COUNT(*) total
             FROM doctors
             WHERE specialization_id=?",
            "i",
            [$id]
        );

        $row = $result->fetch_assoc();

        return (int)$row['total'] === 0;
    }
}