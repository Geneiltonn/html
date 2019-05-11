<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <title>Testador</title>
        <style>
            body {
                background-color: black;
                color: white;
            }
        </style>
    </head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script title="ajax do checker">
        $(document).ready(function () {
            $('#iniciar').click(function () {
                if (!$('#lista').val()) {
                    document.getElementById('iniciar').innerHTML = 'Lista vazia!';
                } else if (!$('#key').val()) {
                    document.getElementById('iniciar').innerHTML = 'Insira sua key!';
                } else {
                    var apv = 0;
                    var rpd = 0;
                    var testadas = 0;
                    var token;
                    token = document.getElementById('key').value;
                    var line = $('#lista').val();
                    line = line.split("\n");
                    var total = line.length;
                    $("#total").html(total);
                    line.forEach(function (value, index) {
                        document.getElementById('iniciar').innerHTML = "Testando.";
                        $.ajax({
                            url: 'api.php',
                            type: 'GET',
                            data: 'lista=' + value + '&token=' + token,
                            success: function (data) {
                                var json = JSON.parse(data);
                                var msg = json.msg;
                                switch (json.status) {
                                    case 0:
                                        removelinha();
                                        $("#Aprovada").append(msg);
                                        testadas = testadas + 1;
                                        apv = apv + 1;
                                        break;
                                    case 1:
                                        removelinha();
                                        $("#Reprovada").append(msg);
                                        testadas = testadas + 1;
                                        rpd = rpd + 1;
                                        break;
                                }

                                var fila = parseInt(apv) + parseInt(rpd);
                                $("#apv").html(apv);
                                $("#rpd").html(rpd);
                                $("#testadas").html(testadas);
                                titulo('[' + $('#testadas').text() + '/' + $('#total').text() + '] Mercado Pago');

                                if (fila == total) {
                                    $("#iniciar").attr('disabled', null);
                                    $("#lista").attr('disabled', null);
                                    document.getElementById("iniciar").innerHTML = "Teste Finalizado!";
                                }
                            }
                        });
                    });
                } // aq
            });
        });

        function titulo(text) {
            document.title = text;
        }

        function removelinha() {
            var lines = $("#lista").val().split('\n');
            lines.splice(0, 1);
            $("#lista").val(lines.join("\n"));
        }
    </script>
    <body>
    <center>
        <h1>Testador Elo</h1>
        <textarea name="lista" id="lista" rows="7" placeholder="Carregue suas lata!" style="margin: 0px; width: 40%;px; height: 132px;"></textarea><br /><br />
        <input type="text"  name="key" id="key" style="width: 40%;" placeholder="Key em produção" /> <br /><br />
       
        <div>
            <span>Aprovadas: </span><span id="apv">0</span>
            <span>Reprovadas: </span> <span id="rpd">0</span>
            <span>Testadas: </span> <span id="testadas">0</span>
            <span>Carregadas: </span> <span id="total">0</span>
        </div>
        <br>
        <div>
            <button type="button" name="iniciar" id="iniciar" style="width:15%;">Iniciar </button>
        </div>
        <br>
        <div>
            Aprovada
            <button type="button" onclick="document.getElementById('Aprovada').innerHTML = ''"> - Limpar</button>
            <h4 id="Aprovada"></h4>
        </div>

        <div>
            Reprovada
            <button type="button" onclick="document.getElementById('Reprovada').innerHTML = ''"> - Limpar</button>
            <h4 id="Reprovada"></h4>
        </div>
    </center>
</body>
</html>