<?php

final class BuildUtil{

  public static function rebuildSchedules($ip_address,$ipHash){
    $resources = queryData('SELECT * FROM resource_media_files');
    $schedules = queryData(sprintf("SELECT * FROM resource_media_schedule WHERE ipAddressHash = '%s' ",$ipHash));
    
    self::buildSchedules($ip_address, $resources, $schedules);
  }

  
  public static function buildSchedules(
    $ip_address
    , array $resources
    , array $schedules){

      $json_filename = 'default';
      if($ip_address !== '127.0.0.1'){
        $json_filename = $ip_address;
      }

    $resources = ipull($resources, null, 'fileHash');
    $schedules = ipull($schedules, null, 'fileOrder');
    ksort($schedules);

    $blocks = array();
    $known_photos = array('jpeg', 'jpg', 'gif', 'png');
    $known_photos = array_fill_keys($known_photos, 1);

    foreach($schedules as $fo => $schedule){
      $resource = idx($resources, $schedule['fileHash'], array());
      $key = "fh{$fo}";
      $uri = null;
      if(!array_key_exists($resource['fileType'], $known_photos)){
        // this is a video
        $uri = self::buildVideoSlide($resource, $schedule);
      }else{
        // we are building image
        $uri = self::buildPhotoSlide($resource, $schedule);
      }

      $blocks[$fo] = array(
        'id'    => $key,
        'title' => $schedule['scheduleName'],
        'url'   => $uri,
      );
    }
    $parser = new JSONParser();
    // we need to write this to a file
    $root = MediaUtils::getApplicationRoot();
    $pathToRateJson = $root.DIRECTORY_SEPARATOR.'playlists'.DIRECTORY_SEPARATOR.'rates.json';
    $rateInJson = file_get_contents($pathToRateJson,false,null,0);
    $rateInJson = $parser->encodeFormatted(json_decode($rateInJson,true));
        
    // read the file that was configured and write this to it
    $tpath = $root.DIRECTORY_SEPARATOR.
    'playlists'.DIRECTORY_SEPARATOR.'tmpl.json';
    
    // Final Path where the file will be saved to

    $fpath = $root.DIRECTORY_SEPARATOR.
    'playlists'.DIRECTORY_SEPARATOR.$json_filename.'.json';
    // Select those files in the temporary path
    $data = file_get_contents($tpath);
    $build = array();
    if($data){
      $build = json_decode($data, true);
      if(!$build){
        $build = array();
      }
    }
    
    
    // we need to update the slides option
    foreach($build['slides'] as $dict){
      $blocks[$dict['id']] = $dict;
    }
    
    ksort($blocks);
    $build['slides'] = array_values($blocks);
    // write the file back to the system
    
    $json = $parser->encodeFormatted($build);

    foreach(json_decode($json, true) as $key => $array){
      $r[$key] = array_merge(json_decode($rateInJson, true)[$key],$array);
    }
    
    // JSON_PRETTY_PRINT function helps to format the json string into human readable format
    $json = json_encode($r,  JSON_PRETTY_PRINT);
    // Remove the backslash (\) from the json arrays
    $json = str_replace('\\','',$json);
    
     
    file_put_contents($fpath,$json);
    

    // file_put_contents($fpath, $json,FILE_APPEND);
  }

  public static function buildVideoSlide(array $resource, array $schedule){

    $hash = $resource['fileHash'];
    $ext = $resource['fileType'];
    $folder = substr($hash, 0, 2);
    $filename = substr($hash, 2);

    $uri = 'mediastore/'.$folder.'/'.$filename.'.'.$ext;
    // build the url for the file we are creating
    $slidename = 'h'.$schedule['fileOrder'];
    $root = MediaUtils::getApplicationRoot();
    $timer = $schedule['timer'];

    $path =
    $root.DIRECTORY_SEPARATOR.'slides'.
    DIRECTORY_SEPARATOR.$slidename.'.html';
    $url = 'slides/'.$slidename.'.html';

    $doc =<<< EOD
    
    <!--<div class="dynamic-slide dynamic-slide-2 video">
    	 <div class="slide-bg"> -->
    		<video id="video" width="100%" height="100%" muted class="dynamic-slide-video dynamic-slides" timer="{$timer}">
          <source src='{$uri}'  type='video/{$ext}'  />
          <embed src="{$uri}" type="application/x-shockwave-flash" width="100%" height="100%" allowscriptaccess ="always" allowfullscreen = 'true'>
          </embed>  
          <p>
            Sorry your browser does not support the video format
          </p>
    		</video>
    	<!-- </div>
    	<div class="container">
    	</div>
    </div> -->
    <script>
    // var v = document.getElementById("video");
    // var original_bg_color = $('body').css('background-color');
    // v.addEventListener('click', function() {
    // 	if (v.paused) {
    // 		v.play();
    // 	} else {
    // 		v.pause();
    // 	}
    // })
    // v.addEventListener('canplay', function(){
    // 	pauseSlides();
    // 	pauseSound();
    // 	$('body').css('background-color', 'black');
    // });
    // v.addEventListener('ended',function(){
    // 	nextSlide();
    // 	resumeSound();
    // 	$('body').css('background-color', original_bg_color);
    // });
    </script>
EOD;

if(file_exists($path)){
  unlink($path);
}
   file_put_contents($path, $doc);
   return $url;
  }

  public static function buildPhotoSlide(array $resource, array $schedule){
    $hash = $resource['fileHash'];
    $ext = $resource['fileType'];
    $folder = substr($hash, 0, 2);
    $filename = substr($hash, 2);

    $uri = 'mediastore/'.$folder.'/'.$filename.'.'.$ext;
    // build the url for the file we are creating
    $slidename = 'h'.$schedule['fileOrder'];
    $root = MediaUtils::getApplicationRoot();

    $timer = $schedule['timer'];

    $path =
    $root.DIRECTORY_SEPARATOR.'slides'.
    DIRECTORY_SEPARATOR.$slidename.'.html';
    $url = 'slides/'.$slidename.'.html';

    $doc =<<<EOD
    <div class="dynamic-slide">
	<div class="slide-bg">
			<img src='{$uri}' class="dynamic-slides" timer="{$timer}" />
		</div>
	</div>
EOD;
    if(file_exists($path)){
      unlink($path);
    }

    file_put_contents($path, $doc);
    return $url;
  }
}
