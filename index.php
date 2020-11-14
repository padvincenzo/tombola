<?php
    $title = "Home";
    include("page_header.php");
?>

    <img id="logo" src="icons/logo.png">
    <br>

    <div id="login">
        <p>Inserisci un PIN<sup>*</sup></p>
        <div id="wrapper">
            <input id='pin' type='text' maxlength='4' autocomplete="off" placeholder="Game PIN">
            <button onclick="checkPin();">Gioca!</button>
        </div>
        <p id="w-help"><sup>*</sup>Chi crea la partita ha il Game PIN</p>
    </div>
    <br>
    <p><a href="server.php">Crea una partita</a></p>
    <br>
    <p class="small-link"><a href="https://github.com/padvincenzo/tombola" target="_blank">Visita la pagina GitHub del progetto</a></p>
<?php
        include("page_footer.php");
?>
