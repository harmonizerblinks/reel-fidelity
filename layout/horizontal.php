<?php
    require_once dirname(__DIR__) . '/conf/AppConfig.class.php';
?>

<html>
<head><title>
<?php echo AppConfig::SITE_TITLE; ?></title>
<style type="text/css">
body{
	background:#f78429;
	overflow-x:hidden;
	overflow-y:hidden;
}
</style>
</head>
<body>
<iframe style="display:block;border:none;" id='framecontent' width="100%" height="100%" src="../rates/"></iframe>

<script type="text/javascript">

// This is the duration set for the iframe to be refreshed with new page content, it is currently set
// to 4 minutes, which means that this page will be switching contents between rates and content after
// every 4 minutes, it will first show the content, after 4 minutes it will show the rates

var duration = 2 * 60 * 1000;

// Holds the frame id and the various uris to be switched to
var swipes = ['framecontent:../rates/', 'framecontent:../',];
var index = 0;

// This handles the interval rotation

setInterval(function(){

	// increase the index
	index++;
	if(index > 1){
		// show the first item again
		index = 0;
	}

	var id_specs = swipes[index];
	var parts = id_specs.split(':');
	var id = parts[0];
	var uri = parts[1];
	var c = document.getElementById(id);
	c.src = uri;
}, duration);

</script>
</body>
</html>
