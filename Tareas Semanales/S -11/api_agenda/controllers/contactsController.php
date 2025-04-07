<?php
    require_once '../config/config.php';
    class ContactsController {
        private $conn;

        public function __construct() {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($this->conn->connect_error) {
                CustomError::throwError("Connection failed: " . $this->conn->connect_error, 500);
            }
        }

        public function getAllContacts() {
            $result = $this->conn->query("SELECT * FROM contacts");
            $contacts = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $contacts[] = $row;
                }
            }

            echo json_encode($contacts);
        }

        public function getContactById($id) {
            $sql = "SELECT * FROM contacts WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                CustomError::throwError("El contacto no existe.", 404);
            }
        }

        public function createContact($name, $phone) {
            if (empty($name) || empty($phone)) {
                CustomError::throwError("Missing parameters.", 400);
            }

            $sql = "INSERT INTO contacts (contact_name, contact_phone) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $name, $phone);

            if ($stmt->execute()) {
                echo json_encode(array("message" => "Contacto creado.", "id" => $this->conn->insert_id));
            } else {
                CustomError::throwError("No se pudo crear el contact.", 500);
            }
        }

        public function updateContact($id, $name, $phone) {
            if (empty($id) || empty($name) || empty($phone)) {
                CustomError::throwError("Missing parameters.", 400);
            }

            $sql = "UPDATE contacts SET contact_name = ?, contact_phone = ? WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $phone, $id);

            if ($stmt->execute()) {
                echo json_encode(array("message" => "Contacto actualizado."));
            } else {
                CustomError::throwError("No se pudo aactualizar el contacto.", 500);
            }
        }

        public function deleteContact($id) {
            if (empty($id)) {
                CustomError::throwError("Missing parameters.", 400);
            }

            $sql = "DELETE FROM contacts WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(array("message" => "Contacto eliminado."));
            } else {
                CustomError::throwError("No se pudo elimianr el contacto.", 500);
            }
        }

        public function __destruct() {
            if ($this->conn) {
                $this->conn->close();
            }
        }
    }
?>
