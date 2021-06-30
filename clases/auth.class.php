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
                if ($password == $data['password']) {

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

}