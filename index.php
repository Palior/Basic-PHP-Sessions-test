<?php
session_start();
require("database.php");

// Definir la página por defecto y las páginas permitidas
$pages = array("products", "cart");
$_page = isset($_GET['page']) && in_array($_GET['page'], $pages) ? $_GET['page'] : "libreria";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Shopping Cart</title>
</head>
<body>
<div id="container">
    <div id="main">
        <h1>Cart</h1>
        <?php
        // Cargar la página requerida
        require($_page . ".php");

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Construir la consulta SQL usando parámetros
            $ids = array_keys($_SESSION['cart']);
            $sql = "SELECT * FROM libreria WHERE id_product IN (" . implode(',', array_map('intval', $ids)) . ") ORDER BY name ASC";

            // Conectar a la base de datos usando mysqli
            $conn = new mysqli('localhost', 'user', 'password', 'database');

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Ejecutar la consulta
            $query = $conn->query($sql);

            // Verificar si se obtuvieron resultados
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    ?>
                    <p><?php echo htmlspecialchars($row['name']); ?> x <?php echo intval($_SESSION['cart'][$row['id_product']]['quantity']); ?></p>
                    <?php
                }
            } else {
                echo "<p>No hay productos en el carro.</p>";
            }

            // Cerrar la conexión
            $conn->close();
        } else {
            echo "<p>Tu carro está vacío.</p>";
        }
        ?>
        <hr />
        <a href="index.php?page=cart">Ir al carro</a>
    </div>
    <div id="sidebar">
    </div>
</div>
</body>
</html>