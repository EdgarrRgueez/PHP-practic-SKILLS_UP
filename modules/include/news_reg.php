<?php
    require "../require/config.php";

    // Definir variables  y dar values vacío ("")
    $Nombre = $correo = $tlf = $calle = $ciudad = $estado = $cp = $news = $formato = $topics = "";
    $Nombre_err = $correo_err = $tlf_err = false;

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
        function validar_Tlf($tlf){
            if (!preg_match("/^[0-9]{9}+$/",$tlf)) { 
                return false;
            } else{
                return true;
            }
        }

// Si (llega datos) Entonces
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r ($_POST);  // Variables requeridas para enviar a BBDD: nombre, correo y tlf.
    if (!empty($_POST["Nombre"]) || !empty($_POST["correo"]) || !empty($_POST["tlf"])){
        echo "<br><strong>En los campos requeridos hay datos</strong><br>";

        // Asignar variables y asignar la función limpiar_dato
        $Nombre = limpiar_dato($_POST["Nombre"]);
        $correo = limpiar_dato($_POST["correo"]);
        $tlf = limpiar_dato($_POST["tlf"]);

        if (validar_Nombre($Nombre)){
            echo "La validación de Name es correcta<br>";
        } else{
            $Nombre_err = true;
        }

        if (validar_Email($correo)){
            echo "La validación de Email es correcta<br>";
        } else{
            $correo_err = true;
        }

        if (validar_Tlf($tlf)){
            echo "La validación de Teléfono es correcta<br>";
        } else{
            $tlf_err = true;
        }

        // Condición si no se cumple alguna validación, parar código
        if (validar_Nombre($Nombre) || validar_Email($correo) || validar_Tlf($tlf)){

            
            // A las variables que pueden ser NULL añadir if para guardar en BBDD como NULL
            if (isset($_POST["calle"])){
                $calle = limpiar_dato($_POST["calle"]);
            } else{
                $calle = NULL;
            }
            
            if (isset($_POST["ciudad"])){
                $ciudad = limpiar_dato($_POST["ciudad"]);
            } else{
                $ciudad = NULL;
            }
            
            if (isset($_POST["estado"])){
                $estado = limpiar_dato($_POST["estado"]);
            } else{
                $estado = NULL;
            }
            
            if (isset($_POST["cp"])){
                $cp = limpiar_dato($_POST["cp"]);
            } else{
                $cp = NULL;
            } 
            
            if (isset($_POST["news"])){
                $news = limpiar_dato($_POST["news"]);
            } else{
                $news = NULL;
            }
            
            /* VALIDACIÓN DEL NEWSLETTER PARA MÁS ADELANTE
            if (isset($_POST["formato"])){
                $formato = limpiar_dato($_POST["formato"]);
            } else{
                $formato = NULL;
            } */
            
            if (isset($_POST["topics"])){
                $topics = limpiar_dato($_POST["topics"]);
            } else{
                $topics = NULL;
            }
            // ============================================================= BORRAME
            
            echo "<br><strong>Name: </strong>".$Nombre ."<br>";
            
            echo "<br><strong>Email: </strong>".$correo ."<br>";
            
            echo "<br><strong>Teléfono: </strong>".$tlf ."<br>";
            
            // ============================================================= BORRAME
        } else{
            if ($Nombre_err == true){
                echo "La validación de Nombre ha fallado<br>";
            } elseif ($correo_err == true) {
                echo "La validación de Email ha fallado<br>";
            } elseif ($tlf_err == true) {
                echo "La validación de Teléfono ha fallado<br>";
            }
        }
    } else {
        echo "Uno de los datos requeridos no ha sido rellenado";
    }
} else{
    echo "No hemos recibido por el métedo POST";
}
    ?>