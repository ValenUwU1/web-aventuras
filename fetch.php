<?php        
date_default_timezone_set('America/Argentina/Buenos_Aires');

include("basedatos.php");
session_start();
if(isset($_POST['borrar'])){    
    $result=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * from mensaje where id={$_POST['id']}"));
    $fecha=strtotime($result['fecha_envio']);
    $fechaLim= strtotime("-15 minutes");
    if($fecha>$fechaLim){
        mysqli_query($conn,"UPDATE mensaje SET borrado=1,mensaje='((borrado))' where id={$_POST['id']}");
        $data=true;
        echo json_encode($data);
    }
    else{
        $data=false;
        echo json_encode($data);
    }
    exit();
}
if(isset($_POST['editarCheck'])){
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mensaje WHERE id={$_POST['id']}"));
    if(!$result){
        echo json_encode(false);
    } else {
        $fecha = strtotime($result['fecha_envio']);
        $fechaLim = strtotime("-15 minutes");
        if($fecha > $fechaLim){
            $data=$result['mensaje'];
            echo json_encode($data);
        } else {
            $data=false;
            echo json_encode($data);
        }
    }
    exit();
}
if(isset($_POST["mensaje"])&& !empty($_SESSION["Id"])){
    $mensaje = $_POST["mensaje"];
    $chat = $_POST["chat"];
    $de = $_SESSION["Id"];

    // Preparar la consulta SQL usando una consulta preparada
    $sql = "INSERT INTO mensaje (id_emisor, id_chat, mensaje) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    // Vincular parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "iis", $de, $chat, $mensaje);
    mysqli_stmt_execute($stmt);
    $ultimoID= $conn->insert_id;
    // Verificar si la consulta se ejecutó correctamente
    if (mysqli_stmt_affected_rows($stmt) == 0) {
        echo "Error al enviar el mensaje";
    }   
    $nombre=mysqli_fetch_assoc(mysqli_query($conn,"SELECT NombreUsuario FROM usuario WHERE id={$_SESSION['Id']}"));
    $para=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM chat WHERE id=$chat"));
    if($para["id_involucrado1"]==$_SESSION["Id"]){
        $para=$para["id_involucrado2"];
    }
    else{
        $para=$para["id_involucrado1"];
    }
    $mensaje_notificacion = "Tenes un nuevo mensaje de {$nombre['NombreUsuario']}.";
    mysqli_query($conn,"INSERT INTO notificacion (usuarioID, mensaje) VALUES ('$para',' $mensaje_notificacion')");
    $fecha=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM mensaje WHERE id=$ultimoID"));
    ?>
    <?php 
    exit();
}
if(isset($_POST['editar'])){
    $mensaje=$_POST['men']." ((editado))";
    mysqli_query($conn,"UPDATE mensaje SET mensaje='$mensaje'WHERE id={$_POST['id']}");
}
?>