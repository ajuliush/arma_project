<?php
require_once 'models/Event.php';


#[AllowDynamicProperties]
class EventController
{
    public function __construct()
    {
        $this->event = new Event();
    }
    public function index(): void
    {
        $events = $this->event->getAllEvents();
        // print_r($events);
        include 'views/admin/event/events.php';
    }
    public function create(): void
    {
        include 'views/admin/event/create.php';
    }
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name                    = trim($_POST['name'] ?? '');
            $description             = trim($_POST['description'] ?? '');
            $date                    = trim($_POST['date'] ?? '');
            $time                    = trim($_POST['time'] ?? '');
            $price_with_table        = trim($_POST['price_with_table'] ?? '');
            $price_without_table     = trim($_POST['price_without_table'] ?? '');
            $requires_adult          = trim($_POST['requires_adult'] ?? '');
            $seat_limit              = trim($_POST['seat_limit'] ?? '');
            //validation
            $missingFields = [];
            if (empty($name)) $missingFields[] = 'name';
            if (empty($description)) $missingFields[] = 'description';
            if (empty($date)) $missingFields[] = 'date';
            if (empty($time)) $missingFields[] = 'time';
            if (empty($price_with_table)) $missingFields[] = 'price_with_table';
            if (empty($price_without_table)) $missingFields[] = 'price_without_table';
            if (!isset($_POST['requires_adult'])) $missingFields[] = 'requires_adult';
            if (empty($seat_limit)) $missingFields[] = 'seat_limit';

            if (!empty($missingFields)) {
                echo 'Missing fields: ' . implode(', ', $missingFields);
                return;
            }
            //save event
            $this->event->name = $name;
            $this->event->description = $description;
            $this->event->date = $date;
            $this->event->time = $time;
            $this->event->price_with_table = $price_with_table;
            $this->event->price_without_table = $price_without_table;
            $this->event->requires_adult = $requires_adult;
            $this->event->seat_limit = $seat_limit;
            $this->event->created_at = date('Y-m-d H:i:s');
            $this->event->updated_at = date('Y-m-d H:i:s');
            if ($this->event->save()) {
                session_start();
                $_SESSION['success_message'] = "Event Created successfully!";
                header('Location: /events');
                exit();
            } else {
                echo "Failed to create event!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function edit(int $id): void
    {
        $event = $this->event->getEventById($id);
        if ($event) {
            include 'views/admin/event/edit.php';
        } else {
            // echo "Page not found!";
            http_response_code(404);
            include 'views/components/404.php';
        }
    }
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name                    = trim($_POST['name'] ?? '');
            $description             = trim($_POST['description'] ?? '');
            $date                    = trim($_POST['date'] ?? '');
            $time                    = trim($_POST['time'] ?? '');
            $price_with_table        = trim($_POST['price_with_table'] ?? '');
            $price_without_table     = trim($_POST['price_without_table'] ?? '');
            $requires_adult          = trim($_POST['requires_adult'] ?? '');
            $seat_limit              = trim($_POST['seat_limit'] ?? '');
            //validation
            $missingFields = [];
            if (empty($name)) $missingFields[] = 'name';
            if (empty($description)) $missingFields[] = 'description';
            if (empty($date)) $missingFields[] = 'date';
            if (empty($time)) $missingFields[] = 'time';
            if (empty($price_with_table)) $missingFields[] = 'price_with_table';
            if (empty($price_without_table)) $missingFields[] = 'price_without_table';
            if (!isset($_POST['requires_adult'])) $missingFields[] = 'requires_adult';
            if (empty($seat_limit)) $missingFields[] = 'seat_limit';

            if (!empty($missingFields)) {
                echo 'Missing fields: ' . implode(', ', $missingFields);
                return;
            }
            //update event
            $this->event->id = $id;
            $this->event->name = $name;
            $this->event->description = $description;
            $this->event->date = $date;
            $this->event->time = $time;
            $this->event->price_with_table = $price_with_table;
            $this->event->price_without_table = $price_without_table;
            $this->event->requires_adult = $requires_adult;
            $this->event->seat_limit = $seat_limit;
            $this->event->updated_at = date('Y-m-d H:i:s');
            if ($this->event->update()) {
                session_start();
                $_SESSION['success_message'] = "Event Updated successfully!";
                header('Location: /events');
                exit();
            } else {
                echo "Failed to update event!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function delete(int $id): void
    {
        // Fetch the event to get the profile photo path
        $event = $this->event->getEventById($id);
        // var_dump($event);
        // exit;
        if ($event) {
            // Delete the event from the database
            if ($this->event->delete($id)) { // Assuming you have a delete method
                session_start();
                $_SESSION['error_message'] = "Event Deleted successfully!";
                header('Location: /events');
                exit();
            } else {
                echo "Failed to delete event!";
            }
        } else {
            // echo "Event not found!";
            http_response_code(404);
            include 'views/components/404.php';
        }
    }
    public function getSeatType($id): void
    {
        $seat_types = $this->event->getSeatTypesById($id);
        $data = json_encode($seat_types);
        echo $data;
    }
}