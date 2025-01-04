<?php
$conn = mysqli_connect("localhost", "root", "", "argo-blastcoating");
if (!$conn) {
    die(json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]));
}