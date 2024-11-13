<?php 
include '../config/config.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapache Sneakers - Admin CRUD</title>
    <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
    <header>
       
    </header>

    <main>
        <h1>Gestión de Productos - Mapache Sneakers</h1>

        <!-- Formulario para Agregar Zapatillas -->
        <section class="product-form">
            <h2>Agregar Nueva Zapatilla</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="name">Nombre:</label>
                <input type="text" name="name" required><br>

                <label for="price">Precio:</label>
                <input type="number" name="price" step="0.01" required><br>

                <label for="shipping_info">Información de Envío:</label>
                <input type="text" name="shipping_info"><br>

                <label for="image_url">URL de Imagen:</label>
                <input type="text" name="image_url" required><br>

                <label for="category">Categoría:</label>
                <input type="text" name="category"><br>

                <label for="brand">Marca:</label>
                <input type="text" name="brand"><br>

                <label for="gender">Género:</label>
                <input type="text" name="gender"><br>

                <button type="submit" name="submit">Agregar Zapatilla</button>
            </form>

            <?php
            // Insertar nueva zapatilla
            if (isset($_POST['submit'])) {
                $name = $_POST['name'];
                $price = $_POST['price'];
                $shipping_info = $_POST['shipping_info'];
                $image_url = $_POST['image_url'];
                $category = $_POST['category'];
                $brand = $_POST['brand'];
                $gender = $_POST['gender'];

                $sql = "INSERT INTO products (name, price, shipping_info, image_url, category, brand, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sdsssss", $name, $price, $shipping_info, $image_url, $category, $brand, $gender);
                $stmt->execute();

                echo "<p>Producto agregado exitosamente!</p>";
            }
            ?>
        </section>

        <!-- Mostrar productos en una tabla -->
        <section class="product-table">
            <h2>Lista de Zapatillas</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Marca</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>

                <?php
                // Mostrar productos
                $sql = "SELECT * FROM products";
                $result = $conexion->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo "<td>" . $row['brand'] . "</td>";
                    echo "<td><img src='" . $row['image_url'] . "' width='100'></td>";
                    echo "<td>";
                    echo "<a href='?edit=" . $row['id'] . "'>Editar</a> | ";
                    echo "<a href='?delete=" . $row['id'] . "'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </section>

        <?php
        // Eliminar producto
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            echo "<p>Producto eliminado exitosamente!</p>";
            header("Location: " . $_SERVER['PHP_SELF']); // Recarga la página
            exit();
        }

        // Mostrar formulario para editar si se selecciona "Editar"
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                ?>
                <section class="product-edit-form">
                    <h2>Editar Zapatilla</h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                        <label for="name">Nombre:</label>
                        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>

                        <label for="price">Precio:</label>
                        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required><br>

                        <label for="shipping_info">Información de Envío:</label>
                        <input type="text" name="shipping_info" value="<?php echo $product['shipping_info']; ?>"><br>

                        <label for="image_url">URL de Imagen:</label>
                        <input type="text" name="image_url" value="<?php echo $product['image_url']; ?>" required><br>

                        <label for="category">Categoría:</label>
                        <input type="text" name="category" value="<?php echo $product['category']; ?>"><br>

                        <label for="brand">Marca:</label>
                        <input type="text" name="brand" value="<?php echo $product['brand']; ?>"><br>

                        <label for="gender">Género:</label>
                        <input type="text" name="gender" value="<?php echo $product['gender']; ?>"><br>

                        <button type="submit" name="update">Guardar Cambios</button>
                    </form>
                </section>
                <?php
            }
        }

        // Actualizar producto
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $shipping_info = $_POST['shipping_info'];
            $image_url = $_POST['image_url'];
            $category = $_POST['category'];
            $brand = $_POST['brand'];
            $gender = $_POST['gender'];

            $sql = "UPDATE products SET name = ?, price = ?, shipping_info = ?, image_url = ?, category = ?, brand = ?, gender = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sdsssssi", $name, $price, $shipping_info, $image_url, $category, $brand, $gender, $id);
            $stmt->execute();

            echo "<p>Producto actualizado correctamente!</p>";
            header("Location: " . $_SERVER['PHP_SELF']); // Recarga la página
            exit();
        }
        ?>
    </main>

    <footer>
        <!-- Tu código de footer aquí -->
    </footer>
</body>
</html>
