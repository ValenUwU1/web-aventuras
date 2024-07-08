<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
include("basedatos.php");
include("header.php");
include("BarraNavegacion.php");
// Verificar si el usuario es el dueño de la página
if (empty($_SESSION["Id"]) || $_SESSION["esOwner"] != 1) {
    header("Location: index.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM usuario");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Usuarios</title>
</head>
<div style="margin-left:15%;padding:20px;width: auto;height:100%;background-color:whitesmoke;overflow-y: auto;">
    <h1>Lista de Usuarios</h1>
    <table border="1">
        <tr>
            <th>Nombre de Usuario</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row["NombreUsuario"]; ?></td>
            <td>
                <?php 
                if(!$row["esOwner"]){if ($row["esMod"] == 1): ?>
                    <button class="quitarPermisos" data=<?=$row['id']?>>Quitar permisos de moderador</button>
                <?php else: ?>
                    <button class="darPermisos" data=<?=$row['id']?>>Dar permisos de moderador</button>
                <?php endif;} ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
    <script>
            $('.quitarPermisos').on("click", function(){
                id=$(this).attr("data");
                if(confirm("¿Seguro que queres quitarle admin a este usuario?")){
                $.post("cambiar_permisos.php",{
                    quitarPermisos:true,
                    id:id
                },function () {
                    location.reload();
                });
            }
            });
            $('.darPermisos').on("click", function(){
                id=$(this).attr("data");
                if(confirm("¿Seguro que queres hacer moderador a este usuario?")){
                $.post("cambiar_permisos.php",{
                    darPermisos:true,
                    id:id
                },function (data) {
                    if(data===false){
                        alert("Ya hay 5 moderadores en la página, no se pueden agregar más.")
                    }
                },"json");
                
                location.reload();
            }
            });
    </script>
</body>
</html>
