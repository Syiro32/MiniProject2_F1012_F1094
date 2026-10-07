<?php

namespace App\Controllers;

use App\Models\EventModel;

class EventController
{
    private $eventModel;

    public function __construct($conn)
    {
        $this->eventModel = new EventModel($conn);
    }

    // READ
    public function index()
    {
        require "app/includes/auth_check.php";

        $events = $this->eventModel->getAllEvents();

        require "app/views/events/index.php";
    }

    // CREATE FORM
    public function create()
    {
        require "app/includes/auth_check.php";

        require "app/views/events/create.php";
    }

    // CREATE
    public function store()
    {
        require "app/includes/auth_check.php";

        $event_name = trim($_POST['event_name'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $venue = trim($_POST['venue'] ?? '');
        $target_audience = trim($_POST['target_audience'] ?? '');
        $budget = $_POST['budget'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'Pending';

        $errors = [];

        // Server-side validation
        if (empty($event_name)) {
            $errors[] = "Event name is required.";
        }

        if (empty($event_date)) {
            $errors[] = "Event date is required.";
        }

        if (empty($venue)) {
            $errors[] = "Venue is required.";
        }

        if (empty($target_audience)) {
            $errors[] = "Target audience is required.";
        }

        if (!is_numeric($budget) || $budget < 0) {
            $errors[] = "Budget must be a valid amount.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            header("Location: index.php?action=create");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        $this->eventModel->createEvent(
            $user_id,
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $description,
            $status
        );

        $_SESSION['success'] = "Event added successfully.";

        header("Location: index.php?action=events");
        exit;
    }

    // UPDATE FORM
    public function edit()
    {
        require "app/includes/auth_check.php";

        $id = $_GET['id'] ?? 0;

        if (!is_numeric($id)) {
            die("Invalid event ID.");
        }

        $event = $this->eventModel->getEventById($id);

        if (!$event) {
            die("Event not found.");
        }

        require "app/views/events/edit.php";
    }

    // UPDATE
    public function update()
    {
        require "app/includes/auth_check.php";

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=events");
            exit;
        }

        $id = filter_var($_POST['event_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || !$this->eventModel->eventExists($id)) {
            $_SESSION['error'] = "Event not found.";
            header("Location: index.php?action=events");
            exit;
        }

        $event_name = trim($_POST['event_name'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $venue = trim($_POST['venue'] ?? '');
        $target_audience = trim($_POST['target_audience'] ?? '');
        $budget = $_POST['budget'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'Pending';

        if (
            empty($event_name) ||
            empty($event_date) ||
            empty($venue) ||
            empty($target_audience) ||
            !is_numeric($budget) ||
            $budget < 0 ||
            !in_array($status, ['Pending', 'Approved', 'Rejected'], true)
        ) {
            $_SESSION['error'] = "Please fill in all required fields correctly.";

            header("Location: index.php?action=edit&id=" . $id);
            exit;
        }

        if (!$this->eventModel->updateEvent(
            $id,
            $event_name,
            $event_date,
            $venue,
            $target_audience,
            $budget,
            $description,
            $status
        )) {
            $_SESSION['error'] = "The event could not be updated.";
            header("Location: index.php?action=edit&id=" . $id);
            exit;
        }

        $_SESSION['success'] = "Event updated successfully.";

        header("Location: index.php?action=events");
        exit;
    }

    // DELETE
    public function delete()
    {
        require "app/includes/auth_check.php";

        $id = $_GET['id'] ?? 0;

        if (!is_numeric($id)) {
            die("Invalid event ID.");
        }

        $this->eventModel->deleteEvent($id);

        $_SESSION['success'] = "Event deleted successfully.";

        header("Location: index.php?action=events");
        exit;
    }

    public function uploadForm()
    {
        require "app/includes/auth_check.php";

        $events = $this->eventModel->getAllEvents();
        if (!isset($_SESSION['csv_upload_token'])) {
            $_SESSION['csv_upload_token'] = bin2hex(random_bytes(32));
        }

        require "app/views/events/upload.php";
    }

    public function uploadParticipants()
    {
        require "app/includes/auth_check.php";

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=upload");
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (
            !isset($_SESSION['csv_upload_token']) ||
            !hash_equals($_SESSION['csv_upload_token'], $token)
        ) {
            $_SESSION['error'] = "The upload request expired. Please try again.";
            header("Location: index.php?action=upload");
            exit;
        }

        $eventId = filter_var($_POST['event_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$eventId || !$this->eventModel->eventExists($eventId)) {
            $_SESSION['error'] = "Please select a valid event.";
            header("Location: index.php?action=upload");
            exit;
        }

        if (!isset($_FILES['csv_file'])) {
            $_SESSION['error'] = "Please select a CSV file.";
            header("Location: index.php?action=upload");
            exit;
        }

        $file = $_FILES['csv_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = $file['error'] === UPLOAD_ERR_INI_SIZE ||
                $file['error'] === UPLOAD_ERR_FORM_SIZE
                ? "The CSV file must not exceed 2MB."
                : "The file upload failed. Please try again.";
            header("Location: index.php?action=upload");
            exit;
        }

        $temporaryPath = $file['tmp_name'];
        $actualSize = is_uploaded_file($temporaryPath)
            ? filesize($temporaryPath)
            : false;
        if ($actualSize === false || $actualSize > 2 * 1024 * 1024) {
            $_SESSION['error'] = "The CSV file must be a valid upload no larger than 2MB.";
            header("Location: index.php?action=upload");
            exit;
        }

        if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'csv') {
            $_SESSION['error'] = "Only .csv files are allowed.";
            header("Location: index.php?action=upload");
            exit;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($temporaryPath);
        $allowedMimeTypes = [
            'text/plain',
            'text/csv',
            'application/csv',
            'application/vnd.ms-excel',
            'application/octet-stream'
        ];
        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            $_SESSION['error'] = "The uploaded file is not a valid CSV file.";
            header("Location: index.php?action=upload");
            exit;
        }

        $handle = fopen($temporaryPath, 'r');
        if ($handle === false) {
            $_SESSION['error'] = "The CSV file could not be read.";
            header("Location: index.php?action=upload");
            exit;
        }

        $header = fgetcsv($handle);
        if ($header !== false && isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            $header = array_map(
                static fn($column) => strtolower(trim((string)$column)),
                $header
            );
        }

        if ($header !== ['student_id', 'student_name', 'student_email']) {
            fclose($handle);
            $_SESSION['error'] = "CSV columns must be student_id, student_name, student_email in that order.";
            header("Location: index.php?action=upload");
            exit;
        }

        $success = 0;
        $failed = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null] || $row === []) {
                continue;
            }

            if (count($row) !== 3) {
                $failed++;
                continue;
            }

            [$studentId, $studentName, $studentEmail] = array_map(
                static fn($value) => trim((string)$value),
                $row
            );

            if (
                $studentId === '' ||
                $studentName === '' ||
                !filter_var($studentEmail, FILTER_VALIDATE_EMAIL)
            ) {
                $failed++;
                continue;
            }

            try {
                if ($this->eventModel->addParticipant(
                    $eventId,
                    $studentId,
                    $studentName,
                    $studentEmail
                )) {
                    $success++;
                } else {
                    $failed++;
                }
            } catch (\mysqli_sql_exception $exception) {
                $failed++;
            }
        }

        fclose($handle);
        $_SESSION['upload_result'] = [
            'success' => $success,
            'failed' => $failed
        ];
        header("Location: index.php?action=participants&id=" . (int)$eventId);
        exit;
    }

    public function participants()
    {
        require "app/includes/auth_check.php";

        $eventId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$eventId) {
            $_SESSION['error'] = "Please select a valid event.";
            header("Location: index.php?action=events");
            exit;
        }

        $event = $this->eventModel->getEventById($eventId);
        if (!$event) {
            $_SESSION['error'] = "Event not found.";
            header("Location: index.php?action=events");
            exit;
        }

        $participants = $this->eventModel->getParticipantsByEvent($eventId);
        require "app/views/events/participants.php";
    }
}