<?php

// require 'src/fidelity_rate_reader2.php';
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__ . '/src/Reel.class.php';
require_once __DIR__.'/src/Rates.class.php';



$rdt = new Rates();
$data = $rdt->getFXRates();
$view = null;
if($data){
  $view = $rdt->buildViewableFXRatesTable($data);
}

$doc =<<< EOD
<div style="background-color:#000;z-index:100000;color:#fff;" class="dynamic-slides Stanbic-bank">
 <br/>
<center><img src="assets/img/fidelity_logo.png" /></center>
      </br>
	<div class="slide-bgs">
  {$view}
		</div>
	</div>
</div>
EOD;

file_put_contents(__DIR__.'/slides/h22.html', $doc);
// echo $doc;
