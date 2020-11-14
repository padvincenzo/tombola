
function mostraMessaggio(messaggio) {
    $("#messaggio").html(messaggio);
    $("#ok").bind("click", function(e) { $("#box_b").css("display","none"); });
    $("#annulla").css("display","none");
    
    $("#box").css("display","inline-block");
    $("#box_b").css("display","block");
}

function chiedi(messaggio, pagina, id1 = 0, id2 = 0) {
    
    $("#messaggio").html(messaggio);
    $("#ok").bind("click", function(e) { doPost(pagina, id1, id2); });
    $("#annulla").css("display","inline-block");
    $("#annulla").bind("click", function(e) { $("#box_b").css("display","none"); });
    
    $("#box").css("display","inline-block");
    $("#box_b").css("display","block");
    
}

function doPost(pagina, id1 = null, id2 = null) {
    
    if (id1 != null) {
        
        $("#id").val(id1);
        if(id2 != null) $("#id2").val(id2);
        $('#f1').attr('action', pagina);
        $("form#f1").submit();
    
    } else {
        window.location.href = pagina;
    }
}

function checkPin() {
    var regex = /^[0-9]{4}$/;
    if(regex.test($("#pin").val()))
        doPost("client_login.php", $("#pin").val());
    else
        mostraMessaggio("PIN non valido.");
}

function nuova_partita() {
    chiedi('Vuoi creare una nuova partita?', 'server_create.php');
}

function termina_partita() {
    chiedi('Sei sicuro di voler terminare la partita?', 'server_stop.php');
}

function checkServerChanges() {
    
    $.ajax({url: 'server_userlist.php', success: function(result) {
        var obj = JSON.parse(urldecode(result));
        $("#NoU").html(obj.connessi);
        $("#NoC").html(obj.cartelleLibere);
        var lista = "";
        for(var i = 0; i < obj.connessi; i++) {
            lista += "<div><div>" + obj.utente[i].nick + "</div><div>[" + obj.utente[i].cartella + "]</div></div>";
        }
        $("#utenti").html(lista);
        if (obj.cartelleLibere < 48) {
            $("#btnIniziaPartita").prop("disabled", false);
        } else {
            $("#btnIniziaPartita").prop("disabled", true);
        }
    }});
    
    setTimeout(function() { checkServerChanges(); }, 800);
}

function estrai_numero() {
    $('#estrazione').prop("disabled", true);
    
    $.ajax({url: 'server_estrai.php', success: function(result) {
        if(result == "terminata") {
            mostraMessaggio("La partita è già terminata, non puoi estrarre altri numeri.");
        } else {
            var obj = JSON.parse(urldecode(result));
            if(obj.numero != 0) {
                if(!($("#n" + obj.numero).hasClass("estratto")))
                    $("#n" + obj.numero).addClass("estratto");
                    for (var i = 5; i > 1; i--) {
                        var j = i - 1;
                        var last = $("#u" + j).html();
                        $("#u" + i).html(last);
                    }
                    $("#u1").html(obj.numero);
            }
            
            for(var i = 0; i < obj.vincite.n; i++) {
                var vincitore = "     <tr>\n" +
                                "        <td class='premio'>" + obj.vincite.premio + "</td>\n" +
                                "        <td class=''>" + obj.vincite.user[i] + "</td>\n" +
                                "     </tr>\n";
                $("#vincitori").append(vincitore);
            }
            
            if(obj.stato == "terminata") {
                
                $("#termina").off('click').on('click', nuova_partita);
                $("#termina").html("Nuova partita");
                $("#estrazione").off('click').on('click', function () { window.location.href = "https://vincenzopadula.altervista.org/tombola/server_stop.php"; });
                $("#estrazione").html("Home");
                
                if(obj.vincite.user[0] == "Tombolone") obj.vincite.user[0] = "Il tombolone";
                switch(obj.vincite.n) {
                    case 1:
                    {
                        mostraMessaggio("La partita è terminata. " + obj.vincite.user[0] + " ha fatto tombola!");
                        break;
                    }
                    case 2:
                    {
                        mostraMessaggio("La partita è terminata. " + obj.vincite.user[0] + " e " + obj.vincite.user[1] + " hanno fatto tombola!");
                        break;
                    }
                    default:
                    {
                        var msg = "La partita è terminata. ";
                        for(var i = 0; i < obj.vincite.n - 1; i++) {
                            msg += obj.vincite.user[i] + ", ";
                        }
                        msg += " e " + obj.vincite.user[i] + " hanno fatto tombola!";
                        mostraMessaggio(msg);
                    }
                }
                
            }
            
            $('#estrazione').prop("disabled", false);
        }
    }});
}

function checkClientChanges() {
    
    $.ajax({url: 'client_updates.php', success: function(result) {
        
        var obj = JSON.parse(urldecode(result));
        
        if(obj.nn == 0) {
            $("#ultimi").html("Attendere l'inizio della partita...");
        } else {
            var numeri = "";
            for(var i = 1; i <= obj.nn; i++) {
                if(i <= 5)
                    numeri += "<div>" + obj.numero[i-1] + "</div>";
                if(!($("#n" + obj.numero[i-1]).hasClass("estratto")))
                    $("#n" + obj.numero[i-1]).addClass("estratto");
            }
            $("#ultimi").html(numeri);
        }
        
        var lista = "<tr><td class='premio privato'>Premio</td><td class='privato'>Utente</td></tr>";
        for(var i = 0; i < obj.np; i++) {
            lista += "<tr><td class='premio'>" + obj.premio[i].premio + "</td><td>" + obj.premio[i].nick + "</td></tr>";
        }
        $("#vincitori").html(lista);
        
        if(obj.stato == "terminato") {
            var i = 0;
            do {
                i++;
            } while (obj.premio[i].premio != "Tombola");
            if(obj.premio[i].nick == "Tombolone") obj.premio[i].nick = "Il tombolone";
            var nv = obj.np - i;
            switch(nv) {
                case 1:
                {
                    mostraMessaggio("La partita è terminata. " + obj.premio[i].nick + " ha fatto tombola!");
                    break;
                }
                case 2:
                {
                    mostraMessaggio("La partita è terminata. " + obj.premio[i].nick + " e " + obj.premio[i+1].nick + " hanno fatto tombola!");
                    break;
                }
                default:
                {
                    var msg = "La partita è terminata. ";
                    for( ; i < obj.np - 1; i++) {
                        msg += obj.premio[i].nick + ", ";
                    }
                    msg += " e " + obj.premio[i].nick + " hanno fatto tombola!";
                    mostraMessaggio(msg);
                }
            }
        } else
            setTimeout(function() { checkClientChanges(); }, 800);
    }});
}

function logout() {
    chiedi('Vuoi uscire dalla partita?', 'client_logout.php');
}

function urldecode(str) {
    return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}
