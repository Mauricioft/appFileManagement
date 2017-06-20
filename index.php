<?php
 
	$dir = opendir("."); //ruta actual
	while (($path = readdir($dir)) !== false) {//obtenemos un archivo y luego otro sucesivamente
    if (is_dir($path)) {//verificamos si es o no un directorio
	    echo "Directorio: [".$path."]<br />"; //de ser un directorio lo envolvemos entre corchetes
    }else{
    	if(file_exists($path)) {
	      if($path != 'index.php' && getExtension($path) == 'php'){
	      	$arrInfoFile = getInfoFile($path);
	      	// echo "Nombre archivo: $path | Extension: ".getExtension($path)."<br/>";
	      	if(!empty($arrInfoFile)){
	      		for ($i=0; $i < count($arrInfoFile); $i++) { 
							findAndReplace($arrInfoFile[$i], $i, $arrInfoFile);	      			
	      		}
						// GUARDAMOS
						$file = fopen($path, "w");
						foreach( $arrInfoFile as $key) {
							fwrite($file, $key);
						} 
						fclose( $file ); 
	      	}
	      }
			}
    }
	}
  closedir($dir);

  echo "<pre>";
  print_r($arrInfoFile);
  echo "</pre>";

  function getExtension($str) {
    return end(explode(".", $str));
	}

	function getInfoFile($path_){
		$data = array();
	  $file = fopen($path_, 'r');
	  while(!feof($file)) { 
      $name = fgets($file);
      $data[] = $name;
	  }
	  fclose($file);
	  return $data;
	}

	function findAndReplace($value, $key, &$arrInfoFile){
		$pattern = "/mysql_query\b/i";
	  if (preg_match($pattern, substr($value, 3))) {
	  	$valuetag = str_replace('mysql_query', 'mysqli_query', $value);
  		$currentValue = current(explode('mysqli_query', $valuetag));
	  	$cleanChainValue = cleanChain($valuetag);
	  	$currentValue = $currentValue." mysqli_query({$cleanChainValue[1]}, {$cleanChainValue[0]});";
	  	$arrInfoFile[$key] = $currentValue;
    } else {
	    echo "NO HAY COINCIDENCIA <br/>";
	  } 
	}
	
	function cleanChain($text) {
  	$endValue = end(explode('mysqli_query', $text));
  	$valuetag = str_replace('(', '', $endValue);
  	$valuetag = str_replace(')', '', $valuetag);
  	$valuetag = str_replace(';', '', $valuetag);
  	return explode(',', $valuetag);
	} 