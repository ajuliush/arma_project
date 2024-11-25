<?php
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Booking.php';
require_once 'models/Sales.php';

#[AllowDynamicProperties]
class DashboardController
{
    public  function __construct()
    {
        $this->user = new User();
        $this->event = new Event();
        $this->booking = new Booking();
        $this->sales = new Sales();
    }
    public function countUsers(): void
    {
        $totalUsers = $this->user->countAllUsers();
        $totalEvents = $this->event->countAllEvents();
        $totalBooking = $this->booking->countAllBookings();
        $totalRevenue = $this->sales->countAllRevenue();
        include 'views/admin/index.php';
    }
}
