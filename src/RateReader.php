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

function parse_rate_table($content){

  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($content);
  libxml_use_internal_errors(false);

  $data = array();
  $nodes = $dom->getElementsByTagName('table');

  $types = array(
    'Interest Rates',
    'Daily Indicative Exchange Rates',
    'Exchange Rates',
  );

  if($nodes->length > 0){
    $node_index = 0;

    foreach($nodes as $child){
      $class = $child->getAttribute('class');
      $parts = explode(' ', $class, 2);
      if(count($parts) !== 2){
        continue;
      }

      $left_part = trim($parts[0]);
      $trail = trim($parts[count($parts) - 1]);
      if($left_part !== 'table'){
        continue;
      }

      // the first two character must be tb
      if(substr($trail, 0, 2) !== 'tb'){
        continue;
      }

        // process each child table
        // Get the type we are dealing with
        $node_type = $types[$node_index];

        // If we are dealing with those that have headers we process as usual
        // otherwise we process carefully
        switch($node_type){
          case 'Interest Rates':
          case 'Daily Indicative Exchange Rates':
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
          case 'Exchange Rates':
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

  return $data;
}

function remove_accents( $string ) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

    $string = preg_replace('~[^-\w]+~', '', $string);
	//$string = preg_replace('/[\s+]+/', ' ', $string);
	$words = preg_split("/[\s]+/", $string);
	$words = array_map('trim', $words);
	return implode(' ', $words);
}
