<?php
/**
* Do not change anything here, this is for reading Fidelity Bank foreign
* exchange,government bond rates from their website
*
*/
function fetch_fidelity_rates(){

  $uri = 'https://www.fidelitybank.com.gh/rates/';
  $data = array();

  try{

    $contents = file_get_contents($uri);

    if($contents && strlen($contents)){
      $data = parse_rate_table(trim($contents));
    }
  }catch(Exception $ex){
    // ignore
  }

  return $data;
}

function parse_rate_titles($content){
	$dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($content);
  libxml_use_internal_errors(false);

  $data = array();
  $xp = new DOMXPath($dom);
  $nodes = $xp->query('//section[contains(@class, "article-content clearfix")]/h2');
  if($nodes->length > 0){
	  foreach($nodes as $child){
		  $value = (string)$child->nodeValue;
		  if($value && strlen($value)){
			  $data[] = remove_accents($value);
		  }
	  }
  }

  return $data;
}

function parse_rate_table($content){

   $types = parse_rate_titles($content);
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($content);
  libxml_use_internal_errors(false);

  $data = array();
  $xp = new DOMXPath($dom);
  $nodes = $xp->query('//div[contains(@class, "table-responsive")]/table[contains(@class, "table")]');


  if($nodes->length > 0){
    $node_index = 0;

    foreach($nodes as $child){
      $class = trim((string)$child->getAttribute('class'));

      if($class !== 'table'){
        continue;
      }

        // process each child table
        // Get the type we are dealing with
        $node_type = $types[$node_index];
        // select the first word
        $cts = explode(' ', $node_type);
         $cts = array_filter($cts);
         $node_key = array_shift($cts);
        // If we are dealing with those that have headers we process as usual
        // otherwise we process carefully
        switch(strtolower($node_key)){
          case 'interest':
          case 'fixed':
          // These ones, have headers
          $first_row = true;
          $rows = array();
          $row_header = array();
          $row_index = 0;
          foreach($child->getElementsByTagName('tr') as $tr_node){
            if($first_row){
              // extract the headers
              foreach($tr_node->getElementsByTagName('th') as $th_node){
                $value = strtolower(trim((string) $th_node->nodeValue));
                $value = preg_replace('/[[:^print:]]/', '', $value);
                $row_header[$row_index] = trim(remove_accents($value));

                $row_index++;
              }
              $first_row = false;
              continue;
            }

            // grab the cells
            $cell = array();
            $cell_index = 0;
            foreach($tr_node->getElementsByTagName('td') as $td_node){
              $value = trim((string)$td_node->nodeValue);
              $value = preg_replace('/[[:^print:]]/', '', $value);
              $value = trim($value);

              if($value && strlen($value)){
                if(array_key_exists($cell_index, $row_header)){
                  $cell[$row_header[$cell_index]] = remove_accents($value);
                  $cell_index++;
                }else{
                  // the we do not have this index in the header
                  echo 'Unknown index : '.$cell_index.' for value :'.$value;
                  continue;
                }
              }
            }

            $rows[] = $cell;
          }
          $data[$node_type] = $rows;
          break;
          case 'daily':
          $first_row = true;
          $second_row = false;
          $rows = array();
          $row_header = array();
          $row_index = 0;
          foreach($child->getElementsByTagName('tr') as $tr_node){
            if($first_row){
              // extract the headers
              if($second_row){
                // Here the first two cells are classified as remittance
                // why the remaining last are cash_transactions
                $marker = 0;
                $callsign = 'remittance';
                foreach($tr_node->getElementsByTagName('th') as $th_node){
                  $value = strtolower(trim((string) $th_node->nodeValue));
                  $value = preg_replace('/[[:^print:]]/', '', $value);

                  $row_header[$row_index] = $callsign.'_'.remove_accents($value);
                  $row_index++;
                  $marker++;

                  if($marker >= 2){
                    $callsign = 'cash transaction';
                  }
                }
                $first_row = false;
              }else{
                // pick out the first two columns
                $marker = 0;
                foreach($tr_node->getElementsByTagName('th') as $th_node){
					$value = strtolower(trim((string) $th_node->nodeValue));
                  $row_header[$row_index] = remove_accents($value);
                  $row_index++;
                  $marker++;
                  if($marker > 1){
                    break;
                  }
                }
              }
              $second_row = true;
              continue;
            }

            // grab the cells
            $cell = array();
            $cell_index = 0;
            foreach($tr_node->getElementsByTagName('td') as $td_node){
              $value = trim((string)$td_node->nodeValue);
              $value = preg_replace('/[[:^print:]]/', '', $value);
              if($value && strlen($value)){
                if(array_key_exists($cell_index, $row_header)){
                  $cell[$row_header[$cell_index]] = remove_accents($value);
                  $cell_index++;
                }else{
                  // the we do not have this index in the header
                  // echo 'Unknown index : '.$cell_index.' for value :'.$value;
                  continue;
                }
              }
            }

            $rows[] = $cell;
          }
          $data[$node_type] = $rows;
          break;
        }

      $node_index++;
    }
  }

  // add the fx rates
  $rates = parse_fxrate_table($content);
  $data['Forex Rates'] = $rates;

  return $data;
}

function parse_fxrate_table($content){

  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($content);
  libxml_use_internal_errors(false);

  $data = array();
  $xp = new DOMXPath($dom);
  $nodes = $xp->query('//div[contains(@class, "module-ct")]/table[contains(@class, "table")]');

  if($nodes->length > 0){
    $node_key = 'forex-rates';
    foreach($nodes as $child){
      $class = trim((string)$child->getAttribute('class'));

        switch(strtolower($node_key)){
          case 'forex-rates':
          // These ones, have headers
          $first_row = true;
          $rows = array();
          $row_header = array();
          $row_index = 0;
          foreach($child->getElementsByTagName('tr') as $tr_node){
            if($first_row){
              // extract the headers
              foreach($tr_node->getElementsByTagName('th') as $th_node){
                $value = strtolower(trim((string) $th_node->nodeValue));
                $value = preg_replace('/[[:^print:]]/', '', $value);
                $row_header[$row_index] = trim(remove_accents($value));

                $row_index++;
              }
              $first_row = false;
              continue;
            }

            // grab the cells
            $cell = array();
            $cell_index = 0;
            foreach($tr_node->getElementsByTagName('td') as $td_node){
              $value = trim((string)$td_node->nodeValue);
              $value = preg_replace('/[[:^print:]]/', '', $value);
              $value = trim($value);

              if($value && strlen($value)){
                if(array_key_exists($cell_index, $row_header)){
                  $cell[$row_header[$cell_index]] = remove_accents($value);
                  $cell_index++;
                }else{
                  // the we do not have this index in the header
                  echo 'Unknown index : '.$cell_index.' for value :'.$value;
                  continue;
                }
              }
            }

            $rows[] = $cell;
          }
          $data['forex-rates'] = $rows;
          break;
        }
    }
  }

  return $data;
}

function remove_accents( $string ) {

	$string = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $string);
	$words = preg_split("/[\s]+/", $string);
	$words = array_map('trim', $words);
	return trim(implode(' ', $words));

}
