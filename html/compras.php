<?php
session_start(); // Inicia la sesión del usuario
// Validar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Debes iniciar sesión para continuar con la compra.'); window.location.href = '../html/sesion.php';</script>";
    exit;
}

// Conexión a la base de datos
include '../config/config.php';

// Obtener el carrito desde el formulario POST
$carrito = [];
if (isset($_POST['carrito'])) {
    $carrito = json_decode($_POST['carrito'], true);
} elseif (isset($_COOKIE['carrito'])) {
    $carrito = json_decode($_COOKIE['carrito'], true);
}

// Validar que el carrito no esté vacío
if (empty($carrito)) {
    echo "<script>alert('El carrito está vacío. Agrega productos antes de continuar.'); window.location.href = '../index.php';</script>";
    exit;
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Compra</title>
    <link rel="stylesheet" href="../css/compras.css">
    <script>
        function confirmarCompra() {
            alert('Gracias por su compra');
            // Redirigir al usuario después de confirmar la compra
            window.location.href = "../index.php";
        }
    </script>
</head>
<body>
    <!-- Cabecera -->
    <header>
        <div class="header-container">
            <!-- Contenedor del logo -->
            <div class="logo-container">
                <button class="logo-button">
                    <img src="../imagenes/LogoMapa.png" alt="Logo Mapache Sneakers">
                </button>
                <h1 class="logo"><a href="../index.php">Mapache Sneakers</a></h1>
            </div>

            <!-- Menú de navegación -->
            <nav>
                <a href="#">Categorías</a>
                <a href="#">Mujer</a>
                <a href="#">Hombre</a>
                <a href="#">Kids</a>
                <a href="#">Marcas</a>
                <a href="#">Zapatillas</a>
                <a href="#">Fútbol</a>
                <a href="#">Lanzamiento</a>
            </nav>

            <!-- Iconos de interacción -->
            <div class="header-icons">
                <span>👤</span>
                <div class="user-menu">
                    <a href="../html/logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="container">
        <h1>Finalizar Compra</h1>
        <form method="POST" action="procesar_compra.php">
            <h2>Datos del Comprador</h2>
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
            
            <h2>Datos de Tarjeta</h2>
            <label for="numero_tarjeta">Número de Tarjeta:</label>
            <input type="text" id="numero_tarjeta" name="numero_tarjeta" required>
            
            <label for="nombre_tarjeta">Nombre en la Tarjeta:</label>
            <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" required>
            
            <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
            <input type="month" id="fecha_vencimiento" name="fecha_vencimiento" required>
            
            <label for="cvv">Código CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            
            <h2>Resumen del Carrito</h2>
            <ul>
                <?php foreach ($carrito as $producto): ?>
                    <li>
                        <strong><?= htmlspecialchars($producto['name']) ?></strong>
                        - Cantidad: <?= htmlspecialchars($producto['quantity']) ?>
                        - Precio: $<?= htmlspecialchars(number_format($producto['price'] * $producto['quantity'], 2)) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <button type="submit" onclick="confirmarCompra()">Confirmar Compra</button>
        </form>
    </div>
</body>
</html>
