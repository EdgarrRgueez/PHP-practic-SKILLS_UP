<?php
require "../modules/require/config.php";
htmlspecialchars($_SERVER['PHP_SELF']);
$_SERVER['REQUEST_METHOD'] == null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/mostrarDatos.css">
    <title>Usuarios registrados</title>
</head>
<body>
    <main>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <button type="submit" name="MostrarInscritos">Mostrar datos</button>
            </form>
        <?php else : ?>
                <?php
                $sql = "SELECT * FROM news_reg";
                $stmt = $conn->prepare($sql);
                $stmt -> execute();

                if ($result = $stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                    echo "<table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Provincia</th>
                            <th>Código postal</th>
                            <th>Noticias a recibir</th>
                            <th>Formato</th>
                            <th>Sugerencias</th>
                        </tr>
                    </thead>";
                    foreach(($rows = $stmt->fetchAll()) as $row){
                        echo "<tr>
                        <td class='borderLeft'>".$row["fullname"]."</td>
                        <td>".$row["email"]."</td>
                        <td>".$row["phone"]."</td>
                        <td>".$row["address"]."</td>
                        <td>".$row["city"]."</td>
                        <td>".$row["state"]."</td>
                        <td class='textCenter'>".$row["zipcode"]."</td>
                        <td>".$row["newsletters"]."</td>
                        <td>".$row["format_news"]."</td>
                        <td class='borderRight'>".$row["suggestion"]."</td>
                        </tr>";
                    }
                echo "</table>";
                } else {
                    echo "<p> 0 results, no found data.</p><br>";
                }
                $conn = null;
                ?>
            <?php endif ?> 
    </main>
</body>
</html>