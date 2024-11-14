<?php

include '../config/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $confirmar_email = $_POST["confirmar_email"];
    $contraseña = $_POST["contraseña"];
    $confirmar_contraseña = $_POST["confirmar_contraseña"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];

    // Verificar que los correos electrónicos y las contraseñas coinciden
    if ($email != $confirmar_email) {
        die("Los correos electrónicos no coinciden.");
    }

    if ($contraseña != $confirmar_contraseña) {
        die("Las contraseñas no coinciden.");
    }

    // Cifrar la contraseña
    $contraseña_cifrada = password_hash($contraseña, PASSWORD_BCRYPT);

    // Insertar el usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña, dni, telefono) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $email, $contraseña_cifrada, $dni, $telefono);

    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarme</title>
    <link rel="stylesheet" href="../css/estiloregister.css">
</head>
<body>
    <!-- Barra de navegación superior -->
    <header>
        <div class="header-container">
            <div class="logo-container">
                <button class="logo-button">
                    <img src="../imagenes/LogoMapa.png" alt="Logo Mapache Sneakers">
                </button>
                <h1 class="logo"><a href="../index.php">Mapache Sneakers</a></h1>
            </div>
            
            <div class="header-icons">
                <div class="user-icon">
                    <span>👤</span>
                    <div class="user-menu">
                        <a href="sesion.php">Iniciar Sesión</a>
                        <a href="register.php">Registrarme</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenedor del formulario de registro -->
    <div class="register-container">
        <h1>Registrarme</h1>
        <div class="login-tabs">
            <button onclick="window.location.href='sesion.php'">Iniciar Sesión</button>
            <button class="active">Registrarme</button>
        </div>

        <form class="register-form" action="register.php" method="POST">
            <h2>Datos Personales</h2>
            <div class="input-group">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
            </div>
            <div class="input-group">
                <input type="number" name="dni" placeholder="DNI" class="small-input" required>
                <input type="number" name="telefono" placeholder="Teléfono (cod. área + número)" class="medium-input" required>
            </div>

            <h2>Sobre tu Cuenta</h2>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
                <input type="email" name="confirmar_email" placeholder="Confirmar Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="contraseña" placeholder="Contraseña" required>
                <input type="password" name="confirmar_contraseña" placeholder="Confirmar Contraseña" required>
            </div>
            
            <div class="terms">
                <label><input type="checkbox" required> Acepto las <a href="#">Políticas de Privacidad</a> y <a href="#">Términos y Condiciones</a></label>
            </div>
            
            <button type="submit" class="register-button">COMPLETAR REGISTRO</button>
        </form>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
