<?php


require_once __DIR__ . DIRECTORY_SEPARATOR. 'RatesData.class.php';

class Rates{

    public static function rateTypesMap(){
        return array(
            'default' => 'FX CASH RATES',
            'international' => 'International Rates'
            );
    }


    public function get(
      $baseCurrency=''
      , $otherCurrencies=array()
      , $rates_type='default'){

        set_time_limit(60);
        $rd = new RatesData();
        $rates = $rd->getDBRates();

        if(!is_array($rates)){
            return null;
        }

        //Add country flags
        foreach ($rates as $key => $rate) {
            $flag = (file_exists(dirname(__DIR__).'/assets/img/'.$key.'-flag.png')) ? $key : 'no';
            $rates[$key]['icon'] = AppConfig::ROOT_URL .'/assets/img/' .strtolower($flag) . '-flag.png';
        }

        $output = array(
            'type' => Utils::a(self::rateTypesMap(), $rates_type)
            ,'rates' => $rates
        );

        return $output;

    }

	public function getBaseRates(){
      $rd = new RatesData();

      $rates = $rd->getBaseRateData();

      if(!is_array($rates)){
          return null;
      }

      return array(
        'type' => 'BASE RATES',
        'rates' => $rates,
      );
    }

    public function getFXRates(){
      $rd = new RatesData();
      $rates = $rd->getFXRates();
      if(!is_array($rates)){
          return null;
      }

      $collections = array();
      $data = $rates['rates']['forex-rates'];
      foreach($data as $key => $dict){
        $code = strtolower($dict['currency']);
        $flag = (file_exists(dirname(__DIR__).'/assets/img/'.$code.'-flag.png')) ? true : false;
        if($flag){
          $data[$key]['icon'] = AppConfig::ROOT_URL .'/assets/img/' .strtolower($code) . '-flag.png';
        }else{
          $data[$key]['icon'] = null;
        }
      }

      $collections['type'] =$rates['type'];
      $collections['rates'] = $data;
      return $collections;
    }

    public function getInterestRates(){
      $rd = new RatesData();
      $rates = $rd->getInterestRates();
      if(!is_array($rates)){
          return null;
      }
      return $rates;
    }

    public function buildInterestRatesTable(array $data){

      $div = '<br/><h1 style="font-size:4vw;" class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead><tr>';
      // add the columns from the data
      $columns = array_keys($data['rates'][0]);
      foreach($columns as $column){
        $table .= '<th style="font-size:3vw;text-align:center;">'.ucwords(trim($column)).'</th>';
      }
      $table .='</thead>';
      $table .= '<tbody>';
      $count = 0;

      foreach ($data['rates'] as $k => $rates) {
        $table .= '<tr>';
          foreach($rates as $key => $value){
            $table .= '<td style="font-size:3vw;text-align:center;">'.$value.'</td>';
          }
          $table .= '</tr>';
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          $last_refresh_date = date('F j, Y, g:i a', time());
		  $last_refresh_date = null;
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px">
          <small><em>&nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }
      return $div;
    }

    public function buildFXRatesTable(array $data){

      $div = '<br/><h1 style="font-size:3vw;" class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead><tr>';
      // add the columns from the data
      $columns = array_keys($data['rates'][0]);
      foreach($columns as $column){
        if($column === 'icon'){
          continue;
        }

        $table .= '<th style="font-size:5vw;text-align:center;">'.
        ucwords(trim($column)).'</th>';
      }
      $table .='</thead>';
      $table .= '<tbody>';
      $count = 0;

      foreach ($data['rates'] as $k => $rates) {
        $table .= '<tr>';
          foreach($rates as $key => $value){
            if($key === 'icon'){
              continue;
            }

            if($key === 'currency'){
              // if we have the icon show it
              $img = null;
              $img_path = dirname(__DIR__).'/assets/img/'.strtolower($value).'-flag.png';
              if(isset($img_path)){
                $img_uri = AppConfig::ROOT_URL.'/assets/img/'.strtolower(trim($value)).'-flag.png';
                $img = '&nbsp;<img style="with:32px;height:32px;" src="'.$img_uri.'"/>';
              }

              $table .= sprintf('<td style="font-size:5vw;text-align:center;">%s %s</td>', $value, $img);
            }else{
              $table .= '<td style="font-size:5vw;text-align:center;">'.$value.'</td>';
            }
          }
          $table .= '</tr>';
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          // $last_refresh_date = date('F j, Y, g:i a', time());
		  $last_refresh_date = null;
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px">
          <small><em> &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }
      return $div;
    }

    public function buildViewableFXRatesTable(array $data){

      $div = '<br/><h1 style="font-size:4vw;" class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead><tr>';
      // add the columns from the data
      $columns = array_keys($data['rates'][0]);
      foreach($columns as $column){
        if($column === 'icon'){
          continue;
        }

        $table .= '<th style="font-size:5vw;text-align:center;">'.ucwords(trim($column)).'</th>';
      }
      $table .='</thead>';
      $table .= '<tbody>';
      $count = 0;

      foreach ($data['rates'] as $k => $rates) {
        $table .= '<tr>';
          foreach($rates as $key => $value){
            if($key === 'icon'){
              continue;
            }

            if($key === 'currency'){
              // if we have the icon show it
              $img = null;
              $img_path = dirname(__DIR__).'/assets/img/'.strtolower($value).'-flag.png';
              if(isset($img_path)){
                $img_uri = AppConfig::ROOT_URL.'/assets/img/'.strtolower(trim($value)).'-flag.png';
                $img = '&nbsp;<img style="with:32px;height:32px;" src="'.$img_uri.'"/>';
              }

              $table .= sprintf('<td style="font-size:5vw;text-align:center;">%s %s</td>', $value, $img);
            }else{
              $table .= '<td style="font-size:5vw;text-align:center;">'.$value.'</td>';
            }
          }
          $table .= '</tr>';
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          // $last_refresh_date = date('F j, Y, g:i a', time());
		  $last_refresh_date = null;
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px">
          <small><em>; '.$last_refresh_date.'</em></small></p>';
      }
      return $div;
    }

    public function getFixedDepositRates(){
      $rd = new RatesData();
      $rates = $rd->getFixedDepositRates();
      if(!is_array($rates)){
          return null;
      }
      return $rates;
    }

    public function buildFixedDepositRatesTable(array $data){

      $div = '<br/><h1 style="font-size:4vw;" class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead><tr>';
      // add the columns from the data
      $columns = array_keys($data['rates'][0]);
      foreach($columns as $column){
        $table .= '<th style="font-size:3vw;text-align:center;">'.ucwords(trim($column)).'</th>';
      }
      $table .='</thead>';
      $table .= '<tbody>';
      $count = 0;

      foreach ($data['rates'] as $k => $rates) {
        $table .= '<tr>';
          foreach($rates as $key => $value){
            $table .= '<td style="font-size:3vw;text-align:center;">'.$value.'</td>';
          }
          $table .= '</tr>';
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          $last_refresh_date = date('F j, Y, g:i a', time());
		  $last_refresh_date = null;
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px">
          <small><em>&nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }
      return $div;
    }

    public function getDailyIndicativeRates(){
      $rd = new RatesData();
      $rates = $rd->getDailyIndicativeRates();
      if(!is_array($rates)){
          return null;
      }

      return $rates;
    }

    public function getDailyIndicativeRatesUpdated(){
      $rd = new RatesData();
      $rates = $rd->getDailyIndicativeRates();
      if($rates){
        $data = array();
        $collections = $rates['rates'];
        foreach($collections as $entry){
          $new_entry = array();
          foreach($entry as $key => $value){

            switch(strtolower($key)){
              case 'currency':
              // currency name
              $new_entry['currency_name'] = $value;
              break;
              case 'code':
              // the currency code it should be 3 characters
              $code = strtolower(substr($value, 0,3));
              $new_entry['icon'] = AppConfig::ROOT_URL.'/assets/img/'.$code.'-flag.png';
              //$new_entry['code']
              break;
              default:
              // check if we are dealing with buy or sell
              if(preg_match('#(buy)#', $key)){
                $new_entry['buy_rate'] = $value;
              }else{
                $new_entry['sell_rate'] = $value;
              }
            }
          }
          if($new_entry){
            $data[] = $new_entry;
          }
        }

        if($data){
          $rates['rates'] = $data;
        }
      }
      return $rates;
    }
    public function buildDailyIndicativeRatesTable(array $data){

      $div = '<br/><h1 style="font-size:3vw;" class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead><tr>
      <th style="font-size:3vw;text-align:center;" rowspan="2">Currency</th>
      <th style="font-size:3vw;text-align:center;" rowspan="2">Code</th>
      <th style="font-size:3vw;text-align:center;" colspan="2">Remittances</th>
      <th style="font-size:3vw;text-align:center;" colspan="2">Cash Transactions</th>
      </tr>
      <tr>
       <th style="font-size:3vw;text-align:center;">Bank Buy<br />(GHc)</th>
       <th style="font-size:3vw;text-align:center;">Bank Sell<br />(GHc)</th>
       <th style="font-size:3vw;text-align:center;">Bank Buy<br />(GHc)</th>
       <th style="font-size:3vw;text-align:center;">Bank Sell<br />(GHc)</th>
      </tr></thead>
      ';
      // add the columns from the data

      $table .='</thead>';
      $table .= '<tbody>';
      $count = 0;

      foreach ($data['rates'] as $k => $rates) {
        $table .= '<tr>';
         $img = null;
          foreach($rates as $key => $value){
            if(strtolower($key) === 'code'){
              // try to find the img
              $img_path = dirname(__DIR__).'/assets/img/'.strtolower($key).'-flag.png';
              if(file_exists($img_path)){
                $img = '&nbsp;<img width="32px" height="32px" src="'.$img_path.'"/>';
              }
            }

            $table .= sprintf('<td style="font-size:3vw;text-align:center;">%s %s</td>', $value, $img);
          }
          $table .= '</tr>';
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          $last_refresh_date = date('F j, Y, g:i a', time());
		  $last_refresh_date = null;
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px">
          <small><em> &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }
      return $div;
    }

    public function getTransferRates(){

        $rd = new RatesData();

        $rates = $rd->getTransferRatesData();

        if(!is_array($rates)){
            return null;
        }

        //Add country flags
        foreach ($rates as $key => $rate) {
            $flag = (file_exists(dirname(__DIR__).'/assets/img/'.$key.'-flag.png')) ? $key : 'no';
            $rates[$key]['icon'] = AppConfig::ROOT_URL .'/assets/img/' .strtolower($flag) . '-flag.png';
        }

        $output = array(
            'type' =>  'FX TRANSFER RATES'
            , 'rates' => $rates
        );

        return $output;

    }

    public function getDeposit(){
      $rd = new RatesData();

      $rates = $rd->getDepositRateData();

      if(!is_array($rates)){
          return null;
      }

      return array(
        'type' => 'FIXED DEPOSIT RATES',
        'rates' => $rates,
      );
    }

    public function getGovermentTreasury(){
      $rd = new RatesData();

      $rates = $rd->getGovTreasuryRatesData();

      if(!is_array($rates)){
          return null;
      }

      return array(
        'type' => 'GOG TREASURY RATES',
        'rates' => $rates,
      );
    }

    public static function buildGovermentTreasuryRateTable(array $data){

      $div = '<br/><h1 class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead>
      <tr>
      <td style="font-size:35px;">TENOR</th>
      <td style="font-size:35px;">RATES</th>
      </tr>
      </thead>';
      $table .= '<tbody>';

      $count = 0;

      foreach ($data['rates'] as $k => $rate) {
          $count++;
          $icon = '';
          $table .= sprintf(
          '<tr>
          <td style="font-size:35px;">%s</td>
          <td style="font-size:35px;">%s</td>
          </tr>'
          , trim($rate['tenor'],'"')
          , $rate['rate']);
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          $last_refresh_date = date('F j, Y, g:i a', time());
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px"><small><em>Last refreshed: &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }

      return $div;

    }

    public static function buildDepositRateTable(array $data){

      $div = '<br/><h1 class="text-center">'.$data['type'].'</h1><hr />';
      $div .= '<div>';
      $table = '<table class="table table-stripped">';
      $table .= '<thead>
      <tr>
      <th>Range</th>
      <th>30 DAYS</th>
      <th>90 DAYS</th>
      <th>180 DAYS</th>
      <th>12 MONTHS</th>
      </tr>
      </thead>';
      $table .= '<tbody>';

      $count = 0;

      foreach ($data['rates'] as $k => $rate) {
          $count++;
          $icon = '';
          $table .= sprintf(
          '<tr>
          <td style="font-size:23px;">%s</td>
          <td style="font-size:23px;">%s</td>
          <td style="font-size:23px;">%s</td>
          <td style="font-size:23px;">%s</td>
          <td style="font-size:23px;">%s</td>
          </tr>'
          , trim($rate['range'],'"')
          , $rate['30days']
          , $rate['90days']
          , $rate['180days']
          , $rate['12months'] );
      }

      $table .= '</tbody>';
      $table .= '</table>';
      $div .= $table . '</div>';

      if(strlen($div)){
          $last_refresh_date = date('F j, Y, g:i a', time());
          $div .= '<div class="clearfix"></div>';
          $div .= '<p class="text-right padding-top-10px"><small><em>Last refreshed: &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
      }

      return $div;

    }

    public static function buildTransferRatesTable($data){

        $div = '<br/><h1 class="text-center">'.$data['type'].'</h1><hr />';
        $div .= '<div>';
        $table = '<table class="table table-stripped">';
        $table .= '<thead><tr><th></th><td style="font-size:35px;">Buying</th><td style="font-size:35px;">Selling</th></tr></thead>';
        $table .= '<tbody>';

        $count = 0;

        foreach ($data['rates'] as $k => $rate) {
            $count++;
            $icon = '';

            if (strlen($rate['icon'])) {
                $icon = '<img src="' . $rate['icon'] . '" /> ';
            }

            $table .= sprintf('<tr><td style="font-size:35px;">%s %s</td><td style="font-size:35px;">%.4f</td><td style="font-size:35px;">%.4f</td></tr>', $icon, $rate['currency_code'], $rate['buy_rate'], $rate['sell_rate'] );
        }

        $table .= '</tbody>';
        $table .= '</table>';
        $div .= $table . '</div>';

        if(strlen($div)){
            $last_refresh_date = date('F j, Y, g:i a', time());
            $div .= '<div class="clearfix"></div>';
            $div .= '<p class="text-right padding-top-10px"><small><em>Last refreshed: &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
        }

        return $div;

    }

    public static function buildRatesTable($data){

        $div = '<h1 class="text-center">'.$data['type'].'</h1><hr />';
        $div .= '<div>';
        $table = '<table class="table table-stripped">';
        $table .= '<thead><tr><th></th><td style="font-size:35px;">Buying</th><td style="font-size:35px;">Selling</th></tr></thead>';
        $table .= '<tbody>';

        $count = 0;

        foreach ($data['rates'] as $k => $rate) {
            $count++;
            $icon = '';

            if (strlen($rate['icon'])) {
                $icon = '<img src="' . $rate['icon'] . '" /> ';
            }

            $table .= sprintf('<tr><td style="font-size:35px;">%s %s</td><td style="font-size:35px;">%.4f</td><td style="font-size:35px;">%.4f</td></tr>', $icon, $rate['currency_name'], $rate['buy_rate'], $rate['sell_rate'] );
        }

        $table .= '</tbody>';
        $table .= '</table>';
        $div .= $table . '</div>';

        if(strlen($div)){
            $last_refresh_date = date('F j, Y, g:i a', time());
            $div .= '<div class="clearfix"></div>';
            $div .= '<p class="text-right padding-top-10px"><small><em>Last refreshed: &nbsp;&nbsp; '.$last_refresh_date.'</em></small></p>';
        }

        return $div;

    }

    public static function buildNewRatesMarquee($data){

        $line = '';
        $count = 0;
        // $line = '<div style="text-align:center;">Fidelity Bank - Believe with us</div>';
        // return $line;
         //if they don't want the scrolling rates, uncomment this these two  lines above
        foreach ($data['rates'] as $k => $rate) {
            $count++;

            if (strlen($rate['icon'])) {
                $line .= sprintf('<img src="%s" /> ', $rate['icon'] );
            }

            $line .= sprintf(
			'<span style="color:#000;">%s </span>
			<i class="icon-caret-right"></i> BUYING: %.4f SELLING: %.4f'
			, $rate['currency']
			, $rate['buying']
			, $rate['selling'] );

            if ($count !== sizeof($data['rates'])) {
                $line .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            }

        }

        $line = ' <div style="padding:3px;color:#000;" id="marquee"><p class="marquee" scrollamount = "3">' . $line . '</p></div>';
        $line =  '<span id="rates_type">' . $data['type'] . ':</span>' . $line;

        return $line;

    }

    public static function buildRatesMarquee($data){

        $line = '';
        $count = 0;
        foreach ($data['rates'] as $k => $rate) {
            $count++;

            if (strlen($rate['icon'])) {
                $line .= sprintf('<img src="%s" /> ', $rate['icon'] );
            }

            $line .= sprintf(
			'<span>%s </span>
			<i class="icon-caret-right"></i> BUYING: %.4f SELLING: %.4f'
			, $rate['currency_name']
			, $rate['buy_rate']
			, $rate['sell_rate'] );

            if ($count !== sizeof($data['rates'])) {
                $line .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            }

        }

        $line = ' <div style="padding:3px;" id="marquee"><p class="marquee" scrollamount = "3">' . $line . '</p></div>';
        $line =  '<span id="rates_type">' . $data['type'] . ':</span>' . $line;

        return $line;

    }

    public static function buildInterestRatesMarquee($data){

        $line = '';
        $count = 0;
        $rates_title = 'Interest Rates';
        $ul_text = 'Above 5M';
        $ll_text = 'Below 5M';

        foreach ($data as $k => $rate) {
            $count++;
            $line .= sprintf('<span>%s -> </span> <i class="icon-caret-right"></i> %s: %.2f %s: %.2f', $rate['account_name'], $ul_text, $rate['upper_limit_rate'], $ll_text, $rate['lower_limit_rate'] );

            if ($count !== sizeof($data)) {
                $line .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            }
        }

        $line = ' <div id="marquee"><p class="marquee" scrollamount = "3">' . $line . '</p></div>';
        $line =  sprintf('<span id="rates_type">%s: </span>', $rates_title) . $line;

        return $line;

    }

    public static function buildRatesTabs($rates){
        $tabs = '<div class="tabbable tabbable-custom">';

        # build tabs
        $tabs .= '<ul class="nav nav-tabs">';
        $count = 0;
        $style= null;

        foreach($rates as $key => $rate){

			if($count === 0){
				$tabs .= sprintf('<li class="pulsate %s">
                               <a style="color:#4cf143;" href="#tab_%s" data-toggle="tab">
                                 %s
                               </a>
                             </li>', ($count == 0) ? 'active': '', $key, $rate['title']);
			}else{
            $tabs .= sprintf('<li class="pulsate %s">
                               <a href="#tab_%s" data-toggle="tab">
                                 %s
                               </a>
                             </li>', ($count == 0) ? 'active': '', $key, $rate['title']);
			}
            $count++;
        }
        $tabs .= '</ul>';

        # build pages
        $count = 0;
        $tabs .= '<div class="tab-content">';
        foreach($rates as $key => $rate){
            $tabs .=  sprintf('<div class="tab-pane %s" id="tab_%s">%s</div>', ($count == 0) ? 'active': '', $key, $rate['content']);
            $count++;
        }
        $tabs .= '</div>';

        $tabs .= '</div>';

        return $tabs;

    }

}

?>
