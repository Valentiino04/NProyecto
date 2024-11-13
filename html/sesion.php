<?php
session_start(); // Esta línea debe ser la primera
include '../config/config.php'; // Conexión a la base de datos

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Consulta SQL para obtener el usuario
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar la contraseña utilizando password_verify
        if (password_verify($contraseña, $usuario["contraseña"])) {
            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION['logged_in'] = true; // Establecer la sesión del usuario
            $_SESSION['is_admin'] = $usuario['is_admin']; // Guardar el rol de admin (si es admin)
            header("Location: ../index.php"); // Redirige a la página principal
            exit();
        } else {
            $error = "Contraseña incorrecta."; // Mensaje de error en caso de que la contraseña no coincida
        }
    } else {
        $error = "No existe una cuenta asociada a este email."; // Mensaje de error si el email no está registrado
    }
    $stmt->close();
    $conexion->close();
}

// Lógica para cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy(); // Destruye todas las sesiones
    header("Location: sesion.php"); // Redirige a la página de inicio de sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/estilosesion.css">
</head>
<body>
<header>
    <div class="header-container">
        <!-- Contenedor del logo y botón -->
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
                    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
                        <!-- Mostrar botón de Cerrar sesión si está autenticado -->
                        <a href="?logout=true">Cerrar sesión</a>
                    <?php else: ?>
                        <!-- Mostrar iniciar sesión y registrarme si no está autenticado -->
                        <a href="html/sesion.php">Iniciar Sesión</a>
                        <a href="html/Register.php">Registrarme</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="search-bar" id="searchBar" style="display: none;">
        <input type="text" placeholder="Buscar...">
    </div>
</header>

<div class="login-container">
    <h1>Iniciar Sesión</h1>
    <div class="login-tabs">
        <button class="active">Iniciar Sesión</button>
        <button onclick="window.location.href='Register.php'">Registrarme</button>
    </div>
    <form class="login-form" action="sesion.php" method="POST">
        <label for="email">* Email</label>
        <input type="email" name="email" id="email" placeholder="Ingresa tu email" required>

        <label for="password">* Contraseña</label>
        <div class="password-container">
            <input type="password" name="contraseña" id="password" placeholder="Ingresa tu contraseña" required>
        </div>

        <div class="login-options">
            <label><input type="checkbox"> Recordarme</label>
            <a href="#">¿Olvidaste tu contraseña?</a>
        </div>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <button type="submit" class="login-button">INICIAR SESIÓN</button>
        <button type="button" class="google-button" onclick="window.location.href='https://accounts.google.com/o/oauth2/auth'">Continuar con Google</button>
    </form>

    <p class="register-link">¿No tenés cuenta? <a href="Register.php">Regístrate.</a></p>
</div>

<script src="../js/script.js"></script>
</body>
</html>
