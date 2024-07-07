
<link rel="stylesheet" href="public/Style.css">
<link rel="stylesheet" href="public/StyleChat.css">
</style>
<?php
    if(isset($_POST["id_chat"])){
        session_start();
        include "basedatos.php";
        $id1=$_SESSION["Id"];
        $id_Chat=$_POST["id_chat"];
        $result = mysqli_query($conn, "SELECT * FROM mensaje WHERE id_chat=$id_Chat ORDER BY id ASC");
        if(!mysqli_num_rows($result)==0){
            while($chat=mysqli_fetch_assoc($result)){
            $chatid=$chat['id'];
            mysqli_query($conn,"UPDATE mensaje SET noLeido=0 WHERE id=$chatid");
            if($chat['id_emisor']!=$_SESSION['Id']){
            ?>
            <div class='menReceptorEncubridor'>
                <div class='menReceptor'> 
                        <p><?=$chat['mensaje']?><p>
                    </div>
                        <div class='botones'>
                            <em><?=$chat['fecha_envio']?></em>
                    </div>  
            </div>
            <?php
            } 
            else{
                ?>
                <div class='menEmisorEncubridor'>
                            <div class='menEmisor'>
                                <p><?=$chat['mensaje']?></p>                            
                            </div>
                                <div class='botones'>
                                    <em><?=$chat['fecha_envio']?></em>
                                    <?php
                                        if($chat['borrado']==0){
                                    ?>
                                        <button class='botonBorrar' data=<?=$chat['id'] ?> fecha=<?=$chat['fecha_envio']?>>borrar</button>
                                        <button class='botonEditar' data=<?=$chat['id']?> fecha=<?=$chat['fecha_envio']?>>editar</button>
                                    <?php
                                        } 
                                    ?>
                                </div>  
                            </div>
                <?php 
            }
            }
        }
    }
?>

