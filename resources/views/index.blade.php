<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name', 'car location') }}</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  {{-- Load Google Maps using Vite env variable --}}
  <script src="https://maps.googleapis.com/maps/api/js?key={{ env('VITE_GOOGLE_MAPS_KEY') }}&libraries=geometry" async defer></script>
  @vite(['resources/js/app.js'])
</head>
<body>
  <div id="app-root">
    {{-- You can mount your frontend here or link to the map page --}}
    <h1>car location</h1>
    <p>Use <a href="/map">/map</a> to view the realtime vehicle map.</p>
  </div>
</body>
</html>
