<?php
require_once '../config/config.php';
require_once '../utils/error.php';
require_once '../functions/operations.php';

$action = $_GET['action'] ?? '';
$num1 = $_GET['num1'] ?? '';
$num2 = $_GET['num2'] ?? '';

if (empty($action) || empty($num1) || empty($num2)) {
    returnError("Missing parameters.");
}

if (!is_numeric($num1) || !is_numeric($num2)) {
    returnError("Invalid numbers.");
}

$num1 = floatval($num1);
$num2 = floatval($num2);

$result = 0;

switch (strtolower($action)) {
    case 'add':
        $result = add($num1, $num2);
        break;
    case 'subtract':
        $result = subtract($num1, $num2);
        break;
    case 'multiply':
        $result = multiply($num1, $num2);
        break;
    case 'divide':
        $result = divide($num1, $num2);
        break;
    default:
        returnError("Invalid action.");
}

$response = array(
    "status" => "success",
    "action" => $action,
    "num1" => $num1,
    "num2" => $num2,
    "result" => $result
);

echo json_encode($response);
?>
