<?php

namespace App\Models;

class UserModel
{
    private $conn;

    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function registerUser($name, $email, $password)
    {
        // Check whether email already exists
        $check = $this->conn->prepare(
            "SELECT user_id FROM users WHERE email = ?"
        );

        if (!$check) {
            return false;
        }

        $check->bind_param("s", $email);
        $check->execute();

        $check->store_result();

        if ($check->num_rows > 0) {
            $check->close();
            return false;
        }

        $check->close();

        // Hash password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insert user
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, password)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        return $stmt->execute();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT user_id, name, email, password FROM users WHERE email = ?"
        );

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows !== 1) {
            $stmt->close();
            return null;
        }

        $stmt->bind_result($userId, $name, $userEmail, $hashedPassword);
        $stmt->fetch();
        $stmt->close();

        return [
            'user_id' => $userId,
            'name' => $name,
            'email' => $userEmail,
            'password' => $hashedPassword,
        ];
    }
}