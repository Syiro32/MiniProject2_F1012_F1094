<?php

namespace App\Models;

class EventModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // READ
    public function getAllEvents()
{
    $stmt = $this->conn->prepare(
        "SELECT events.*, users.name AS coordinator
         FROM events
         INNER JOIN users ON events.user_id = users.user_id
         ORDER BY event_date ASC"
    );

    $stmt->execute();
    return $stmt->get_result();
}

    // READ ONE
    public function getEventById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM events WHERE event_id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREATE
    public function createEvent(
        $user_id,
        $event_name,
        $event_date,
        $venue,
        $target_audience,
        $budget,
        $description,
        $status
    ) {
        $stmt = $this->conn->prepare(
            "INSERT INTO events
            (
                user_id,
                event_name,
                event_date,
                venue,
                target_audience,
                budget,
                description,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issssdss",
            $user_id,
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $description,
            $status
        );

        return $stmt->execute();
    }

    // UPDATE
    public function updateEvent(
        $id,
        $event_name,
        $event_date,
        $venue,
        $target_audience,
        $budget,
        $description,
        $status
    ) {
        $stmt = $this->conn->prepare(
            "UPDATE events
             SET
                event_name = ?,
                event_date = ?,
                venue = ?,
                target_audience = ?,
                budget = ?,
                description = ?,
                status = ?
             WHERE event_id = ?"
        );

        $stmt->bind_param(
            "ssssdssi",
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $description,
            $status,
            $id
        );

        return $stmt->execute();
    }

    // DELETE
    public function deleteEvent($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM events WHERE event_id = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

public function eventExists($eventId)
{
    $stmt = $this->conn->prepare(
        "SELECT event_id FROM events WHERE event_id = ?"
    );
    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

public function addParticipant(
    $eventId,
    $studentId,
    $studentName,
    $studentEmail
) {
    $stmt = $this->conn->prepare(
        "INSERT INTO participants
         (event_id, student_id, student_name, student_email)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "isss",
        $eventId,
        $studentId,
        $studentName,
        $studentEmail
    );

    return $stmt->execute();
}

public function getParticipantsByEvent($eventId)
{
    $stmt = $this->conn->prepare(
        "SELECT student_id, student_name, student_email, registered_at
         FROM participants
         WHERE event_id = ?
         ORDER BY student_name ASC"
    );

    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

}