<?php

require_once 'models/User.php';

#[AllowDynamicProperties]
class UserController
{
    public  function __construct()
    {
        $this->user = new User();
    }

    public function index(): void
    {
        $users = $this->user->getAllUsers();
        include 'views/admin/user/users.php';
    }
    public function create(): void
    {
        include 'views/admin/user/create.php';
    }
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name            = trim($_POST['name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $phone           = trim($_POST['phone'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $photo           = $_FILES['photo'] ?? null;

            // Validation
            if (empty($name) || empty($email) || empty($phone) || empty($photo)) {
                echo "All fields are required!";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address!";
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                echo "Invalid phone number!";
                return;
            }

            if (empty($password)) {
                echo "Password is required!";
                return;
            }

            if (empty($confirmPassword)) {
                echo "Confirm password is required!";
                return;
            }

            if ($password !== $confirmPassword) {
                echo "Passwords do not match!";
                return;
            }

            // File Upload
            $uploadDir = 'storage/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uniqueFileName = uniqid('photo_', true) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
            $photoPath      = $uploadDir . $uniqueFileName;

            if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
                echo "Failed to upload photo!";
                return;
            }

            // Save user
            $this->user->name          = $name;
            $this->user->email         = $email;
            $this->user->phone         = $phone;
            $this->user->password      = password_hash($password, PASSWORD_DEFAULT);
            $this->user->role          = 'user'; // Default role
            $this->user->profile_photo = $photoPath;
            $this->user->created_at    = date('Y-m-d H:i:s');
            $this->user->updated_at    = date('Y-m-d H:i:s');

            if ($this->user->save()) {
                session_start();
                $_SESSION['success_message'] = "User Created successfully!";
                header('Location: /users');
                exit();
            } else {
                echo "Failed to register user!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function edit(int $id): void
    {
        $user = $this->user->getSingleUser($id);
        // print_r($user['name']);
        // exit();
        if ($user) {
            include 'views/admin/user/edit.php';
        } else {
            // echo "User not found!";
            http_response_code(404);
            include 'views/components/404.php';
        }
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name            = trim($_POST['name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $phone           = trim($_POST['phone'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $photo           = $_FILES['photo'] ?? null;

            // Validation
            if (empty($name) || empty($email) || empty($phone)) {
                echo "All fields are required!";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address!";
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                echo "Invalid phone number!";
                return;
            }

            // Password validation (optional)
            if (!empty($password) && $password !== $confirmPassword) {
                echo "Passwords do not match!";
                return;
            }

            // File Upload (if a new photo is uploaded)
            $uploadDir = 'storage/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid('photo_', true) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photoPath      = $uploadDir . $uniqueFileName;

                if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
                    echo "Failed to upload photo!";
                    return;
                }
            } else {
                // If no new photo, keep the existing one
                $photoPath = $this->user->profile_photo; // Assuming you fetch the existing user first
            }

            // Update user
            $this->user->id             = $id; // Assuming you have an ID property
            $this->user->name           = $name;
            $this->user->email          = $email;
            $this->user->phone          = $phone;
            if (!empty($password)) {
                $this->user->password     = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->user->profile_photo   = $photoPath;
            $this->user->updated_at     = date('Y-m-d H:i:s');

            if ($this->user->update()) { // Assuming you have an update method
                session_start();
                $_SESSION['success_message'] = "User Updated successfully!";
                header('Location: /users');
                exit();
            } else {
                echo "Failed to update user!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function delete(int $id): void
    {
        // Fetch the user to get the profile photo path
        $user = $this->user->getSingleUser($id);

        if ($user) {
            // Delete the user from the database
            if ($this->user->delete($id)) { // Assuming you have a delete method
                // Remove the user's profile photo from the server
                if (file_exists($user['profile_photo'])) {
                    unlink($user['profile_photo']);
                }
                session_start();
                $_SESSION['error_message'] = "User Deleted successfully!";
                header('Location: /users');
                exit();
            } else {
                echo "Failed to delete user!";
            }
        } else {
            // echo "User not found!";
            http_response_code(404);
            include 'views/components/404.php';
        }
    }
}