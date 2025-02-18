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
        $setenciaSQL->bindParam(':imagen', $txtImagen);
        $setenciaSQL->execute();
        break;
    case "Modificar":
        echo 'Presiono el boton modificare';
        break;
    case "Cancelar":
        echo 'Presiono el boton cancelar';
        break;
    case "Borrar":
        $setenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
        $setenciaSQL->bindParam(':id', $txtID);
        $setenciaSQL->execute();
        break;
    case "Selecionar":
        echo 'Presiono el boton Selecionar';
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
                    <input type="txt" class="form-control" name="txtID" id="txtID" placeholder="ID">
                    <small id="emailHelp" class="form-text text-muted">Referencia unica del libro</small>
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre : </label>
                    <input type="txt" class="form-control" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen : </label>
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