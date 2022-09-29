<?php
    require "../require/config.php";

    // define variables  y dar values vacío ("")
    $Nombre = $correo = $tlf = $calle = $ciudad = $estado = $cp = $news = $formato = $topics = "";

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
        function validar_Email($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                return false;
            } else{
                return true;
            }
        }

        // Validación nº teléfono
        function validar_Tlf($Nombre){
            if (!preg_match("/^[0-9]{10}+$/",$tlf)) { 
                return false;
            } else{
                return true;
            }
        }

    // Si (llega datos) Entonces
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //print_r ($_POST);  // Para ver que están llegando los datos
        if (!empty($_POST["Nombre"]) || !empty($_POST["correo"]) || !empty($_POST["tlf"])){
            echo "<br><strong>En los campos requeridos hay datos</strong><br>";

        // Asignar variables y asignar la función limpiar_dato
            $Nombre = limpiar_dato($_POST["Nombre"]);
            $correo = limpiar_dato($_POST["correo"]);
            $tlf = limpiar_dato($_POST["tlf"]);
            $calle = limpiar_dato($_POST["calle"]);
            $ciudad = limpiar_dato($_POST["ciudad"]);
            $estado = limpiar_dato($_POST["estado"]);
            $cp = limpiar_dato($_POST["cp"]);
            $news = limpiar_dato($_POST["news"]);
            $formato = limpiar_dato($_POST["formato"]);
            $topics = limpiar_dato($_POST["topics"]);

        
        echo "<br><strong>Name: </strong>".$Nombre ."<br>";
        echo "<br><strong>Email: </strong>".$correo ."<br>";
        echo "<br><strong>Teléfono: </strong>".$tlf ."<br>";

        if (validar_Nombre($Nombre)){
            echo "La validación de Name está hecha";
        } else{
            echo "Name no válido";
        }
        // TO DO seguir con los if para comprobar que se validan
        }
    }
?>