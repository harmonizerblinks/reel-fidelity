<?php   
    require_once dirname(__DIR__) . '/conf/AppConfig.class.php';
?>

<html>
<head><title><?php echo AppConfig::SITE_TITLE; ?></title>
</head>

<frameset rows="50%,*" frameborder="no" framespacing="0" border="0">
    <frame src="../"/>   
    <frame src="../rates/"/>    
</frameset>

</html>