<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="src/styles/styles.css" rel="stylesheet" type="text/css">
    <link href="src/styles/nullstyle.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body>

<div class="wrapper">
    <h1>URL shorter</h1>
    <div class="shorter">
        <div class="row center">
            <div class="input-field col s5">
                <input placeholder="Full URL" id="full_url_input" type="text" class="validate">
            </div>
            <div class="input-field col s5">
                <input placeholder="Custom short URL (optional)" id="custom_url_input" type="text" class="validate">
            </div>
            <a class="btn waves-effect waves-light col s2" id="go_button">
                Go
            </a>
        </div>
        <div class="row center">
            <div class="input-field col s10">
                <input id="short_url_output" disabled type="text">
            </div>
            <a class="btn waves-effect waves-light col s2" disabled id="copy_button">
                Copy
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="src/scripts/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</body>
</html>