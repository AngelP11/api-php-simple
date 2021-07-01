<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class auth extends conexion {
    public function login($json) {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos["username"]) || !isset($datos["password"])) {
            return $_respuestas->error_400();
        } else {
            $user = $datos["username"];
            $password = $datos["password"];
            $password = parent::encrypt($password);

            $data = $this->getUserData($user);

            if ($data) {
                if ($password == $data[0]['password']) {
                    $token = $this->insertToken($data[0]['id']);

                    if ($token) {
                        $result = $_respuestas->response;
                        $result['result'] = array(
                            "token" => $token
                        );

                        return $result;
                    } else {
                        return $_respuestas->error_500("No se pudo guardar el token");
                    }
                } else {
                    return $_respuestas->error_200("La contrasenia es incorrecta.");
                }
            } else {
                return $_respuestas->error_200("El usuario no existe");
            }
        }
    }

    private function getUserData($username) {
        $query = "SELECT id,password,rol FROM usuarios WHERE username = '$username'";
        $response = parent::executeQuery($query);

        if(isset($response[0]['id'])) {
            return $response;
        } else {
            return 0;
        }
    }

    private function insertToken($userId) {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $status = "Activo";

        $query = "INSERT INTO usuarios_token (usuario_id,status,token,date) VALUES ('$userId','$status','$token','$date')";
        $verify = parent::nonQueryId($query);

        if ($verify) {
            return $token;
        } else {
            return 0;
        }
    }

}