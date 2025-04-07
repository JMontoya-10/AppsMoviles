<?php
require_once '../config/config.php';
require_once '../utils/error.php';
require_once '../functions/operations.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    $data = $_POST;
}

if (!isset($data['salario']) || !is_numeric($data['salario'])) {
    returnError("El parámetro 'salario' es requerido y debe ser numérico.");
}

$salario = floatval($data['salario']);
$resultado = calcularImpuestos($salario);

$response = [
    "status" => "success",
    "salario" => $salario,
    "result" => $resultado
];

header("Content-Type: application/json");
echo json_encode($response);
?>
