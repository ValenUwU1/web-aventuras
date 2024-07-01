<?php
include("basedatos.php");
session_start();
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

    // Verificar si la consulta se ejecutó correctamente
    if (mysqli_stmt_affected_rows($stmt) == 0) {
        echo "Error al enviar el mensaje";
    }

    // Cerrar la consulta preparada
    mysqli_stmt_close($stmt);
    exit();
}
if(!empty($_SESSION["Id"]) &&isset($_POST["recibir"])){

}
?>