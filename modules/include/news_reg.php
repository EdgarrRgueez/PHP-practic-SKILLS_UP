<?php
require "../require/config.php";

// Definir variables  y dar values vacío ("")
$Nombre = $correo = $tlf = $calle = $ciudad = $estado = $cp = $news = $formato = $topics = "";
// Definir e inicializar variables para detectar fallos en las validaciones
$Nombre_err = $correo_err = $tlf_err = false;
$checkNewsletter;

// Declarar la función para limpiar
function limpiar_dato($data){
    $data = trim($data);  // Limpiar espacios delante y detrás
    $data = stripslashes($data);  // Limpiar barras de un string
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
        if (validar_Nombre($Nombre) && validar_Email($correo) && validar_Tlf($tlf)){
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
            
            /* VALIDACIÓN DEL NEWSLETTER PARA MÁS ADELANTE PORQUE NO SE LE PUEDE DAR VALOR NULL */
            $news = filter_input(
                INPUT_POST,
                'news',
                FILTER_SANITIZE_SPECIAL_CHARS,
                FILTER_REQUIRE_ARRAY
            );
            /* USAMOS EL IMPLODE PARA UNIR ELEMENTOS DE UN ARRAY EN UN STRING -> implode(string $separator, array $array) */
            $string = implode(", ",$news);

            $lenArray = count($news);  /* DEVUELVE LA LONGITUD DEL ARRAY count() */
            echo "Antes del switch: " . $lenArray;
            switch ($lenArray) {    /* PARA COMPROBAR QUE VALOR ESTÁ CHECK */
                case 1:
                    if ($news[0] == "HTML"){
                        $checkNewsletter = bindec('100');
                    } elseif($news[0] == "CSS"){
                        $checkNewsletter = bindec('010');
                    } else {
                        $checkNewsletter = bindec('001');
                    }
                    break;
                case 2:
                    if ($news[0] != "HTML") {
                        $checkNewsletter = bindec('011');
                    } elseif ($news[0] != "CSS" && $news[1] == "JS"){
                        $checkNewsletter = bindec('101');
                    } else {
                        $checkNewsletter = bindec('110');
                    }
                    break;
                case 3:
                    $checkNewsletter = bindec('111');
                    break;
                default:
                    $checkNewsletter = bindec('100');
            }

            echo "<br>Valor a devolver del array: " . $checkNewsletter;

            if (isset($_POST["formato"])){
                $formato = limpiar_dato($_POST["formato"]);
            }
            
            if (isset($_POST["topics"])){
                $topics = limpiar_dato($_POST["topics"]);
            } else{
                $topics = NULL;
            }
            // ============================================================= BORRAME
            
            echo "<br><strong>Nombre: </strong>".$Nombre ."<br>";
            echo "<br><strong>Email: </strong>".$correo ."<br>";
            echo "<br><strong>Teléfono: </strong>".$tlf ."<br>";
            echo "<br><strong>Calle: </strong>".$calle ."<br>";
            echo "<br><strong>Ciudad: </strong>".$ciudad ."<br>";
            echo "<br><strong>Estado: </strong>".$estado ."<br>";
            echo "<br><strong>CP: </strong>".$cp ."<br>";
            echo "<br><strong>Noticias (Binario a la BBDD): </strong>".$checkNewsletter ."<br>";
            echo "<br><strong>Formato: </strong>".$formato ."<br>";
            echo "<br><strong>Topics: </strong>".$topics ."<br>";
            
            // ============================================================= BORRAME

            // COMPROBAR QUE NO EXISTEN LOS DATOS QUE SE VAN A ENVIAR, ES DECIR, QUE NO ESTÉN DUPLICADOS: NOMBRE, EMAIL Y TLF
            try{
                $sql = "SELECT * FROM news_reg WHERE fullname = :fullname OR email = :email OR phone = :phone";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':fullname', $Nombre, PDO::PARAM_STR);
                $stmt->bindParam(':email', $correo, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $tlf, PDO::PARAM_STR);

                $stmt->execute();
                $resultado = $stmt->fetchAll();
                echo "Resultado es: " . var_dump($resultado) . "<br>";
                if ($resultado){
                    echo "La información existe<br>";
                } else { 
                    //INSERT DATOS A LA BASE DE DATOS
                    try{
                        $sql = "INSERT INTO news_reg (fullname, email, phone, address, city, state, zipcode, newsletters, format_news, suggestion) VALUES (:fullname, :email, :phone, :address, :city, :state, :zipcode, :newsletters, :format_news, :suggestion)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':fullname', $Nombre, PDO::PARAM_STR);
                        $stmt->bindParam(':email', $correo, PDO::PARAM_STR);
                        $stmt->bindParam(':phone', $tlf, PDO::PARAM_STR);
                        $stmt->bindParam(':address', $calle, PDO::PARAM_STR);
                        $stmt->bindParam(':city', $ciudad, PDO::PARAM_STR);
                        $stmt->bindParam(':state', $estado, PDO::PARAM_STR);
                        $stmt->bindParam(':zipcode', $cp, PDO::PARAM_STR);
                        $stmt->bindParam(':newsletters', $checkNewsletter, PDO::PARAM_INT);
                        $stmt->bindParam(':format_news', $formato, PDO::PARAM_INT);
                        $stmt->bindParam(':suggestion', $topics, PDO::PARAM_STR);

                        $stmt->execute();
                        echo "Nuevo registro creado correctamente.<br>";
                        echo "Valor a ingresado decimal de 3bit: " . $checkNewsletter . "<br>";
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                    }
                    $conn = null;
                }
            } catch (PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
            }

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