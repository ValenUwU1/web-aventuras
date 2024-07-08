<?php
    include("basedatos.php");
    if(isset($_POST["bloquear"])){
        $id=$_POST["id"];
        $sqlPublicaciones="SELECT p.id as pubId FROM usuario u inner join publicacion p on p.usuarioID=u.id where u.id=$id; ";
        if($result=mysqli_query($conn,$sqlPublicaciones)){
            while($row=mysqli_fetch_assoc($result)){
                echo $row["pubId"];
                $sql="DELETE FROM publicacion WHERE id={$row['pubId']}";
                mysqli_query($conn,$sql);
            }
        }
        $sqlOfertas="SELECT o.id as ofertaId FROM usuario u inner join oferta o ON u.id=o.idUsuario where u.id=$id";
        if($result=mysqli_query($conn,$sqlOfertas)){
            while($row=mysqli_fetch_assoc($result)){
                echo $row["ofertaId"];
                $sql="DELETE FROM oferta WHERE id={$row['ofertaId']}";
                mysqli_query($conn,$sql);
            }
        }
        echo "hola";
        $sql="UPDATE usuario SET baneado=1, Nombre='[Usuario bloqueado]', Apellido='[Usuario bloqueado]' where id=$id";
        mysqli_query($conn,$sql);
        exit();
    }
?>