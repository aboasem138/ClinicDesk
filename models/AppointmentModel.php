<?php

require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel
{
    public function book(array $data): bool
    {
        $sql = "
            INSERT INTO appointments
            (
                patient_id,
                doctor_id,
                appt_date,
                appt_time,
                reason,
                status
            )
            VALUES (?, ?, ?, ?, ?, 'pending')
        ";

        return $this->execute(
            $sql,
            "iisss",
            [
                $data['patient_id'],
                $data['doctor_id'],
                $data['appt_date'],
                $data['appt_time'],
                $data['reason']
            ]
        );
    }

    public function hasConflict(
        int $doctorId,
        string $date,
        string $time
    ): bool
    {
        $result = $this->execute(
            "SELECT id
             FROM appointments
             WHERE doctor_id=?
             AND appt_date=?
             AND appt_time=?",
            "iss",
            [
                $doctorId,
                $date,
                $time
            ]
        );

        return $result->num_rows > 0;
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT a.*,
                   p.name AS patient_name,
                   duser.name AS doctor_name
            FROM appointments a
            JOIN users p
                ON a.patient_id = p.id
            JOIN doctors d
                ON a.doctor_id = d.id
            JOIN users duser
                ON d.user_id = duser.id
            WHERE a.id=?
        ";

        $result = $this->execute(
            $sql,
            "i",
            [$id]
        );

        return $result->fetch_assoc() ?: null;
    }

    public function getByPatient(
        int $patientId,
        int $page
    ): array
    {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*,
                   u.name AS doctor_name
            FROM appointments a
            JOIN doctors d
                ON a.doctor_id=d.id
            JOIN users u
                ON d.user_id=u.id
            WHERE a.patient_id=?
            ORDER BY a.appt_date DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute(
            $sql,
            "iii",
            [
                $patientId,
                $limit,
                $offset
            ]
        );

        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getByDoctor(
        int $doctorId,
        int $page
    ): array
    {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*,
                   u.name AS patient_name
            FROM appointments a
            JOIN users u
                ON a.patient_id=u.id
            WHERE a.doctor_id=?
            ORDER BY a.appt_date DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute(
            $sql,
            "iii",
            [
                $doctorId,
                $limit,
                $offset
            ]
        );

        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getAll(int $page): array
    {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*,
                   p.name AS patient_name,
                   duser.name AS doctor_name
            FROM appointments a
            JOIN users p
                ON a.patient_id=p.id
            JOIN doctors d
                ON a.doctor_id=d.id
            JOIN users duser
                ON d.user_id=duser.id
            ORDER BY a.appt_date DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute(
            $sql,
            "ii",
            [
                $limit,
                $offset
            ]
        );

        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function countAll(): int
    {
        $result = $this->execute(
            "SELECT COUNT(*) total
             FROM appointments"
        );

        $row = $result->fetch_assoc();

        return (int)$row['total'];
    }

    public function updateStatus(
        int $id,
        string $status,
        string $notes = ''
    ): bool
    {
        return $this->execute(
            "UPDATE appointments
             SET status=?,
                 doctor_notes=?
             WHERE id=?",
            "ssi",
            [
                $status,
                $notes,
                $id
            ]
        );
    }
    public function countTodayAppointments(): int
{
    $result = $this->execute(
        "SELECT COUNT(*) total
         FROM appointments
         WHERE appt_date = CURDATE()"
    );

    return (int)$result
        ->fetch_assoc()['total'];
}

public function countByStatus(
    string $status
): int
{
    $result = $this->execute(
        "SELECT COUNT(*) total
         FROM appointments
         WHERE status=?",
        "s",
        [$status]
    );

    return (int)$result
        ->fetch_assoc()['total'];
}
}