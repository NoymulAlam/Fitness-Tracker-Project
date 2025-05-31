<?php
require_once 'model/formModel.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $age = intval($_POST["age"]);

    $msg = handleFormSubmission($name, $email, $age);
}

include 'view/formView.php';
