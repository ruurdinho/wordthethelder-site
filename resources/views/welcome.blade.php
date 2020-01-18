<!DOCTYPE html>
<html lang="nl">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{{ asset('img/favicon.png') }}}">
  <title>Wordt het helder vannacht?</title>
</head>
<body>
  <div id="background">
    <div class="verdict">
      <h1>Het wordt @if($clear) @else niet @endif helder.</h1>
    </div>

    <img src="img/moons/{{$moonPhase}}.png" alt="Maanfase" width="75px" height="75px" class="moon">
        <h2>Bewolkingspercentage per nachtelijk uur:</h2> 
    <div class="overview">
      @foreach ($nightHoursInformations as $nightHoursInformation)
        <li class="list-group-item d-flex justify-content-between">
            <p class="lead m-0">
              {{ date("G:i", $nightHoursInformation->time) }}
            </p>
            <p class="lead m-0">
              {{ $cloudcover = $nightHoursInformation->cloudCover*100 }}%
            </p>
        </li> 
      @endforeach
    </div>
    <footer><a href="https://darksky.net/poweredby/" target="_blank" rel="nofollow"><img src="img/poweredby-oneline-darkbackground.png" width="250px" height="50px"></a></footer>
  </div>
  <script src="js/app.js"></script>
</body>
</html>

