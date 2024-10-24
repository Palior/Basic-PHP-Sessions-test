<h1>View Cart</h1>
<a href="index.php?page=products">Volver a la página de productos</a>
<form method="post" action="index.php?page=cart">
    <table>
        <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Precio objeto</th>
        </tr>
        <?php
        // Asegúrate de que la sesión esté iniciada
        session_start();

        if (isset($_POST['submit'])) {
            foreach ($_POST['quantity'] as $key => $val) {
                // Verifica que la cantidad es un número
                if (is_numeric($val) && $val >= 0) {
                    if ($val == 0) {
                        unset($_SESSION['cart'][$key]);
                    } else {
                        $_SESSION['cart'][$key]['quantity'] = intval($val);
                    }
                }
            }
        }

        // Conectar a la base de datos usando mysqli o PDO
        $conn = new mysqli('localhost', 'user', 'password', 'database');

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM products WHERE id_product IN (";
        $ids = array_keys($_SESSION['cart']);
        $sql .= implode(',', array_map('intval', $ids)) . ") ORDER BY name ASC";
        $query = $conn->query($sql);

        $totalprice = 0;
        while ($row = $query->fetch_assoc()) {
            $subtotal = $_SESSION['cart'][$row['id_product']]['quantity'] * $row['price'];
            $totalprice += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <input type="number" name="quantity[<?php echo $row['id_product']; ?>]" size="5" value="<?php echo $_SESSION['cart'][$row['id_product']]['quantity']; ?>" min="0" />
                </td>
                <td><?php echo htmlspecialchars($row['price']); ?>$</td>
                <td><?php echo htmlspecialchars($_SESSION['cart'][$row['id_product']]['quantity'] * $row['price']); ?>$</td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="4">Total Price: <?php echo htmlspecialchars($totalprice); ?>$</td>
        </tr>
    </table>
    <br />
    <button type="submit" name="submit">Update Cart</button>
</form>
<br />
<p>Para eliminar un item, dejar cantidad en cero (0).</p>

<?php
// Cerrar la conexión
$conn->close();
?>