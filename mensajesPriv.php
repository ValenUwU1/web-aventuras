<?php
include("basedatos.php");
include("header.php");
include("BarraNavegacion.php");
ob_start();

// Verificar si no hay sesión activa
if (empty($_SESSION["Id"])) {
    header("Location: index.php");
    exit; // Asegurar que se detenga la ejecución si no hay sesión activa
} else {
    $id_usuario = $_SESSION["Id"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/Style.css">
    <link rel="stylesheet" href="public/StyleChat.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Chat</title>
</head>
<body>
    <div id="divmain">
        <div id="divchat">
            <?php 
            $chatQuery = "SELECT * FROM chat WHERE id_involucrado1=$id_usuario OR id_involucrado2=$id_usuario";
            $queryChat = mysqli_query($conn, $chatQuery);
            
            if (mysqli_num_rows($queryChat) == 0) {
                echo "<p>No tenés ningún chat. Para empezar, anda al perfil de alguien y presioná 'enviar mensaje'.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($queryChat)) {
                    // Obtener la información del otro usuario en el chat
                    $id_involucrado = ($row['id_involucrado1'] == $id_usuario) ? $row['id_involucrado2'] : $row['id_involucrado1'];
                    $usuarioQuery = "SELECT * FROM usuario WHERE id = $id_involucrado";
                    $queryUsuario = mysqli_query($conn, $usuarioQuery);
                    if (mysqli_num_rows($result) == 0) {
                        echo "<p>No tenes chats.</p>";
                    } else {
                    if ($result = mysqli_fetch_assoc($queryUsuario)) {
                        echo "<div class='chat' onclick='redirigir({$row['id']})'>
                                <div>
                                    <img src='{$result['DirFotoPerfil']}' alt='Foto de perfil' class='fotoMini'/>
                                </div>
                                        <h3>{$result['NombreUsuario']}</h3>
                            </div>";
                    }
                }
                }
            }
            ?>
        </div>
        <div id="divmens">
            <div id="areaMensajes">
                <?php
                $chatId = $_GET["LeChat"] ?? 0;
                $result = mysqli_query($conn, "SELECT * FROM mensaje WHERE id_chat=$chatId");

                if (mysqli_num_rows($result) == 0) {
                    echo "<p>No se ha abierto ningún chat.</p>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row["id_emisor"] == $id_usuario) {
                            echo "<div class='menEmisor'>
                                    <p>{$row['mensaje']}</p>
                                </div>";
                        } else {
                            echo "<div class='menReceptor'>
                                    <p>{$row['mensaje']}</p>
                                </div>";
                        }
                    }
                }
                ?>
            </div>
            <div id="barraMensaje">
                <textarea minlength="1" name="mensajeEnviar" id="inputMens"></textarea>
                <button id="botonMen">Enviar mensaje</button>
            </div>
        </div>
    </div>
</body>
</html>
<script>
function redirigir(id){
    // Simulate an HTTP redirect:
    window.location.replace(window.location + "?LeChat=" +id  );
}
$(document).ready(function() {
  /*  let fetchdata = function () {
 
    // Manejar el click del botón #botonMen*/
    $("#botonMen").on('click', function() {
        var mens = $("#inputMens").val();
        if (mens === "") return;

        // Realizar la solicitud POST para enviar el mensaje
        $.post("fetch.php", {
            mensaje: mens,
            chat: <?=$chatId?> // Asegúrate de que $chatId esté definido correctamente
        }, function(response) {
            // Manejar la respuesta del servidor
            console.log(response); // Para depuración
            $("#areaMensajes").append('<div class="menEmisor"><p>'+ mens +'</p></div>');
            $("#inputMens").val("");

            // Hacer scroll al final del área de mensajes
            var areaMensajes = document.getElementById("areaMensajes");
            areaMensajes.scrollTop = areaMensajes.scrollHeight;
        }).fail(function() {
            // Manejar el caso cuando la solicitud falla
            console.error('Error en la solicitud AJAX');
            alert('Error al enviar el mensaje');
        });
    });
});
$(document).ready(function() {
    // Definir una función para obtener los mensajes del chat
    function fetchdata() {
        $.post("obtenerChat.php", {
            id_chat: <?=$chatId?>
        }, function(data, status) {
            $("#areaMensajes").append(data);
        });
    }

    // Llamar a fetchdata inicialmente y luego configurar setInterval para llamadas periódicas
    fetchdata();
    setInterval(fetchdata, 500);
});
</script>