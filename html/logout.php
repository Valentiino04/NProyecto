<?php
session_start(); // Inicia la sesión
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión completamente

// Redirige al usuario a la página principal 
header("Location: ../index.php"); 
exit();
?>
