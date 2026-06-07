<?php

require_once __DIR__ . '/BaseModel.php';

class DashboardModel extends BaseModel
{
    public function userStats()
    {
        $result = $this->execute(
            "SELECT role,
                    COUNT(*) total
             FROM users
             GROUP BY role"
        );

        $rows = [];

        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
}