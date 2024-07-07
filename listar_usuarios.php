
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
session_start();

include("basedatos.php");

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
<body>
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
                <?php if ($row["esMod"] == 1): ?>
                    <button class="quitarPermisos" onclick="quitarPermisos(<?=$row['id']?>)">Quitar permisos de moderador</button>
                <?php else: ?>
                    <button class="darPermisos"  onclick="darPermisos(<?=$row['id']?>)">Dar permisos de moderador</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <script>
            function darPermisos(id){
                $.ajax({
                    type:"POST",
                    url:"cambiar_permisos.php",
                    data:{id: id, darPermisos:true},
                    data:
                })
            }       
             function quitarPermisos(id){

            }
            /*$('.quitarPermisos').on("click", function(){
                id=$(this).attr("data");
                console.log("quitar" + id);
                var obj= $(this);
                $.post("cambiar_permisos.php",{
                    quitarPermisos:true,
                    id:id
                },function (data) {
                    obj.html(data);
                    obj.addClass("darPermisos").removeClass("quitarPermisos");
                });
            });
            $('.darPermisos').on("click", function(){
                id=$(this).attr("data");
                var obj= $(this);
                console.log("dar" + id);
                $.post("cambiar_permisos.php",{
                    darPermisos:true,
                    id:id
                },function (data) {
                    obj.html(data);
                    obj.addClass("quitarPermisos").removeClass("darPermisos");
                });
            });*/
    </script>
</body>
</html>
