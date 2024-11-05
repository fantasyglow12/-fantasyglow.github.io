<?php

require 'config/configu.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();
session_start();

$sql = $con->prepare("SELECT ID_Producto, Nombre_Producto, Precio_Venta FROM productos");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantasy Glow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
        <ul class="navbar- nav me-auto mb-2 mb-lg-0">
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
<main>
    <div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach($resultado as $row) { ?>
        <div class="col">
          <div class="card shadow-sm">
            <?php
            $id = $row['ID_Producto'];
            $imagen = "images/productos/$id/princi.jpg";

            if(!file_exists($imagen)){
                $imagen ="images/no-photo.jpg";
            }
            ?>

            <img src="<?php echo $imagen;?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['Nombre_Producto'];?></h5>
              <p class="cart-text">$<?php echo $row['Precio_Venta'];?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a href="details.php?id=<?php echo $row ['ID_Producto'] ?>&token=<?php echo hash_hmac('sha1', $row ['ID_Producto'], KEY_TOKEN);?>" class="btn btn-primary">Detalles</a>
                  
                </div>
                <a href="clases/carrito.php?id=<?php echo $row['ID_Producto']; ?>" class="btn btn-success">Agregar al carrito</a>
              </div>
            </div>
          </div>
        </div>
        <?php }?>
    </div>
    </div>
</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>