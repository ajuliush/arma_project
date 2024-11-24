<?php

use JetBrains\PhpStorm\NoReturn;

session_start(); // Start the session to manage authentication

$method  = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$route   = $request[0] ?? '';

/**
 * Redirect to a specific URL and exit
 */
#[NoReturn]
function redirectTo(string $url): void
{
    header("Location: $url");
    exit();
}

/**
 * Check if the user is authenticated
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

/**
 * Handle unauthenticated access
 */
function requireAuthentication(array $publicRoutes, string $route): void
{
    if (!isAuthenticated() && !in_array($route, $publicRoutes)) {
        redirectTo('/login');
    }
}

// Publicly accessible routes
$publicRoutes = ['login', 'register'];

// Authentication middleware
requireAuthentication($publicRoutes, $route);

// Redirect authenticated users from login or default route
if (isAuthenticated() && ($route === 'login' || $route === 'register')) {
    redirectTo('/');
}

// Routing logic
switch ($route) {
    case '':
    case '/':
        if (isAuthenticated()) {
            if ($_SESSION['role'] === 'user') {
                // include 'views/customer/index.php';
                require_once 'controllers/customer/CustomerController.php';
                $customerController = new CustomerController();
                $customerController->index();
            } elseif ($_SESSION['role'] === 'admin') {
                // include 'views/admin/index.php';
                require_once 'controllers/admin/DashboardController.php';
                $dashboardController = new DashboardController();
                $dashboardController->countUsers();
            }
        } else {
            redirectTo('/login');
        }
        break;

        //user routes start here
    case 'users':
        require_once 'controllers/admin/UserController.php';
        $userController = new UserController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $userController->index();
        } else {
            echo "You are not authorized to access this page";
        }
        break;

    case 'create-user':
        require_once 'controllers/admin/UserController.php';
        $userController = new UserController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $userController->create();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'edit-user':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/UserController.php';
        $userController = new UserController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $userController->edit($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'user-update':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/UserController.php';
        $userController = new UserController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $userController->update($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;

    case 'delete_user':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/UserController.php';
        $userController = new UserController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $userController->delete($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
        //user routes end here
        //event routes start here
    case 'events':
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->index();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'create-event':
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->create();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'event-store':
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->store();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'edit-event':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->edit($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'event-update':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->update($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'delete_event':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->delete($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'get-seat-type':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/EventController.php';
        $eventController = new EventController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $eventController->getSeatType($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
        //event routes end here
        //booking routes start here
    case 'bookings':
        require_once 'controllers/admin/BookingController.php';
        $bookingController = new BookingController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $bookingController->index();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'create-booking':
        require_once 'controllers/admin/BookingController.php';
        $bookingController = new BookingController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $bookingController->create();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'booking-store':
        require_once 'controllers/admin/BookingController.php';
        $bookingController = new BookingController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $bookingController->store();
        } else {
            echo "You are not authorized to access this page";
        }
        break;
    case 'edit-booking':
        $id = $_GET['id'] ?? '';
        require_once 'controllers/admin/BookingController.php';
        $bookingController = new BookingController();
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            $bookingController->edit($id);
        } else {
            echo "You are not authorized to access this page";
        }
        break;
        //booking routes end here
        //customer routes start here    
    case 'store':
        if (isAuthenticated() && ($_SESSION['role'] === 'admin')) {
            require_once 'controllers/admin/UserController.php';
            $userController = new UserController();

            if ($method === 'POST') {
                $userController->store(); // Handle form submission for creating a user
            } else {
                include 'views/admin/create_user.php'; // Display the user creation form
            }
        } else {
            redirectTo('/login'); // Redirect to login if unauthenticated or unauthorized
        }
        break;
        //customer routes end here
        // Default route for register user start here
    case 'register':
        require_once 'controllers/auth/AuthController.php';
        $authController = new AuthController();
        if ($method === 'POST') {
            $authController->register();
        } else {
            include 'views/auth/registration.php';
        }
        break;
        // Default route for register user end here
        // Default route for login user & admin start here
    case 'login':
        require_once 'controllers/auth/AuthController.php';
        $authController = new AuthController();
        if ($method === 'POST') {
            $authController->login();
        } else {
            include 'views/auth/login.php';
        }
        break;
        // Default route for logout user end here
        //user profile routes start here
    case 'profile':
        if (isAuthenticated()) {
            if ($_SESSION['role'] === 'user') {
                // include 'views/customer/profile.php'; 
                require_once 'controllers/customer/CustomerController.php';
                $customerController = new CustomerController();
                $customerController->profile();
            }
        }
        break;
    case 'customer-profile-update':
        if (isAuthenticated()) {
            if ($_SESSION['role'] === 'user') {
                // include 'views/customer/profile.php'; 
                require_once 'controllers/customer/CustomerController.php';
                $userController = new CustomerController();
                $userController->profileUpdate($_SESSION['id']);
            }
        }
        break;
        //user profile routes end here
        // Default route for logout user start here
    case 'logout':
        session_destroy(); // Destroy the session
        redirectTo('/login'); // Redirect to login page
        break;
        // Default route for logout user end here
        // Default route for 404 page start here
    default:
        http_response_code(404);
        echo "Page not found!";
        break;
        // Default route for 404 page end here
}