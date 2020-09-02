<?php   
    require_once dirname(__DIR__) . '/conf/AppConfig.class.php';
?>

<html>
<head><title><?php echo AppConfig::SITE_TITLE; ?></title>
</head>

<frameset cols="50%,*" framespacing="0" border="0">
    <frame src="../rates/"/>   
    <frame src="../"/>       
</frameset>

</html>