<?php
$con = new mysqli("localhost", "root", "", "sourcecodester_hoteldb");

// Check for connection error
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

>?