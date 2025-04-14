<?php
    require_once '../models/contactsModel.php';
    require_once '../utils/error.php';
    require_once '../utils/validations.php';
    
    class ContactsController {
        private $model;

        public function __construct() {
            $this->model = new ContactsModel();
        }

        public function getAllContacts() {
            $contacts = $this->model->getAllContacts();
            echo json_encode($contacts);
        }

        public function getContactById($id) {
            if (!Validator::validateId($id)) {
                CustomError::throwError("ID de contacto inválido.", 400);
            }
            
            $contact = $this->model->getContactById($id);
            
            if ($contact) {
                echo json_encode($contact);
            } else {
                CustomError::throwError("El contacto no existe.", 404);
            }
        }
        
        public function searchContacts($term) {
            if (!Validator::validateSearchTerm($term)) {
                CustomError::throwError("Término de búsqueda inválido.", 400);
            }
            
            $contacts = $this->model->searchContacts($term);
            echo json_encode($contacts);
        }

        public function createContact($name, $phone) {
            if (!Validator::validateContactName($name)) {
                CustomError::throwError("Nombre de contacto inválido.", 400);
            }
            
            if (!Validator::validatePhone($phone)) {
                CustomError::throwError("Número de teléfono inválido.", 400);
            }

            $insertId = $this->model->createContact($name, $phone);
            
            if ($insertId) {
                echo json_encode(array("message" => "Contacto creado.", "id" => $insertId));
            } else {
                CustomError::throwError("No se pudo crear el contacto.", 500);
            }
        }

        public function updateContact($id, $name, $phone) {
            if (!Validator::validateId($id)) {
                CustomError::throwError("ID de contacto inválido.", 400);
            }
            
            if (!Validator::validateContactName($name)) {
                CustomError::throwError("Nombre de contacto inválido.", 400);
            }
            
            if (!Validator::validatePhone($phone)) {
                CustomError::throwError("Número de teléfono inválido.", 400);
            }

            if ($this->model->updateContact($id, $name, $phone)) {
                echo json_encode(array("message" => "Contacto actualizado."));
            } else {
                CustomError::throwError("No se pudo actualizar el contacto.", 500);
            }
        }

        public function deleteContact($id) {
            if (!Validator::validateId($id)) {
                CustomError::throwError("ID de contacto inválido.", 400);
            }

            if ($this->model->deleteContact($id)) {
                echo json_encode(array("message" => "Contacto eliminado."));
            } else {
                CustomError::throwError("No se pudo eliminar el contacto.", 500);
            }
        }
    }
?>
