<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Metajm</title>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
    <style>
    #welcome {
        position: absolute;
        color: #FFFFFF;
        z-index: 2;
        top: 30%;
        right: 15%;
        text-align: right;
    }
    #welcome h1 {
        margin: 0;
        border-bottom: 1px solid #A3A3CC;
        padding-bottom: 2px;
        margin-bottom: 10px;
    }
    #welcome h2 {
        margin: 50px 0 0 0;
    }
    </style>
</head>
<body>
    <div id="welcome">
        <h1>Din bokning har mottagits</h1>
        <div>Tid: {{ $time->format('j D F H:i') }}</div>
        <div>Adress: {{ $company->address }}</div>
        <div>Tel: {{ $company->tel }}</div>
        <h2>Varmt välkommen till oss!</h2>
    </div>
    <div id="start">
        <img src="{{ URL::asset('img/test_bild_2.jpg') }}" id="a">
        <div id="fade" style="display:inline-block;"></div>
        <div id="start-background"></div>
    </div>
<script src="{{ URL::asset('vendor/jquery/jquery-1.12.0.min.js') }}"></script>
</body>
</html>