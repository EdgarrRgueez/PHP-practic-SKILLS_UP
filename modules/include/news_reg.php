<?php
    require "../require/config.php";

    // define variables  y dar values vacío ("")
    $Nombre = $correo = $tlf = $calle = $ciudad = $estado = $cp = $news = $formato = $topics = "";

    // Si (llega datos) Entonces
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["Nombre"]) || !empty($_POST["correo"]) || !empty($_POST["tlf"])){
            echo "<br><strong>En los campos requeridos hay datos</strong><br>";

        // Asignar variables
            $Nombre = $_POST["Nombre"];
            $correo = $_POST["correo"];
            $tlf = $_POST["tlf"];
            $calle = $_POST["calle"];
            $ciudad = $_POST["ciudad"];
            $estado = $_POST["estado"];
            $cp = $_POST["cp"];
            $news = $_POST["news"];
            $formato = $_POST["formato"];
            $topics = $_POST["topics"];

        // Declarar la función para limpiar
            function limpiar_dato($data){
                $data = trim($data);  // Limpiar espacios delante y detrás
                $data = stripslashes($data);  // Limpiar 
                $data = htmlspecialchars($data);  // Limpiar caracteres especiales
                return $data;
            }

        // Validación nombre
        function validar_Nombre($Nombre){
            // check if Nombre only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$Nombre)) { 
                return false;
            } else{
                return true;
            }
        }

        // Validación email
        function validateEmail($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                return false;
            } else{
                return true;
            }
        }

        // Validación nº teléfono
        function validar_Nombre($Nombre){
            if (!preg_match("/^[0-9]{10}+$/",$tlf)) { 
                return false;
            } else{
                return true;
            }
        }
        }
    }
?>