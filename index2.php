<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  
</body>
<script src="playlists/default.json"></script>
<script>
  fetch("./playlists/default.json").then(function(resp){
    return resp.json();
  })
  .then(function(data){
    console.log(data);
  })
</script>
</html>