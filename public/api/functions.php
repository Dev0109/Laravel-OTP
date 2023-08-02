<?php

	// -------------------------------------------------------------------------	
	// Libreria di funzioni PHP
	//   
	// Compatibile PHP 7
	// 
	// Autore : Nicola Morganti
	// -------------------------------------------------------------------------


	// ricerca multimensionale nell'array
	function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		
		return false;
	}
	
	// Esporta in file csv
	function csv_export($data, $filename)
	{
		
		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=".$filename.".csv"); 
		fputcsv($output, array_keys($data[0]), ';');
		foreach($data as $row) {
			fputcsv($output, $row, ';');
		}
		fclose($output) or die("Can't close php://output");
		
	}
	
	
	// -------------------------------------------------------------------------	
	// Funzione per eliminare una o  più colonne da un array utilizzando le keys
	//   
	// Esempio delete_col($array, 'val6')
	// Esempio delete_col($array, array('val1', 'val3', 'val4'))
	//
	// -------------------------------------------------------------------------
	
	function delete_col(&$array, $keys) {
	
	if (is_array($keys))
	{
		foreach ($keys as $key)
		{
			array_walk($array, function (&$v) use ($key) {
			unset($v[$key]);
			});
		}
		return;
	}
	else
	{
		return array_walk($array, function (&$v) use ($keys) {
        unset($v[$keys]);
    });}
	}
	

	// -------------------------------------------------------------------------	
	// Funzione per eliminare una o  più righe da un array utilizzando uno o più valori
	//   
	// Esempio delete_row($array, 'val6')
	// Esempio delete_row($array, array('val1', 'val3', 'val4'))
	//
	// -------------------------------------------------------------------------

	function delete_row(&$array, $keys) {
	
	if (is_array($keys))
	{
		foreach ($keys as $key)
		{

		for($i = 0; $i < count($array); $i++)
		{
		 if(is_array($array[$i]))
		  {
		  if (in_array($key, $array[$i]))
			  unset($array[$i]);
		  }
		  
		}
		
		
		}
		return;
	}
	else
	{
		for($i = 0; $i < count($array); $i++)
		{
		  if (in_array($keys, $array[$i]))
			  unset($array[$i]);
		}
		return;
		  
    }
	
	
	}

	// Formattazione di una tabella a doppia entrata righe--colonne
	
	
	function format_multitable ($tableS8)
	{
	
	if (empty($tableS8))
		{
			echo "Tabella Vuota";
			return;
		}
	
	$ColumnHeaders = array_keys($tableS8);
	
	$RowHeaders = array_keys($tableS8[$ColumnHeaders[0]]);
	$htmltable = '<th></th>';
		
	sort($ColumnHeaders);
	
	echo '<table id="myTable3" class="table table-striped table-bordered">';
	echo '<thead><tr>';
	
	foreach ($ColumnHeaders as $header)
		{
			$htmltable .= '<th>' . $header . '</th>';
		}
	
	echo $htmltable;
	
	echo '</tr></thead>';
	
	$htmltable = '<tr>';

	foreach ($RowHeaders as $Rheader)
		{
			$htmltable = '<tr>';
			$htmltable .= '<td><b>' . $Rheader . '</b></td>';
			
			foreach ($ColumnHeaders as $Cheader)
				{
				$htmltable .= '<td>' .$tableS8[$Cheader][$Rheader] . '</td>';
				}
			$htmltable .=  '</tr>';
			echo $htmltable;
		}

	echo '</table>';
	
	}

	// restituisce primo e ultimo giorno della settimana di calendario
	// Input settimana e anno
	function getStartAndEndDate($week, $year) {
		  $dto = new DateTime();
		  $dto->setISODate($year, $week);
		  $ret['week_start'] = $dto->format('Y-m-d');
		  $dto->modify('+7 days');
		  $ret['week_end'] = $dto->format('Y-m-d');
		  return $ret;
		}


	// creazione di un json pulito con utf8
	
	function safe_json_encode($value){
		if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
			$encoded = json_encode($value, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
		} else {
			$encoded = json_encode($value);
		}
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = utf8ize($value);
				return safe_json_encode($clean);
			default:
				return 'Unknown error'; // or trigger_error() or throw new 
		Exception();
		}
	}

	function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}


   // return variable name
   // If you have same value, return the first one.
 
	function print_var_name($var) {
		foreach($GLOBALS as $var_name => $value) {
			if ($value === $var) {
				return $var_name;
			}
		}

		return false;
	}

?>
	
		