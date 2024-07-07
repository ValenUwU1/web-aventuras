<?php
session_start();
include("basedatos.php");

// Verificar si el usuario es el dueño de la página
if (empty($_SESSION["Id"]) || $_SESSION["esOwner"] != 1) {
    header("Location: index.php");
    exit();
}
//
if (isset($_POST["id"]) && isset($_POST["darPermisos"]))
{
    $id = $_POST["id"];
    $permiso = 1;
    $sqlQuery = "UPDATE usuario SET esMod = $permiso WHERE id = $id";
    mysqli_query($conn, $sqlQuery);   
?>
    <button class="quitarPermisos"  data=<?=$id?>>Quitar permisos de moderador</button>
<?php
exit();
}
if (isset($_POST["id"]) && isset($_POST["quitarPermisos"])) 
{
    $id = $_POST["id"];
    $permiso = 0;
    $sql = "UPDATE usuario SET esMod=$permiso WHERE id = $id";
    mysqli_query($conn, $sql);
    ?>
        <button class="darPermisos"  data=<?=$id?>> Dar permisos de moderador</button>
    <?php
}
exit();
?>
