<?php
    require_once '../config/config.php';
    require_once '../utils/error.php';
    
    class ContactsModel {
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
            
            return $contacts;
        }
        
        public function getContactById($id) {
            $sql = "SELECT * FROM contacts WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }
        
        public function searchContacts($term) {
            $term = "%$term%";
            $sql = "SELECT * FROM contacts WHERE contact_name LIKE ? OR contact_phone LIKE ? OR id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            
            $id = is_numeric($term) ? trim($term, '%') : 0;
            
            $stmt->bind_param("ssi", $term, $term, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $contacts = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $contacts[] = $row;
                }
            }
            
            return $contacts;
        }
        
        public function createContact($name, $phone) {
            $sql = "INSERT INTO contacts (contact_name, contact_phone) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $name, $phone);
            
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            } else {
                return false;
            }
        }
        
        public function updateContact($id, $name, $phone) {
            $sql = "UPDATE contacts SET contact_name = ?, contact_phone = ? WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $phone, $id);
            
            return $stmt->execute() && $stmt->affected_rows > 0;
        }
        
        public function deleteContact($id) {
            $sql = "DELETE FROM contacts WHERE id_contact = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            return $stmt->execute() && $stmt->affected_rows > 0;
        }
        
        public function __destruct() {
            if ($this->conn) {
                $this->conn->close();
            }
        }
    }
?>
