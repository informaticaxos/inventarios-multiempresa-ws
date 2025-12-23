<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Models\User;
use PDO;

class UserController {
    private $db;
    private $userRepository;

    public function __construct($db) {
        $this->db = $db;
        $this->userRepository = new UserRepository($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_user) && !empty($data->username_user) && !empty($data->email_user) && !empty($data->password_user)) {
            $user = new User();
            $user->dni_user = $data->dni_user;
            $user->username_user = $data->username_user;
            $user->email_user = $data->email_user;
            $user->password_user = $data->password_user;
            $result = $this->userRepository->create($user);
            if($result['success']) {
                http_response_code(201);
                echo json_encode(array("state" => 1, "message" => "User was created.", "data" => array()));
            } else {
                http_response_code(400);
                echo json_encode(array("state" => 0, "message" => $result['message'], "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to create user. Data is incomplete.", "data" => array()));
        }
    }

    public function read() {
        $stmt = $this->userRepository->read();
        $num = $stmt->rowCount();
        if($num > 0) {
            $users_arr = array();
            $users_arr["records"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    "id_user" => $id_user,
                    "dni_user" => $dni_user,
                    "username_user" => $username_user,
                    "email_user" => $email_user
                );
                array_push($users_arr["records"], $user_item);
            }
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Users found.", "data" => $users_arr["records"]));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "No users found.", "data" => array()));
        }
    }

    public function readOne($id) {
        $user = $this->userRepository->readOne($id);
        if($user) {
            $user_arr = array(
                "id_user" => $user->id_user,
                "dni_user" => $user->dni_user,
                "username_user" => $user->username_user,
                "email_user" => $user->email_user
            );
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "User found.", "data" => array($user_arr)));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "User not found.", "data" => array()));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_user) && !empty($data->username_user) && !empty($data->email_user)) {
            $user = new User();
            $user->id_user = $id;
            $user->dni_user = $data->dni_user;
            $user->username_user = $data->username_user;
            $user->email_user = $data->email_user;
            if($this->userRepository->update($user)) {
                http_response_code(200);
                echo json_encode(array("state" => 1, "message" => "User was updated.", "data" => array()));
            } else {
                http_response_code(503);
                echo json_encode(array("state" => 0, "message" => "Unable to update user.", "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to update user. Data is incomplete.", "data" => array()));
        }
    }

    public function delete($id) {
        if($this->userRepository->delete($id)) {
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "User was deleted.", "data" => array()));
        } else {
            http_response_code(503);
            echo json_encode(array("state" => 0, "message" => "Unable to delete user.", "data" => array()));
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->username) && !empty($data->password)) {
            $id = $this->userRepository->login($data->username, $data->password);
            if($id) {
                http_response_code(200);
                echo json_encode(array("state" => 1, "message" => "Login successful.", "data" => array(array("id_user" => $id))));
            } else {
                http_response_code(401);
                echo json_encode(array("state" => 0, "message" => "Login failed.", "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to login. Data is incomplete.", "data" => array()));
        }
    }
}