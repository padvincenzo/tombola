<?php
    
    $title = "Unisciti alla partita";
    include("page_header.php");
    
    // PIN
    $pin = "";
    if(isset($content["pin"]))
        $pin = $content["pin"];
    else if(isset($_POST['pin']))
        $pin = $_POST['pin'];
    else if(isset($_POST['id']))
        $pin = $_POST['id'];
    
    // Nick
    $nick = "";
    if(isset($_POST['nick']))
        $nick = $_POST['nick'];
    
    // Ordine:        PIN    -->    Nick    -->    Cartella
    
    // Verifico presenza PIN
    
    if($pin != "") {
        
        // Verifico validità PIN
        
        $query = mysqli_query($dbh, "select * from ".PREFIX."server where pin = '$pin' and accessibile = true;");
        
        if(mysqli_num_rows($query) == 1) {
            
            $server = mysqli_fetch_array($query);
            
            // Verifico presenza nick
            
            if($nick != "") {
                
                // Verifico validità Nick
                
                $idserver = $server['idserver'];
                $query = mysqli_query($dbh, "select nick from ".PREFIX."utente where nick = '$nick' and idserver = '$idserver'");
                
                if(mysqli_num_rows($query) == 0) {
                    
                    // Inserisco l'utente nel database
                    mysqli_query($dbh, "insert into ".PREFIX."utente (nick, idserver) values ('$nick', '$idserver');");
                    $_SESSION["idutente"] = mysqli_insert_id($dbh)."";
                    
                    inviaMessaggio("Benvenuto $nick,<br>scegli una cartella con cui giocare.", "select_cartella.php");
                    
                } else {
                    
                    // Nick non valido
                    inviaMessaggio("Spiacente, nick già in uso", "client_login.php", array("pin" => $pin));
                }
            }
        
        } else {
            
            // PIN non valido
            inviaMessaggio("Il PIN che hai inserito non è valido", WEBSITE);
        }
    
    } else {
        
        // PIN non presente
        redirect(WEBSITE);
    }
    
?>
    
    <p class="titolo">Game PIN: <?php echo $pin; ?></p>
    <p>Inserisci un nickname</p>
    <form name='f1' action='client_login.php' method='post'>
        <input name='nick' type='text' maxlength='30' required />
        <input type='hidden' name='pin' value='<?php echo $pin; ?>'>
        <br>
        <button type='submit'>Avanti</button>
    </form>
    <br>
    <a href="<?php echo WEBSITE; ?>">Torna indietro</a>
    
<?php
    include("page_footer.php");
?>