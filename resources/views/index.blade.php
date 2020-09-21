<!doctype html>
<html>
  <head>
    <title>How to Upload a File in Laravel</title>
  </head>
  <body>
    <!-- Message -->
    @if(Session::has('message'))
      <p >{{ Session::get('message') }}</p>
    @endif

    <!-- Form -->
    <form method='post' action='/api/uploadFile' enctype='multipart/form-data' >
      {{ csrf_field() }}
      <input type='file' name='file' >
      <input type='submit' name='submit' value='Upload File'>
    </form>
  </body>
</html>