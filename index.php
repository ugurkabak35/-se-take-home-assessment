<?php 
session_start();
require_once("config.php");
error_reporting(0);
ini_set('display_errors', 0);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <?php 
        include("page/template/head.php");
        ?>
    </head>
    <body>

    <?php include("page/template/header.php"); ?>
    <input style="display: none" type="text" name="user_token" value="<?= $_SESSION["_access_token"] ?>">
        <?php 
        
            if(!isset($_REQUEST['page']) || $_REQUEST['page'] == "index")
            {
                include("page/index.php");
            }
            else
            {
                include("page/".$_REQUEST['page'].".php");
            }
        ?>

        <?php include("page/template/footer_script.php"); ?>
    </body>
</html>