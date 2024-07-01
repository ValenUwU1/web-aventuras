<style>
    .menReceptor{
    margin-right: auto;
    background-color: gray;
    border-radius: 10px;
    height: fit-content;
    padding-left: 30px;
    width: fit-content;
    display:flex;
    padding:4px;
}
</style>
<?php
    if(isset($_POST["id_chat"])){
        session_start();
        include "basedatos.php";
        $id1=$_SESSION["Id"];
        $id_Chat=$_POST["id_chat"];
        $result = mysqli_query($conn, "SELECT * FROM mensaje WHERE id_chat=$id_Chat and not id_emisor=$id1 and noLeido=1 ORDER BY id ASC");
        if(!mysqli_num_rows($result)==0){
            while($chat=mysqli_fetch_assoc($result)){
            $chatid=$chat['id'];
            mysqli_query($conn,"UPDATE mensaje SET noLeido=0 WHERE id=$chatid");
            ?>
                <div class="menReceptor">
                    <p><?=$chat['mensaje']?><p>
                </div>
            <?php
            }
        }
    }
?>
