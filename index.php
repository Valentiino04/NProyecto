<?php
session_start();  // Inicia la sesión

// Verificar si el usuario está intentando cerrar sesión
if (isset($_GET['logout'])) {
    // Eliminar todas las variables de sesión
    session_unset();
    
    // Destruir la sesión
    session_destroy();
    
    // Limpiar el carrito del localStorage (esto se hace desde el lado del cliente)
    echo '<script>localStorage.removeItem("carrito");</script>';

    // Redirigir al usuario a la página de inicio después de cerrar sesión
    header('Location: index.php');  // Cambia "index.php" si necesitas redirigir a otra página
    exit;
}

// Conexión a la base de datos
include 'config/config.php'; 

// Consulta SQL para obtener todos los productos
$sql = "SELECT * FROM products"; 
$result = $conexion->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Crear una estructura de productos para mostrar en el frontend
    $productos = [];
    while($row = $result->fetch_assoc()) {
        $productos[] = $row; // Guardar cada producto en un arreglo
    }
} else {
    $productos = []; // Si no hay productos en la base de datos
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapache Sneakers</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header>
    <div class="header-container">
        <!-- Contenedor del logo y botón -->
        <div class="logo-container">
            <button class="logo-button">
                <img src="imagenes/LogoMapa.png" alt="Logo Mapache Sneakers">
            </button>
            <h1 class="logo"><a href="index.php">Mapache Sneakers</a></h1>
        </div>
        
        <div class="header-icons">
            <!-- Verificamos si el usuario es admin -->
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
               <!-- Si el usuario es admin, mostramos el botón "Gestión" -->
               <a href="productos.php" class="gestion-button">Gestión</a>
            <?php endif; ?>
        </div>
        
        <nav>
           
        </nav>
        
        <div class="header-icons">
            <span class="search-icon">🔍</span>
            
            <span class="cart-icon">🛒</span>
            <!-- Icono de usuario con menú desplegable -->
            <div class="user-icon">
                <span>👤</span>
                <div class="user-menu">
                    <?php
                    // Verificamos si el usuario está autenticado
                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true):
                        // Si el usuario está autenticado, mostrar la opción de Cerrar sesión
                    ?>
                        <a href="?logout=true">Cerrar sesión</a>
                    <?php else: ?>
                        <!-- Si el usuario no está autenticado, mostrar Iniciar sesión y Registrarme -->
                        <a href="html/sesion.php">Iniciar Sesión</a>
                        <a href="html/Register.php">Registrarme</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

    <main>
    <div class="breadcrumb">
        <p>Inicio / Marcas</p>
    </div>

    <div class="content-container">
        <!-- Barra lateral con filtros -->
        <aside class="filters">
            <h2>Filtrar por</h2>

            <!-- Filtro de Género -->
<div class="filter-section">
    <div class="filter-header">
        <span>Género</span>
        <span class="arrow">▸</span>
    </div>
    <ul class="filter-options" style="display: none;">
        <li><input type="checkbox" class="gender-filter" value="mujer"><label for="mujer">Mujer</label></li>
        <li><input type="checkbox" class="gender-filter" value="hombre"><label for="hombre">Hombre</label></li>
        <li><input type="checkbox" class="gender-filter" value="unisex"><label for="unisex">Unisex</label></li>
    </ul>
</div>

<!-- Filtro de Marca -->
<div class="filter-section">
    <div class="filter-header">
        <span>Marca</span>
        <span class="arrow">▸</span>
    </div>
    <ul class="filter-options" style="display: none;">
        <li><input type="checkbox" class="brand-filter" value="nike"><label for="nike">Nike</label></li>
        <li><input type="checkbox" class="brand-filter" value="vans"><label for="vans">Vans</label></li>
        <li><input type="checkbox" class="brand-filter" value="adidas"><label for="adidas">Adidas</label></li>
    </ul>
</div>

<!-- Filtro de Categoría -->
<div class="filter-section">
    <div class="filter-header">
        <span>Categoría</span>
        <span class="arrow">▸</span>
    </div>
    <ul class="filter-options" style="display: none;">
        <li><input type="checkbox" class="category-filter" value="deportiva"><label for="deportiva">Deportiva</label></li>
        <li><input type="checkbox" class="category-filter" value="casual"><label for="casual">Casual</label></li>
        <li><input type="checkbox" class="category-filter" value="futbol"><label for="futbol">Fútbol</label></li>
    </ul>
</div>

<!-- Filtro de Color -->
<div class="filter-section">
    <div class="filter-header">
        <span>Color</span>
        <span class="arrow">▸</span>
    </div>
    <ul class="filter-options" style="display: none;">
        <li><input type="checkbox" class="color-filter" value="rojo"><label for="rojo">Rojo</label></li>
        <li><input type="checkbox" class="color-filter" value="negro"><label for="negro">Negro</label></li>
        <li><input type="checkbox" class="color-filter" value="blanco"><label for="blanco">Blanco</label></li>
        <!-- Agrega más colores si es necesario -->
    </ul>
</div>
        </aside>

        <!-- Productos -->
<section class="products">
    <!-- Producto para Mujer (Nike) - Deportivo -->
    <div class="product-card" data-id="7" data-gender="mujer" data-brand="nike" data-category="deportiva">
        <img src="https://www.dexter.com.ar/on/demandware.static/-/Sites-365-dabra-catalog/default/dw95183db0/products/NIDZ3547-001/NIDZ3547-001-1.JPG" alt="Producto 1">
        <p class="product-name">Zapatillas Training Nike Versair Mujer</p>
        <p class="product-price">$229.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto para Mujer (Nike) - Deportivo -->
    <div class="product-card" data-id="8" data-gender="mujer" data-brand="nike" data-category="deportiva">
        <img src="https://media2.solodeportes.com.ar/media/catalog/product/cache/7c4f9b393f0b8cb75f2b74fe5e9e52aa/z/a/zapatillas-running-nike-run-swift-3-mujer-negra-510010dr2698002-1.jpg" alt="Producto 2">
        <p class="product-name">Zapatillas Running Nike Run Swift 3 Mujer</p>
        <p class="product-price">$129.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto para Hombre (Nike) - Deportivo -->
    <div class="product-card" data-id="9" data-gender="hombre" data-brand="nike" data-category="deportiva">
        <img src="https://www.dexter.com.ar/on/demandware.static/-/Sites-365-dabra-catalog/default/dw93725a01/products/NIDM0829-001/NIDM0829-001-1.JPG" alt="Producto 3">
        <p class="product-name">Zapatillas Training Nike Air Max Alpha Trainer 5 Hombre</p>
        <p class="product-price">$149.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Vans) - Casual -->
    <div class="product-card" data-id="10" data-gender="unisex" data-brand="vans" data-category="casual">
        <img src="https://woker.vtexassets.com/arquivos/ids/474661-800-800?v=638557781258200000&width=800&height=800&aspect=true" alt="Producto 4">
        <p class="product-name">Zapatillas Vans UltraRange EXO</p>
        <p class="product-price">$109.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Vans) - Casual -->
    <div class="product-card" data-id="11" data-gender="unisex" data-brand="vans" data-category="casual">
        <img src="https://http2.mlstatic.com/D_NQ_NP_843502-MLA76820708395_062024-O.webp" alt="Producto 5">
        <p class="product-name">Zapatillas Vans SK8-Hi Reissue</p>
        <p class="product-price">$119.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Vans) - Casual -->
    <div class="product-card" data-id="12" data-gender="unisex" data-brand="vans" data-category="casual">
        <img src="https://http2.mlstatic.com/D_NQ_NP_605693-MLA74808085601_022024-O.webp" alt="Producto 6">
        <p class="product-name">Zapatillas Vans Old Skool</p>
        <p class="product-price">$99.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Adidas) - Deportiva -->
    <div class="product-card" data-id="13" data-gender="unisex" data-brand="adidas" data-category="deportiva">
        <img src="https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/4b593057a18c47d2844dad9000ecd808_9366/Zapatillas_Ultraboost_22_Negro_GX3062_01_standard.jpg" alt="Producto 7">
        <p class="product-name">Zapatillas Adidas Ultraboost 22</p>
        <p class="product-price">$249.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Adidas) - Casual -->
    <div class="product-card" data-id="14" data-gender="unisex" data-brand="adidas" data-category="casual">
        <img src="https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/09c5ea6df1bd4be6baaaac5e003e7047_9366/Zapatillas_Forum_Low_Blanco_FY7756_01_standard.jpg" alt="Producto 8">
        <p class="product-name">Zapatillas Adidas Forum Low</p>
        <p class="product-price">$159.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto Unisex (Adidas) - Deportiva -->
    <div class="product-card" data-id="15" data-gender="unisex" data-brand="adidas" data-category="deportiva">
        <img src="https://assets.adidas.com/images/w_600,f_auto,q_auto/e47a546011fc4057ba4aad7c00f69239_9366/Zapatillas_Adistar_Negro_GX2954_01_standard.jpg" alt="Producto 9">
        <p class="product-name">Zapatillas Adidas Adistar</p>
        <p class="product-price">$179.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto para Fútbol (Botín Morado) -->
    <div class="product-card" data-id="16" data-gender="unisex" data-brand="nike" data-category="futbol">
        <img src="https://www.digitalsport.com.ar/files/products/56ec1b69d0e6b-359096-500x500.jpg" alt="Producto 10">
        <p class="product-name">Botines Nike Hypervenom 3 Morado</p>
        <p class="product-price">$199.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto para Fútbol (Botín Rosa) -->
    <div class="product-card" data-id="17" data-gender="unisex" data-brand="nike" data-category="futbol">
        <img src="https://media2.solodeportes.com.ar/media/catalog/product/cache/7c4f9b393f0b8cb75f2b74fe5e9e52aa/b/o/botines-de-futbol-nike-mercurial-superfly-9-academy-tf-rosa-510010dj5629601-1.jpg" alt="Producto 11">
        <p class="product-name">Botines Nike Hypervenom 3 Rosa</p>
        <p class="product-price">$199.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>

    <!-- Producto para Fútbol (Botín Rojo) -->
    <div class="product-card" data-id="18" data-gender="unisex" data-brand="nike" data-category="futbol">
        <img src="https://media.futbolmania.com/media/catalog/product/cache/1/image/0f330055bc18e2dda592b4a7c3a0ea22/A/T/AT7946-606_imagen-de-las-botas-de-futbol-Nike-Mercurial-Superfly-7-Academy-MG-2020-rojo_1_pie-derecho.jpg" alt="Producto 12">
        <p class="product-name">Botines Nike Mercurial Rojo</p>
        <p class="product-price">$189.99</p>
        <p class="product-shipping">ENVÍO GRATIS</p>
        <button class="add-to-cart">Agregar al carrito</button>
    </div>
     <!-- Productos dinámicos desde la base de datos -->
<!-- Productos dinámicos desde la base de datos -->

</section>

    </div>
</main>

<!-- Contenedor del carrito -->
<div class="cart-container" id="cartContainer">
    <div class="cart-header">
        <h2>Mi Carrito</h2>
        <span class="close-cart" id="closeCart">✖</span>
    </div>
    <div class="cart-content">
        <!-- Lista de productos en el carrito -->
        <ul id="cartItemsList"></ul>

        <!-- Mensaje de carrito vacío -->
        <div class="empty-cart" id="emptyCartMessage" style="display: none;">
            <p>👜</p>
            <p>Tu carrito está vacío.</p>
        </div>

        <!-- Formulario para continuar con la compra -->
        <form id="carritoForm" method="POST" action="html/compras.php">
            <input type="hidden" name="carrito" id="carritoInput">
            <button type="submit" id="continuePurchase" class="continue-button" style="display: none;" disabled>
                Continuar con la compra
            </button>
        </form>

        <!-- Botón para limpiar el carrito -->
        <button id="clearCart" class="clear-cart-button">
            Limpiar Carrito
        </button>
    </div>
</div>

<footer>
    <div class="footer-container">
        <p>© 2024 Mapache Sneakers. Todos los derechos reservados.</p>
        <nav>
            <a href="#">Política de Privacidad</a>
            <a href="#">Términos de Uso</a>
            <a href="#">Contacto</a>
        </nav>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>

