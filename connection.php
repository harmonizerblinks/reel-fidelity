<?php

// This scripts helps to connect to the old proskool for
// database queries
function _connect(){
  $username = 'root1';
  $password = 'linux';
  //$db = 'sunnydale';
  $conn = @mysqli_connect('localhost', $username, $password);
  if(!$conn){
    throw new Exception('Unable to connect to database at localhost');
  }
  return $conn;
}

function executeQuery($sql){
  // This method is used for executing queries that does not return a result
  $conn = _connect();
  $db = 'reel_mediaresource';
  mysqli_select_db($conn, $db);
  return mysqli_query($conn, $sql);

}

function queryData($sql){
  // This method can query a table or a view and return the
  // response as an array
  $db = 'reel_mediaresource';
  $conn = _connect();
  mysqli_select_db($conn, $db);

  $result = mysqli_query($conn, $sql);
  $maps = array();
  if($result){
    while($row = mysqli_fetch_assoc($result)){
      $maps[] = $row;
    }
  }
  return $maps;
}
function queryData2($sql){
  // This method can query a table or a view and return the
  // response as an array
  $db = 'reel_mediaresource';
  $conn = _connect();
  mysqli_select_db($conn, $db);

  $result = mysqli_query($conn, $sql);

  if($result){
    $result = mysqli_fetch_assoc($result);
    return $result;
  }else{
    return "Your query is empty";
  }
}
function queryData3($sql){
  /** This method can query a table or a view and return the response as an array */
  $db = 'reel_mediaresource';
  $conn = _connect();
  mysqli_select_db($conn, $db);

  $result = mysqli_query($conn, $sql);

  if($result){
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
  }else{
    return "Your query is empty";
  }
}

function queryData4($sql){
  // This method can query a table or a view and return the
  // associated response 
  $db = 'reel_mediaresource';
  $conn = _connect();
  mysqli_select_db($conn, $db);

  $result = mysqli_query($conn, $sql);

  if($result){
    $result = mysqli_fetch_assoc($result);
    return $result;
  }else{
    return "Your query is empty";
  }
}


