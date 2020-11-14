<?php
    session_start();
    
    define("PREFIX", "tombola_k_");
    define("WEBSITE", "https://vincenzopadula.altervista.org/tombola/");
    
    $host = 'localhost';
    $user = 'vincenzopadula';
    $database = 'my_vincenzopadula';
    $psw = '';
    $dbh = mysqli_connect($host, $user, $psw, $database);
    
    if(!$dbh) {
        die("Oh.");
    }
    
    // Session
    if(isset($_SESSION["idserver"])) $idserver = $_SESSION["idserver"];
    if(isset($_SESSION["idutente"])) $idutente = $_SESSION["idutente"];
    
    /* Cancella le partite abbandonate */
    $query = "update ".PREFIX."server set offlimits = 1 where offlimits is not true and data < '".date('Y-m-d', strtotime("-10 days"))."';";
    mysqli_query($dbh, $query);
    
    function redirect($link) {
        header("Location: $link");
        die();
    }
    
    function inviaMessaggio($messaggio, $link, $content = null) {
        $data = urlencode(json_encode($content));
        $messaggio = rimuovi_apici($messaggio);
        $_SESSION['messaggio'] = array($messaggio, $data);
        redirect($link);
    }
    
    function rimuovi_apici($data) {
        $data = str_replace("'", "&#039;", $data);
        $data = str_replace('"', "&quot;", $data);
        return $data;
    }
    
    function checkOnlyNumbers($number = null) {
        if(is_numeric($number) && strpos($number, ".") == false && $number > 0) return true;
        return false;
    }
    
    function is_a_username($username = null) {
        if($username == null || $username == "") return false;
        return preg_match("/^[a-zA-Z0-9_.-]{2,20}$/", $username);
    }
?>
