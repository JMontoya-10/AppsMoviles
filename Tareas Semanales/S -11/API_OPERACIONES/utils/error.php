<?php
function returnError($message) {
    $response = array("status" => "error", "message" => $message);
    echo json_encode($response);
    exit();
}
?>
