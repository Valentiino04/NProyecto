<?php
// Iniciar sesión para manejar datos de usuario
session_start();

// Conexión a la base de datos
require_once '../config/config.php'; // Asegúrate de que este archivo contiene tu conexión a la base de datos

// Validar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['usuario_id'])) {
        die('Debes iniciar sesión para realizar una compra.');
    }

    // Recoger los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $numero_tarjeta = htmlspecialchars(trim($_POST['numero_tarjeta']));
    $nombre_tarjeta = htmlspecialchars(trim($_POST['nombre_tarjeta']));
    $fecha_vencimiento = htmlspecialchars(trim($_POST['fecha_vencimiento']));
    $cvv = htmlspecialchars(trim($_POST['cvv']));

    // Validar que los datos no estén vacíos
    if (empty($nombre) || empty($email) || empty($direccion) || empty($telefono) || empty($numero_tarjeta) || empty($nombre_tarjeta) || empty($fecha_vencimiento) || empty($cvv)) {
        die('Todos los campos son obligatorios.');
    }

    // Validar formato del correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('El correo electrónico no es válido.');
    }

    // Validar que el número de tarjeta tenga 16 dígitos
    if (!preg_match('/^\d{16}$/', $numero_tarjeta)) {
        die('El número de tarjeta debe tener 16 dígitos.');
    }

    // Validar el CVV
    if (!preg_match('/^\d{3}$/', $cvv)) {
        die('El código CVV debe tener 3 dígitos.');
    }

    // Obtener el carrito del usuario (simulado en cookies o en sesión)
    $carrito = json_decode($_COOKIE['carrito'] ?? '[]', true);

    // Validar que el carrito no esté vacío
    if (empty($carrito)) {
        die('El carrito está vacío. Agrega productos antes de realizar la compra.');
    }

    // Insertar datos en la base de datos
    try {
        $pdo->beginTransaction();

        // Insertar compra
        $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, fecha) VALUES (:usuario_id, NOW())");
        $stmt->execute([':usuario_id' => $_SESSION['usuario_id']]);
        $compra_id = $pdo->lastInsertId();

        // Insertar productos comprados
        $stmt = $pdo->prepare("INSERT INTO productos_comprados (compra_id, producto_id, cantidad, precio) VALUES (:compra_id, :producto_id, :cantidad, :precio)");

        foreach ($carrito as $producto) {
            $stmt->execute([
                ':compra_id' => $compra_id,
                ':producto_id' => $producto['id'],
                ':cantidad' => $producto['quantity'],
                ':precio' => $producto['price']
            ]);
        }

        // Confirmar transacción
        $pdo->commit();

        // Vaciar el carrito
        setcookie('carrito', '', time() - 3600, '/'); // Eliminar cookie
        unset($_SESSION['carrito']); // Si también lo guardas en sesión

        // Redirigir al usuario con mensaje de éxito
        header('Location: ../html/confirmacion.php');
        exit;
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        die('Error al procesar la compra: ' . $e->getMessage());
    }
} else {
    die('Método no permitido.');
}
