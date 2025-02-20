<?php include("../template/cabecera.php"); ?>
<?php

include("../config/baseDB.php");

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";


switch ($accion) {
    case "Agregar":
        // INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, 'Letras y voces con eco', 'imagen.jpg');
        $setenciaSQL = $conexion->prepare("INSERT INTO libros (nombre,imagen) VALUES (:nombre,:imagen)");
        $setenciaSQL->bindParam(':nombre', $txtNombre);
        // agregammo imagenes
        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : "imagen.jpg";
        // imagen temporal 
        $tmpImagen = $_FILES['txtImagen']['tmp_name'];
        if ($tmpImagen != '') {
            move_uploaded_file($tmpImagen, '../../img/' . $nombreArchivo);
        }

        $setenciaSQL->bindParam(':imagen', $nombreArchivo);
        $setenciaSQL->execute();
        break;
    case "Modificar":
        $setenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $setenciaSQL->bindParam(':id', $txtID);
        $setenciaSQL->bindParam(':nombre', $txtNombre);
        $setenciaSQL->execute();
        if ($txtImagen != "") {
            $setenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $setenciaSQL->bindParam(':id', $txtID);
            $setenciaSQL->bindParam(':nombre', $txtImagen);
            $setenciaSQL->execute();
        }
        // actilizar imagen 
        break;
    case "Cancelar":
        // mostra los libros
        echo 'Selecion de cancelar ';
        break;
    case "Borrar":
        $setenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $setenciaSQL->bindParam(':id', $txtID);
        $setenciaSQL->execute();
        // asocia una variable los datos recogido en una a uno y rellenar 
        $Libros = $setenciaSQL->fetch(PDO::FETCH_LAZY);
        if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
            if (file_exists("../../img" . $libro["imagen"])) {
                unlink("../../img" . $libro["imagen"]);
            }
        }

        $setenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
        $setenciaSQL->bindParam(':id', $txtID);
        $setenciaSQL->execute();

        break;
    case "Selecionar":
        $setenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
        $setenciaSQL->bindParam(':id', $txtID);
        $setenciaSQL->execute();
        // asocia una variable los datos recogido en una a uno y rellenar 
        $Libros = $setenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $Libros['nombre'];
        $txtImagen = $Libros['imagen'];
        break;
}
// mostra los libros
$setenciaSQL = $conexion->prepare("SELECT * FROM libros");
$setenciaSQL->execute();
// asocia una variable los datos recogido en una sosiacion 
$lisatLibros = $setenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">

    <form method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                Datos de libro
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="txtID">ID : </label>
                    <input type="txt" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                    <small id="emailHelp" class="form-text text-muted">Referencia unica del libro</small>
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre : </label>
                    <input type="txt" class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen : </label>
                    <?php echo $txtImagen; ?>
                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen de referencia">
                </div>
                <br />

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name='accion' value='Agregar' class="btn btn-success">Agregar</button>
                    <button type="submit" name='accion' value='Modificar' class="btn btn-warning">Modificar</button>
                    <button type="submit" name='accion' value='Cancelar' class="btn btn-info">Cancelar</button>
                </div>
            </div>
        </div>



    </form>



</div>
<div class="col-md-7">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lisatLibros as $libro) {  ?>
                <tr>
                    <td><?php echo $libro['id']; ?></td>
                    <td><?php echo $libro['nombre']; ?></td>
                    <td><?php echo $libro['imagen']; ?></td>

                    <td>

                        <form action="" method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>" />
                            <input type="submit" name="accion" value="Selecionar" class="btn btn-primary" />
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
                        </form>

                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include("../template/footer.php"); ?>