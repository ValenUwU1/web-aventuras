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
    $sql="SELECT * FROM usuario";
    $resultado=mysqli_query($conn,$sql);
    $contador=0;
    $bool=false;
    while($row=mysqli_fetch_assoc($resultado)){
        if($row["esMod"]){
            $contador++;
        }
    }
    if($contador<6){
    $bool=true;
    $id = $_POST["id"];
    $permiso = 1;
    $sqlQuery = "UPDATE usuario SET esMod = $permiso WHERE id = $id";
    mysqli_query($conn, $sqlQuery);   
?>
    <button class="quitarPermisos"  data=<?=$id?>>Quitar permisos de moderador</button>
<?php
    }
    echo json_encode($bool);
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
