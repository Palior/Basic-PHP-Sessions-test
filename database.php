<?php
// Configuración de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "concesionario";

// Conectar a la base de datos usando mysqli
$mysqli = new mysqli($server, $user, $pass, $db);

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Consulta para obtener productos
$sql = "SELECT nombre, precio FROM productos"; // Asumiendo que hay una tabla llamada 'productos'
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Agrega tu propio CSS si es necesario -->
</head>
<body>

<h1>LISTA PRODUCTOS</h1>
<table>
    <tr>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Agregar</th>
    </tr>

    <?php
    // Verificar si hay resultados y mostrarlos
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>$ " . number_format($row['precio'], 0, ',', '.') . "</td>"; // Formatear el precio
            echo '<td><a href="#">Agregar al carro</a></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No hay productos disponibles</td></tr>";
    }

    // Cerrar la conexión
    $mysqli->close();
    ?>
</table>

</body>
</html>