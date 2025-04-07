<?php
    require_once '../controllers/contactsController.php';
    require_once '../utils/error.php';

    $contactsController = new ContactsController();

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $contactsController->getContactById($id);
            } else {
                $contactsController->getAllContacts();
            }
            break;
        case 'POST':
            $name = $_POST['contact_name'];
            $phone = $_POST['contact_phone'];
            $contactsController->createContact($name, $phone);
            break;
        case 'PUT':
            parse_str(file_get_contents("php://input"), $_PUT);
            $id = $_PUT['id_contact'];
            $name = $_PUT['contact_name'];
            $phone = $_PUT['contact_phone'];
            $contactsController->updateContact($id, $name, $phone);
            break;
        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $contactsController->deleteContact($id);
            } else {
                CustomError::throwError("Contact ID is required for deletion.", 400);
            }
            break;
        default:
            CustomError::throwError("Invalid method.", 405);
            break;
    }
?>
