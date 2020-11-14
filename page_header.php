<?php
    include("connect.php");
    ob_start( );
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?> - Tombola!</title>
        <meta charset="UTF-8">
        
        <!-- MaxCDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        
        <link rel="icon" href="<?php echo WEBSITE; ?>icons/icon.png">
        <link rel="stylesheet" type="text/css" href="<?php echo WEBSITE; ?>style.css">
        <script src="<?php echo WEBSITE; ?>code.js"></script>
    </head>
    
    <body>
        
        <div id="box_b" style="display:none">
            <br><br><br><br>
            <div id="box">
                <div id="messaggio"></div>
                <br><br>
                <button id="annulla" class="small-button" style="display:none;">Annulla</button>
                <button id="ok" class="small-button">OK</button>
            </div>
        </div>
        
        <?php
            
            // Se c'è un messaggio o una notifica
            if(isset($_SESSION['messaggio'])) {
                $messaggio = $_SESSION['messaggio'][0];
                $content = json_decode(urldecode($_SESSION['messaggio'][1]), true);
                echo "<script>mostraMessaggio('$messaggio')</script>\n";
                unset($_SESSION['messaggio']);
            }
            
        ?>
        
        <form id="f1" method="POST" action="">
            <input type="hidden" id="id" value="" name="id" />
            <input type="hidden" id="id2" value="" name="id2" />
        </form>
        
        <div id="container">
