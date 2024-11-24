<?php
require_once 'models/User.php';
require_once 'models/Event.php';

#[AllowDynamicProperties]
class DashboardController
{
    public  function __construct()
    {
        $this->user = new User();
        $this->event = new Event();
    }
    public function countUsers(): void
    {
        $totalUsers = $this->user->countAllUsers();
        $totalEvents = $this->event->countAllEvents();
        include 'views/admin/index.php';
    }
}