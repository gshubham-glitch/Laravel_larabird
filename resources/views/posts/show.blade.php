<div class="container"><div id="map" style="width: 1100px; height: 400px; background: grey"></div></div>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="description" content="Display a moveable marker on a map">
    
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{-- {{ config('app.name', 'Laravel') }} --}}THIS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css" />
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
  
</head>
<body>
    <div id="app">

        @include('inc.navbar')
        <div class="container">
              @include('inc.messages')
     

       <h1>{{$post->title}}</h1>
      <img style="width:60%" src="/storage/cover_images/{{$post->cover_image}}">
      <br><br>
      <div>
         {!!$post->body!!}
         <hr>
         longitutude is {{$post->lng}} and latitude is {{$post->lat}}
      </div> 
      <hr>
      Spotted on {{$post->created_at}} by {{$post->user->name}} at location {{$post->location}}<br>
      Species name: {{$post->species_name}}<br> total number of birds sighted: {{$post->number_sigh}}
      <br>
      <br>
      @if(!Auth::guest())
         @if(Auth::user()->id == $post->user_id)
      <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
      
      <hr>
      
      <a href="/posts" class="btn btn-primary btn-lg">Go Back</a> 
      
   
      {!!Form::open(['action' => ['PostsController@destroy', $post->id ], 'method' => 'POST', 'class' =>'pull-right'])!!}
         {{Form::hidden('_method','DELETE')}}
         {{Form::submit('Delete',['class' => 'btn btn-danger'])}}
      {!!Form::close() !!}
          @endif
      @endif
      
<script>
      /**
 * Adds a  draggable marker to the map..
 *
 * @param {H.Map} map                      A HERE Map instance within the
 *                                         application
 * @param {H.mapevents.Behavior} behavior  Behavior implements
 *                                         default interactions for pan/zoom

 */
 var lat = {{$post->lat}};
 var lng = {{$post->lng}}
function addDraggableMarker(map, behavior){

  var marker = new H.map.Marker({lat:lat, lng:lng});
  // Ensure that the marker can receive drag events
  marker.draggable = true;
  map.addObject(marker);

  // disable the default draggability of the underlying map
  // when starting to drag a marker object:
  map.addEventListener('dragstart', function(ev) {
    var target = ev.target;
    if (target instanceof H.map.Marker) {
      behavior.disable();
    }
  }, false);


  // re-enable the default draggability of the underlying map
  // when dragging has completed
  map.addEventListener('dragend', function(ev) {
    var target = ev.target;
    if (target instanceof mapsjs.map.Marker) {
      behavior.enable();
    }
    //console.log(marker.getPosition())
    var laat = marker.getPosition().lat;
    var long = marker.getPosition().lng;
    console.log(laat)
    console.log(long)
    
    document.getElementById("lat").value=laat;
    document.getElementById("lng").value=long;

  }, false);
  // var laat = marker.getPosition().lat;
  // var long = marker.getPosition().lng;
  // console.log(laat)
  // console.log(long)

  // Listen to the drag event and move the position of the marker
  // as necessary
   map.addEventListener('drag', function(ev) {
    var target = ev.target,
        pointer = ev.currentPointer;
    if (target instanceof mapsjs.map.Marker) {
      target.setPosition(map.screenToGeo(pointer.viewportX, pointer.viewportY));
    }
  }, false);
}

/**
 * Boilerplate map initialization code starts below:
 */

//Step 1: initialize communication with the platform
var platform = new H.service.Platform({
  app_id: 'nuKYhHFJdh6NMK9M4duO',
  app_code: '5Wmq-x701tp2YWL76Zqnog',
  useCIT: true,
  useHTTPS: true
});
var defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Boston
var map = new H.Map(document.getElementById('map'),
  defaultLayers.normal.map,{
  center: {lat:lat, lng:lng},
  zoom: 12
});

//Step 3: make the map interactive
// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Step 4: Create the default UI:
var ui = H.ui.UI.createDefault(map, defaultLayers, 'en-US');

// Add the click event listener.
addDraggableMarker(map, behavior);
    </script>
</div>
    </div>
 </body>
 </html>