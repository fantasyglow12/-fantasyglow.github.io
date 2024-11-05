<?php
session_start();
require 'config/configu.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();


if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];

    $token_verificado = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_verificado) {
        $sql = $con->prepare("SELECT Nombre_Producto, Precio_Venta, Descripción FROM productos WHERE ID_Producto = ?");
        $sql->execute([$id]);
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $nombre = $resultado['Nombre_Producto'];
            $precio = $resultado['Precio_Venta'];
            $descripcion = $resultado['Descripción'] ?? 'No hay descripción disponible.'; // Valor por defecto si no existe descripción

            $imagen = "images/productos/$id/princi.jpg";
            if (!file_exists($imagen)) {
                $imagen = "images/no-photo.jpg";
            }
        } else {
            echo "Producto no encontrado.";
            exit;
        }
    } else {
        echo "Token no válido.";
        exit;
    }
} else {
    echo "ID o token no especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto - Fantasy Glow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
<?php

$numero_productos = 0;

if (isset($_SESSION['carrito'])) {
    // Sumar la cantidad total de productos en el carrito
    foreach ($_SESSION['carrito'] as $cantidad) {
        $numero_productos += $cantidad;
    }
}
?>
<header data-bs-theme="dark">
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="#" class="navbar-brand">
                <strong>Fantasy Glow</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">Catalogo</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contacto</a>
                    </li>
                </ul>
                <a href="ver_carrito.php" class="btn btn-primary">
    Carrito <span class="badge bg-secondary"><?php echo $numero_productos; ?></span>
</a>
            </div>
        </div>
    </div>
</header>

<main class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                
                <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($nombre); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($nombre); ?></h5>
                    <p class="card-text text-muted">$<?php echo number_format($precio, 2); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($descripcion); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="comprar.php?id=<?php echo $id; ?>" class="btn btn-primary">Comprar ahora</a>
                        <a href="clases/carrito.php?id=<?php echo $id; ?>" class="btn btn-success">Agregar al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>