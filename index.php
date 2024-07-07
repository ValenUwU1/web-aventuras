<?php
include("header.php");
include("basedatos.php");
include("BarraNavegacion.php");
?>
 <?php 
        $contadorVeleros=0;
        $contadorPesca=0;
        $contadorYates=0;
        $contadorLancha=0;
        $contadorTotal=0;
        $intercambios=mysqli_query($conn,"SELECT b.tipo as tipo1, b1.tipo as tipo2, i.fecha as fecha FROM intercambio i INNER JOIN embarcacion b on b.id=i.fk_embarcacion1 INNER JOIN embarcacion b1 ON b1.id=i.fk_embarcacion2;");
        while($row=mysqli_fetch_assoc($intercambios)){
            $fechaSemanaPasada=strtotime("-1 week");
            if(strtotime($row['fecha']) > $fechaSemanaPasada){
                switch($row["tipo1"]){
                    case "Lancha":
                        $contadorLancha++;
                        break;
                    case "Pesca":
                        $contadorPesca++;
                        break;
                    case "Yate":
                        $contadorYates++;
                        break;
                    case "Velero":
                        $contadorVeleros++;
                        break;
                }               
                switch($row["tipo2"]){
                    case "Lancha":
                        $contadorLancha++;
                        break;
                    case "Pesca":
                        $contadorPesca++;
                        break;
                    case "Yate":
                        $contadorYates++;
                        break;
                    case "Velero":
                        $contadorVeleros++;
                        break;
                }   
        }
            $contadorTotal++;
        }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Web Aventuras</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/Style.css">
    <style>
        h4{
            flex:1;
        }
        button{
            flex:1;
        }
    </style>
</head>

<body>
    <section class="publicaciones">
    <div style="bottom:10px;right:10px;position:fixed;z-index: 10000; ">   
        <button id="ver" onclick="document.getElementById('stats').style.display='flex';document.getElementById('ver').style.display='none'">Ver estadisticas</button>
        <div id="stats" style="padding:20px;display:none;align-items:center;justify-content:center;flex-direction:column;flex: 1;background-color:whitesmoke; border: black 1px solid; border: radius 10px;width:fit-content;height:auto">
        <button class="btn btn-warning" style="background-color:crimson; color:white; border-radius:8px; cursor:pointer" onclick="document.getElementById('stats').style.display='none'; document.getElementById('ver').style.display='flex'">X</button> 
            <h4>Cantidad de barcos intercambiados desde el inicio de la página: <?=$contadorTotal?></h4>    
            <h4>Cantidad de veleros intercambiados esta semana: <?=$contadorVeleros?></h4>    
            <h4>Cantidad de yates intercambiados esta semana: <?=$contadorYates?></h4>    
            <h4>Cantidad de barcos pesqueros intercambiados esta semana: <?=$contadorPesca?></h4>   
            <h4>Cantidad de lanchas intercambiados esta semana: <?=$contadorLancha?></h4>    
        </div>
    </div>
        <div>
            <ul>
                <?php
                    $sql = "SELECT publicacion.*, embarcacion.valor AS precio, embarcacion.anio AS antiguedad, embarcacion.Tipo AS tipo, embarcacion.marca 
                        FROM publicacion 
                        JOIN embarcacion ON publicacion.embarcacionID = embarcacion.id";

                    // Filtrar publicaciones según los criterios seleccionados
                    if (isset($_GET['criterio'])) {
                        $criterio = $_GET['criterio'];
                        switch ($criterio) {
                            case 'precio_asc':
                                $sql .= " ORDER BY embarcacion.valor ASC";
                                break;
                            case 'precio_desc':
                                $sql .= " ORDER BY embarcacion.valor DESC";
                                break;
                            case 'antiguedad_asc':
                                $sql .= " ORDER BY embarcacion.anio ASC";
                                break;
                            case 'antiguedad_desc':
                                $sql .= " ORDER BY embarcacion.anio DESC";
                                break;
                            case 'tipo':
                                if (isset($_GET['tipo'])) {
                                    $tipo = $conn->real_escape_string($_GET['tipo']);
                                    $sql .= " WHERE embarcacion.Tipo = '$tipo'";
                                }
                                break;
                            case 'marca':
                                if (isset($_GET['marca'])) {
                                    $marca = $conn->real_escape_string($_GET['marca']);
                                    $sql .= " WHERE embarcacion.marca = '$marca'";
                                }
                                break;
                        }
                    }

                    $resultado = mysqli_query($conn, $sql);

                    // Comprobar si hay resultados
                    if (mysqli_num_rows($resultado) > 0) {
                        // Recorrer los resultados y mostrar cada publicación
                        while ($row = mysqli_fetch_assoc($resultado)) {
                            echo '<li><a href="visualizar_publicacion.php?id=' . $row["id"] . '">' . $row["Titulo"] . '</a></li>';
                        }
                    } else {
                        echo '<li>No hay publicaciones disponibles.</li>';
                    }

                    // Liberar el resultado
                    mysqli_free_result($resultado);

                    // Cerrar la conexión a la base de datos
                    mysqli_close($conn);
                ?>
            </ul>
        </div>
    </section>
    
    <div style="z-index:10000">
    <?php include("formulario_filtro.php"); ?>  <!-- Formulario de filtrado -->
        <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit, ad. Harum unde cupiditate qui distinctio, optio culpa. Nihil, deleniti facilis quos ad quia maxime cumque quis odit, delectus nulla ratione.</h1>
    </div>
    <footer>
    </footer>
</body>
   
</html>
