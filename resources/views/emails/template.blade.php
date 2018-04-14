<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <style>
        p.sansserif {
            font-family: Verdana, Helvetica, sans-serif;
        }
        i.sansserif {
            font-family: Verdana, Helvetica, sans-serif;
        }
        body{
            background-color: #dadada;
        }
    </style>
</head>


<body>
<div class="jumbotron">
    <h1 class="display-3">Board Game Tournament</h1>
    <p class="sansserif">Hi, er is een contactformulier/suggestie ingevuld door: {{$name}}!</p>
    <hr class="my-4" style="background-color: black;">
    <p class="sansserif">{{$body}}</p>
    <br>
    <br>
    <hr class="my-4" style="background-color: black;">
    <p class="lead">
        <br>
        <i class="sansserif">Email verstuurd door: {{$email}}</i>

    </p>
</div>
</body>
</html>

