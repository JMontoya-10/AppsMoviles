CREATE DATABASE PuellaAgendaDB;
USE PuellaAgendaDB;

CREATE TABLE contacts (
  id_contact int NOT NULL AUTO_INCREMENT,
  contact_name varchar(100) NOT NULL,
  contact_phone varchar(20) NOT NULL
);

INSERT INTO contacts (contact_name, contact_phone) VALUES ('AARON','77341732'),('Carlos','2332'),('Josué','2121333');

