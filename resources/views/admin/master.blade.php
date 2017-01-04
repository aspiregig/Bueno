<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <title>Bueno</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />



    @yield('header')
  
</head>
<body id="{{$page}}">
  <div id="wrapper">
    
    @include('admin.partials.sidebar')

    @yield('content')

  </div>

	@yield('script')

</body>
<script src="{{asset('js/main.js')}}"></script>

</html>
