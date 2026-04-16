<?php
$conn = new mysqli("localhost","root","","bikes");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM bikes WHERE id=$id");
echo json_encode($result->fetch_assoc());
