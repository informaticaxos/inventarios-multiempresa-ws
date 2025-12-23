<?php

namespace App\Repository;

use PDO;
use App\Models\Company;

class CompanyRepository {
    private $conn;
    private $table_name = "company";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Company $company) {
        // Check if company with this DNI already exists
        $query_check = "SELECT id_company FROM " . $this->table_name . " WHERE dni_company = :dni LIMIT 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":dni", $company->dni_company);
        $stmt_check->execute();
        if ($stmt_check->rowCount() > 0) {
            return ['success' => false, 'message' => 'Company with this DNI already exists.'];
        }

        $query = "INSERT INTO " . $this->table_name . " SET dni_company=:dni, name_company=:name, phone_company=:phone, email_company=:email, address_company=:address";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $company->dni_company);
        $stmt->bindParam(":name", $company->name_company);
        $stmt->bindParam(":phone", $company->phone_company);
        $stmt->bindParam(":email", $company->email_company);
        $stmt->bindParam(":address", $company->address_company);
        if($stmt->execute()) {
            return ['success' => true, 'message' => ''];
        }
        return ['success' => false, 'message' => 'Unable to create company.'];
    }

    public function read() {
        $query = "SELECT id_company, dni_company, name_company, phone_company, email_company, address_company FROM " . $this->table_name . " ORDER BY id_company DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT id_company, dni_company, name_company, phone_company, email_company, address_company FROM " . $this->table_name . " WHERE id_company = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $company = new Company();
            $company->id_company = $row['id_company'];
            $company->dni_company = $row['dni_company'];
            $company->name_company = $row['name_company'];
            $company->phone_company = $row['phone_company'];
            $company->email_company = $row['email_company'];
            $company->address_company = $row['address_company'];
            return $company;
        }
        return null;
    }

    public function update(Company $company) {
        $query = "UPDATE " . $this->table_name . " SET dni_company=:dni, name_company=:name, phone_company=:phone, email_company=:email, address_company=:address WHERE id_company=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $company->dni_company);
        $stmt->bindParam(":name", $company->name_company);
        $stmt->bindParam(":phone", $company->phone_company);
        $stmt->bindParam(":email", $company->email_company);
        $stmt->bindParam(":address", $company->address_company);
        $stmt->bindParam(":id", $company->id_company);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_company = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}