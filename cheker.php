<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Config Checker</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
        <style type="text/css">
            body{
                background-color: #DADADA;
                padding: 30px;
            }
            .button{
                cursor:pointer;
            }
        </style>
    </head>
    <body class="">
        <h1>Configurations checker</h1>
        <p>This utility will check the configuration file and tell you if errors are found.</p>
        <button id="startButton" class="ui primary button">Start check</button>

        <div class="ui segment">
            <div id="resultContainer" class="ui relaxed divided list">
                
            </div>
        </div>

        <p>&nbsp;</p>
        <script src="js/jquery_3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
        <script>
            $("#startButton").click(function(){
                $("#resultContainer").html('Loading.....');
                $.ajax({
                    url: "cheker_script.php"
                }).done(function(data){
                    $("#resultContainer").html(data);
                });
            });
        </script>

    </body>
</html>