<?php

namespace App\Controllers;

use App\Repository\CompanyRepository;
use App\Models\Company;
use PDO;

class CompanyController {
    private $db;
    private $companyRepository;

    public function __construct($db) {
        $this->db = $db;
        $this->companyRepository = new CompanyRepository($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_company) && !empty($data->name_company)) {
            $company = new Company();
            $company->dni_company = $data->dni_company;
            $company->name_company = $data->name_company;
            $company->phone_company = $data->phone_company ?? '';
            $company->email_company = $data->email_company ?? '';
            $company->address_company = $data->address_company ?? '';
            $result = $this->companyRepository->create($company);
            if($result['success']) {
                http_response_code(201);
                echo json_encode(array("state" => 1, "message" => "Company was created.", "data" => array()));
            } else {
                http_response_code(400);
                echo json_encode(array("state" => 0, "message" => $result['message'], "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to create company. Data is incomplete.", "data" => array()));
        }
    }

    public function read() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        $result = $this->companyRepository->readPaginated($limit, $offset);
        $companies = $result['companies'];
        $total = $result['total'];
        if(count($companies) > 0) {
            http_response_code(200);
            echo json_encode(array(
                "state" => 1,
                "message" => "Companies found.",
                "data" => $companies,
                "pagination" => array(
                    "page" => $page,
                    "limit" => $limit,
                    "total" => $total,
                    "pages" => ceil($total / $limit)
                )
            ));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "No companies found.", "data" => array()));
        }
    }

    public function readOne($id) {
        $company = $this->companyRepository->readOne($id);
        if($company) {
            $company_arr = array(
                "id_company" => $company->id_company,
                "dni_company" => $company->dni_company,
                "name_company" => $company->name_company,
                "phone_company" => $company->phone_company,
                "email_company" => $company->email_company,
                "address_company" => $company->address_company
            );
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Company found.", "data" => array($company_arr)));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "Company not found.", "data" => array()));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_company) && !empty($data->name_company)) {
            $company = new Company();
            $company->id_company = $id;
            $company->dni_company = $data->dni_company;
            $company->name_company = $data->name_company;
            $company->phone_company = $data->phone_company ?? '';
            $company->email_company = $data->email_company ?? '';
            $company->address_company = $data->address_company ?? '';
            if($this->companyRepository->update($company)) {
                http_response_code(200);
                echo json_encode(array("state" => 1, "message" => "Company was updated.", "data" => array()));
            } else {
                http_response_code(503);
                echo json_encode(array("state" => 0, "message" => "Unable to update company.", "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to update company. Data is incomplete.", "data" => array()));
        }
    }

    public function delete($id) {
        if($this->companyRepository->delete($id)) {
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Company was deleted.", "data" => array()));
        } else {
            http_response_code(503);
            echo json_encode(array("state" => 0, "message" => "Unable to delete company.", "data" => array()));
        }
    }
}