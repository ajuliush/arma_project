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

            if (empty($name) || empty($email) || empty($phone) || empty($photo)) {
                $_SESSION['error'] = "All fields are required!";
                header('Location: /create-user');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_email'] = "Invalid email address!";
                header('Location: /create-user');
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $_SESSION['error_phone'] = "Invalid phone number!";
                header('Location: /create-user');
                return;
            }
            if (empty($photo)) {
                $_SESSION['error'] = "Image is required!";
                header('Location: /create-user');
                return;
            }
            if (empty($password)) {
                $_SESSION['error'] = "Password is required!";
                header('Location: /create-user');
                return;
            }

            if (empty($confirmPassword)) {
                $_SESSION['error_password'] = "Confirm password is required!";
                header('Location: /create-user');
                return;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error_password_confirm'] = "Passwords do not match!";
                header('Location: /create-user');
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
                // echo "Failed to upload photo!";
                $_SESSION['error_image'] = "Failed to upload photo!";
                header('Location: /create-user');
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
            // Fetch existing user data first
            $existingUser = $this->user->getSingleUser($id);
            if (!$existingUser) {
                $_SESSION['error'] = "User not found!";
                header('Location: /users');
                return;
            }

            $name            = trim($_POST['name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $phone           = trim($_POST['phone'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $photo           = $_FILES['photo'] ?? null;

            // Validation
            if (empty($name) || empty($email) || empty($phone)) {
                $_SESSION['error'] = "All fields are required!";
                header('Location: /edit-user?id=' . $id);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // echo "Invalid email address!";
                $_SESSION['error_email'] = "Invalid email address!";
                header('Location: /edit-user?id=' . $id);
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                // echo "Invalid phone number!";
                $_SESSION['error_phone'] = "Invalid phone number!";
                header('Location: /edit-user?id=' . $id);
                return;
            }

            // Password validation (optional)
            if (!empty($password) && $password !== $confirmPassword) {
                // echo "Passwords do not match!";
                $_SESSION['error_password'] = "Passwords do not match!";
                header('Location: /edit-user?id=' . $id);
                return;
            }

            // File Upload (if a new photo is uploaded)
            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'storage/photos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uniqueFileName = uniqid('photo_', true) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photoPath      = $uploadDir . $uniqueFileName;

                if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
                    $_SESSION['error_image'] = "Failed to upload photo!";
                    header('Location: /edit-user?id=' . $id);
                    return;
                }
            } else {
                // If no new photo, use the existing photo path
                $photoPath = $existingUser['profile_photo'];
            }

            // Update user
            $this->user->id            = $id;
            $this->user->name          = $name;
            $this->user->email         = $email;
            $this->user->phone         = $phone;
            $this->user->profile_photo = $photoPath;
            // Handle password - if new password provided, use it; otherwise use existing password
            $this->user->password = !empty($password)
                ? password_hash($password, PASSWORD_DEFAULT)
                : $existingUser['password'];
            $this->user->updated_at = date('Y-m-d H:i:s');

            if ($this->user->update()) {
                session_start();
                $_SESSION['success_message'] = "User Updated successfully!";
                header('Location: /users');
                exit();
            } else {
                // echo "Failed to update user!";
                $_SESSION['error'] = "Failed to update user!";
                header('Location: /edit-user?id=' . $id);
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
