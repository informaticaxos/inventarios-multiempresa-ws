<?php

namespace App\Controllers;

use App\Models\User;

class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_user) && !empty($data->username_user) && !empty($data->email_user) && !empty($data->password_user)) {
            $this->user->dni_user = $data->dni_user;
            $this->user->username_user = $data->username_user;
            $this->user->email_user = $data->email_user;
            $this->user->password_user = $data->password_user;
            if($this->user->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "User was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
    }

    public function read() {
        $stmt = $this->user->read();
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
            echo json_encode($users_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No users found."));
        }
    }

    public function readOne($id) {
        $this->user->id_user = $id;
        $this->user->readOne();
        if($this->user->username_user != null) {
            $user_arr = array(
                "id_user" => $this->user->id_user,
                "dni_user" => $this->user->dni_user,
                "username_user" => $this->user->username_user,
                "email_user" => $this->user->email_user
            );
            http_response_code(200);
            echo json_encode($user_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "User not found."));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->dni_user) && !empty($data->username_user) && !empty($data->email_user)) {
            $this->user->id_user = $id;
            $this->user->dni_user = $data->dni_user;
            $this->user->username_user = $data->username_user;
            $this->user->email_user = $data->email_user;
            if($this->user->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "User was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update user. Data is incomplete."));
        }
    }

    public function delete($id) {
        $this->user->id_user = $id;
        if($this->user->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "User was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete user."));
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->username) && !empty($data->password)) {
            $id = $this->user->login($data->username, $data->password);
            if($id) {
                http_response_code(200);
                echo json_encode(array("message" => "Login successful.", "id_user" => $id));
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Login failed."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to login. Data is incomplete."));
        }
    }
}