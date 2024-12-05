<?php

require_once 'models/User.php';

#[AllowDynamicProperties]
class AuthController
{
    public function __construct()
    {
        $this->user = new User();
    }

    public function register(): void
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
                $_SESSION['error'] = "All fields are required!";
                header('Location: /register');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email address!";
                header('Location: /register');
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $_SESSION['error'] = "Invalid phone number!";
                header('Location: /register');
                return;
            }

            if (empty($password)) {
                $_SESSION['error'] = "Password is required!";
                header('Location: /register');
                return;
            }

            if (empty($confirmPassword)) {
                $_SESSION['error'] = "Confirm password is required!";
                header('Location: /register');
                return;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match!";
                header('Location: /register');
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
                $_SESSION['error'] = "Failed to upload photo!";
                header('Location: /register');
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
                $_SESSION['success_message'] = "Registration successful! Please login.";
                header('Location: /login');
                exit();
            } else {
                $_SESSION['error'] = "Failed to register user!";
                header('Location: /register');
                return;
            }
        } else {
            http_response_code(405);
            $_SESSION['error'] = "Method Not Allowed";
            header('Location: /register');
            return;
        }
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // session_start();
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email and Password are required!";
                header('Location: /login');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email address!";
                header('Location: /login');
                return;
            }

            $user = $this->user->findByEmail($email);

            if (!$user) {
                $_SESSION['error'] = "Email not found!";
                header('Location: /login');
                return;
            }

            if (!password_verify($password, $user['password'])) {
                $_SESSION['error'] = "Wrong password!";
                header('Location: /login');
                return;
            }

            // Authenticate user
            $_SESSION['authenticated'] = true;
            $_SESSION['id']           = $user['id'];
            $_SESSION['name']         = $user['name'];
            $_SESSION['email']        = $email;
            $_SESSION['role']         = $user['role'];

            $_SESSION['success_message'] = "Login successful!.";
            header('Location: /');
            exit();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
}