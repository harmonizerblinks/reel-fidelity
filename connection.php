<?php

// This scripts helps to connect to the old proskool for
// database queries
function _connect(){
  $username = 'fingerti_Charles';
  $password = 'ofadarice@88';
  //$db = 'sunnydale';
  $conn = @mysqli_connect('fingertip.com.ng', $username, $password);
  if(!$conn){
    echo mysqli_connect_error($conn);
    throw new Exception('Unable to connect to database at fingertip.com.ng');
  }
  return $conn;
}

function executeQuery($sql){
  // This method is used for executing queries that does not return a result
  $conn = _connect();
  $db = 'fingerti_reel_mediaresource2';
  mysqli_select_db($conn, $db);
  $result= mysqli_query($conn, $sql);
  if($result == false){
    echo "Error". mysqli_error($conn);
    exit;
  }
  else{
    return $result;
  }
}

function queryData($sql){
  // This method can query a table or a view and return the
  // response as an array
  $db = 'fingerti_reel_mediaresource2';
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
  $db = 'fingerti_reel_mediaresource2';
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
  $db = 'fingerti_reel_mediaresource2';
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
  $db = 'fingerti_reel_mediaresource2';
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


