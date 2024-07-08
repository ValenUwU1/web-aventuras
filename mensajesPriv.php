
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
                echo "<p>No hay conversaciones.</p>";
            } else {
                while ($row = mysqli_fetch_assoc($queryChat)) {
                    // Obtener la información del otro usuario en el chat
                    $id_involucrado = ($row['id_involucrado1'] == $id_usuario) ? $row['id_involucrado2'] : $row['id_involucrado1'];
                    $usuarioQuery = "SELECT * FROM usuario WHERE id = $id_involucrado";
                    $queryUsuario = mysqli_query($conn, $usuarioQuery);
                    if (mysqli_num_rows($queryChat) != 0) {
                    if ($result = mysqli_fetch_assoc($queryUsuario)) {
                        $nombre="[Usuario bloqueado]";
                        if(!$result['baneado']){
                            $nombre=$result['NombreUsuario'];
                        }
                        echo "<div class='chat' onclick='redirigir({$row['id']})'>
                                <div>
                                    <img src='{$result['DirFotoPerfil']}' alt='Foto de perfil' class='fotoMini'/>
                                </div>
                                        <h3>{$nombre}</h3>
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
                if($chatId==0){
                    echo "<p id='hola'>No se ha abierto ningún chat.</p>";
                }
                elseif (mysqli_num_rows($result) == 0) {
                    echo "<p id='hola'>No hay mensajes.</p>";
                }
                ?>
            </div>
            <div id="barraMensaje">
                <textarea minlength="1" name="mensajeEnviar" id="inputMens"></textarea>
                <button id="botonMen">Enviar mensaje</button>
                <button id="botonEdit">Editar mensaje</button>
                <button id="cancelarEdit" onclick="cambiarMen()">Cancelar</button>
            </div>
        </div>
    </div>
</body>
</html>
<script>
function redirigir(id){
    // Simulate an HTTP redirect:
    window.location.replace("mensajesPriv.php"+ "?LeChat=" +id  );
}

function cambiarEditar(){
    document.getElementById("botonMen").style.display="none";
    document.getElementById("botonEdit").style.display="flex";
    document.getElementById("cancelarEdit").style.display="flex";
}
function cambiarMen(){
    document.getElementById("botonMen").style.display="flex";
    document.getElementById("botonEdit").style.display="none";
    document.getElementById("cancelarEdit").style.display="none";
}

$(document).ready(function() {
  /*  let fetchdata = function () {
    // Manejar el click del botón #botonMen*/
    var ulti;
    var ultif;
    $(document).on('click','.botonEditar',function(){
    var id1=$(this).attr('data');
    var fecha= $(this).attr('fecha');
    $.post("fetch.php",{
        editarCheck:true,
        id:id1,
        fecha:fecha
    },
    function(response){
        console.log(response);
        if(response===false){
            alert("no se pudo editar el mensaje, la vida de este supera a los 15 minutos.");
        }
        else{
            $('#inputMens').val(response);
            ulti=id1;
            cambiarEditar();
        }
    },'json')
});
    $(document).on('click','.botonBorrar',function(){
        cambiarMen();
        id=$(this).attr('data');
        var fecha= $(this).attr('fecha');
        console.log(fecha);
        if(confirm('Seguro que queres borrar este mensaje?')){
            $.post("fetch.php",{
                borrar:true,
                id:id
            },
        function(response){
            console.log(response);
            if(response==0){
                alert("No se pudo borrar el mensaje, porque su vida superó los 15 minutos.");
            }
        },'json');
    }
    });
    $("#botonEdit").on('click',function(){
        var men= $("#inputMens").val();
        console.log(men);
        console.log(ulti)
        $.post("fetch.php",{
            id:ulti,
            men:men,
            editar:true
        },function(){
            $("#inputMens").val("");
            cambiarMen();
        });
    });
    $("#botonMen").on('click', function() {
        var mens = $("#inputMens").val();
        if (mens === "") return;
        else{
        }
        // Realizar la solicituds POST para enviar el mensaje
        $.post("fetch.php", {
            mensaje: mens,
            chat: <?=$chatId?> // Asegúrate de que $chatId esté definido correctamente
        }, function(response) {
            // Manejar la respuesta del servidor
            console.log(response); // Para depuración
            $("#inputMens").val("");
            xd();
        }).fail(function() {
            // Manejar el caso cuando la solicitud falla
            console.error('Error en la solicitud AJAX');
            alert('Error al enviar el mensaje');
        });
    });
});
function xd(){
    if(document.getElementById("hola")!=null)document.getElementById("hola").style.display="none";
    var areaMensajes = document.getElementById("areaMensajes");
    areaMensajes.scrollTop = areaMensajes.scrollHeight;
}
$(document).ready(function() {
    // Definir una función para obtener los mensajes del chat
    datapasada="";
    function fetchdata() {
        $.post("obtenerChat.php", {
            id_chat: <?=$chatId?>
        }, function(data) {
            if(data!=datapasada){
                $("#areaMensajes").html(data);
            }
            else{
                xd();
                datapasada=data;
            }
        },'html');
    }
    <?php
        if(isset($_GET["LeChat"])){
    ?>
    // Llamar a fetchdata inicialmente y luego configurar setInterval para llamadas periódicas
    fetchdata();
    setInterval(fetchdata, 1000);
    <?php
 } ?>
});
</script>