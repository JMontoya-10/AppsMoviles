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
            } else if (isset($_GET['search'])) {
                $term = $_GET['search'];
                $contactsController->searchContacts($term);
            } else {
                $contactsController->getAllContacts();
            }
            break;
        case 'POST':
            if (!isset($_POST['contact_name']) || !isset($_POST['contact_phone'])) {
                CustomError::throwError("Nombre y teléfono son requeridos.", 400);
            }
            $name = $_POST['contact_name'];
            $phone = $_POST['contact_phone'];
            $contactsController->createContact($name, $phone);
            break;
        case 'PUT':
            parse_str(file_get_contents("php://input"), $_PUT);
            if (!isset($_PUT['id_contact']) || !isset($_PUT['contact_name']) || !isset($_PUT['contact_phone'])) {
                CustomError::throwError("ID, nombre y teléfono son requeridos para actualizar.", 400);
            }
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
                CustomError::throwError("ID de contacto es requerido para eliminar.", 400);
            }
            break;
        default:
            CustomError::throwError("Método inválido.", 405);
            break;
    }
?>
