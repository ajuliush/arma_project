<?php

require_once 'config/connection.php';

class User
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $phone;
    public string $password;
    public string $role = 'user'; // Default role
    public string $profile_photo;
    public string $created_at;
    public string $updated_at;

    public function save(): bool
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, profile_photo, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param(
            "ssssssss",
            $this->name,
            $this->email,
            $this->phone,
            $this->password,
            $this->role,
            $this->profile_photo,
            $this->created_at,
            $this->updated_at
        );

        $result = $stmt->execute();

        if ($result) {
            $this->id = $conn->insert_id;
        }

        $stmt->close();

        return $result;
    }
    public function update(): bool
    {
        global $conn;

        // Fetch the current user data to get the existing profile photo
        $currentUser = $this->getUserById($this->id); // Assuming you have a method to fetch user by ID
        $oldPhotoPath = $currentUser->profile_photo;

        // Prepare the SQL statement for updating user data
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ?, role = ?, profile_photo = ?, updated_at = ? WHERE id = ?");

        if ($stmt === false) {
            return false;
        }

        // Check if a new photo is uploaded
        if ($this->profile_photo !== $oldPhotoPath) {
            // Delete the old photo if it exists
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Delete the old photo
            }
        }

        // Bind parameters
        $stmt->bind_param(
            "sssssssi",
            $this->name,
            $this->email,
            $this->phone,
            $this->password,
            $this->role,
            $this->profile_photo,
            $this->updated_at,
            $this->id // Bind the user ID for the WHERE clause
        );

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $result;
    }

    // Example method to fetch user by ID
    private function getUserById(int $id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object(); // Assuming you want to return an object

        $stmt->close();

        return $user;
    }

    public function getSingleUser(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user data as an associative array
        $user = $result->fetch_assoc();

        $stmt->close();

        return $user ?: null; // Return the user data or null if not found
    }

    public function findByEmail(string $email): ?array
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        }

        $stmt->close();

        return null;
    }

    public function getAllUsers(): array
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            global $conn;
            $stmt = $conn->prepare("SELECT id, name, email, phone, role, profile_photo , created_at, updated_at FROM users");

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    return $result->fetch_all(MYSQLI_ASSOC);
                }

                return [];
            }

            $stmt->close();
            return [];
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
            global $conn;
            $stmt = $conn->prepare("SELECT id, name, email, phone, role, profile_photo , created_at, updated_at FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['id']);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $users = [];
                    while ($user = $result->fetch_object()) {
                        $users[] = $user; // Collect each user object
                    }
                    return $users; // Return the array of user objects
                }

                return [];
            }

            $stmt->close();
            return [];
        }
    }
    public function countAllUsers(): int
    {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return (int) $row['total'];
            }

            return 0;
        }

        $stmt->close();
        return 0;
    }

    public function delete(int $id): bool
    {
        global $conn;

        // Prepare the SQL statement for deleting the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");

        if ($stmt === false) {
            return false;
        }

        // Bind the user ID for the WHERE clause
        $stmt->bind_param("i", $id);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $result;
    }
}