<?php
    $connection = mysqli_connect("localhost", "root", "", "todo list");
    if (!$connection) {
        die(mysqli_connect_error());
    }

    session_start();
?>
