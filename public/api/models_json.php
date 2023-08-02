<?php

include_once "CLDC_class.php";
include_once "calc_library.php";
	
	$c= new CLDC();

// --- Check minimum parameters	
		
		
// --- Filter parameter
	if(isset($_GET['indoor']))
	{
			$indoor = trim(strtoupper($_GET['indoor']));
			if(!($indoor === 'I'  || $indoor === 'O'))
			{
				echo safe_json_encode('Invalid data : '.print_var_name($indoor).' = '.$indoor);
				die();
			}			
	}	
	else 
	{
		echo safe_json_encode('Incomplete data : indoor variable missing.');
		die();
	}



	if(isset($_GET['ex1']))
	{
			$ex1 = trim(strtoupper($_GET['ex1']));
			if(!($ex1 === 'LT'  || $ex1 === 'EN' ))
			{
				echo safe_json_encode('Invalid data : '.print_var_name($ex1).' = '.$ex1);
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: ex1 variable missing.');
		die();
	}


	if(isset($_GET['ex2']))
	{
			$ex2 = trim(strtoupper($_GET['ex2']));
			if(!($ex2 === 'CF'  || $ex2 === 'RT' ))
			{
				echo safe_json_encode('Invalid data : '.print_var_name($ex2).' = '.$ex2);
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: ex2 variable missing.');
		die();
	}

	if(isset($_GET['layout']))
	{
			$layout = trim(strtoupper($_GET['layout']));
			if(!($layout === 'C'  || $layout === 'D' ))
			{
				echo safe_json_encode('Invalid data : '.print_var_name($layout).' = '.$layout);
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: layout variable missing.');
		die();
	}

// --- Calc parameter

	if(isset($_GET['airflow']))
	{
			$airflow = floatval(trim($_GET['airflow']));
			if($airflow<=0)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($airflow).' must be greater than 0');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: airflow variable missing.');
		die();
	}
	

	if(isset($_GET['pressure']))
	{
			$pressure = floatval(trim($_GET['pressure']));
			if($pressure<=0)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($pressure).' must be greater than 0');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: pressure variable missing.');
		die();
	}


	if(isset($_GET['Trin']))
	{
			$Trin = floatval(trim($_GET['Trin']));
			if($Trin<-20 || $Trin>40)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($Trin).' must be greater than or equal to -20 and less than or equal to 40');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: Trin variable missing.');
		die();
	}


	if(isset($_GET['Hrin']))
	{
			$Hrin = floatval(trim($_GET['Hrin']));
			if($Hrin<5 || $Hrin>98)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($Hrin).' must be greater than or equal to 5 and less than or equal to 98');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: Hrin variable missing.');
		die();
	}

	if(isset($_GET['Tfin']))
	{
			$Tfin = floatval(trim($_GET['Tfin']));
			if($Tfin<-20 || $Tfin>40)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($Tfin).' must be greater than or equal to -20 and less than or equal to 40');
				die();
			}
			if($Tfin == $Trin)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($Tfin).' must be different from Trin');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: Tfin variable missing.');
		die();
	}


	if(isset($_GET['Hfin']))
	{
			$Hfin = floatval(trim($_GET['Hfin']));
			if($Hfin<5 || $Hfin>98)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($Hfin).' must be greater than or equal to 5 and less than or equal to 98');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: Hfin variable missing.');
		die();
	}


// --- Filter data


// --- Filter serie
	$serie = $c->serie(1);
	$filter = array();
	
	for($i = 0; $i < count($serie); $i++)
	{
		$feature = explode('|', $serie[$i]['feature']);
		
		if(in_array($indoor, $feature))
			if (in_array($ex1, $feature))
				if (in_array($ex2, $feature))
					if (in_array($layout, $feature))
						array_push($filter, $serie[$i]);
	}
	


// --- Filter models
	$modelli = array();
		
	foreach ($filter as $fil)
	{
		
		$models = $c->modelli($fil['id']);
		
		foreach ($models as $mod)
		{
			if( $mod['code']!=='ACC' && $mod['code']!=='IOM3')
				array_push($modelli, $mod['id']);				
		}
	
	}





// --- Calculation


	$airflow;
	$pressure;
	
	$data =array();
	
	$efficiency=array();
	$airflows = array();
	$pressures = array();
	$powers = array();
	
	$reg = 100;
	
	
//--------	
	
	
foreach ($modelli as $k)	
{
	
	$found = $c->data_model($k);
	$modello = $found[0];
	
//--------	Stampa di debug
	// if (empty($modello))
		// return;
	// else
		// print_r($modello);
//--------	


	$airflows = array_filter(explode('|',$modello['Airflows']));
	$pressures = array_filter(explode('|',$modello['Pressures']));
	$powers = array_filter(explode('|',$modello['Powers']));


	foreach ($airflows as &$val)
				$val = round(tofloat($val),1);
			
	foreach ($pressures as &$val)
				$val = round(tofloat($val),1);
				
	foreach ($powers as &$val)
				$val = round(tofloat($val),1);
				
				
				
	if($airflows[0] > $airflows[1] )
	{
		$airflows = array_reverse($airflows);
		$pressures = array_reverse($pressures);
		$powers = array_reverse($powers);
	}			

	$search = searchwp($airflows, $pressures, $powers, $airflow, $pressure, 69);
 
	if($search)
	{


		$data[$modello['Code']]['id']=$modello['Id'];
		$data[$modello['Code']]['Airflows']=$search[0];
		$data[$modello['Code']]['Pressures']=$search[1];
		$data[$modello['Code']]['Powers']=$search[2];
		$data[$modello['Code']]['Reg']=$search[3];
		$data[$modello['Code']]['Airflow']=$airflow;
		$data[$modello['Code']]['Pressure']=$search[4];
		$data[$modello['Code']]['Power']=$search[5];
		$data[$modello['Code']]['Unit-SEL']=round(2*$search[5]*1000/($airflow),0);
		
		//--------	Calcolo il vettore di efficienza
		foreach ($data[$modello['Code']]['Airflows'] as $val)
				{
					if($val <= 0)
						$val = 1;
					$termo = termo_calc($Trin,$Hrin/100,$Tfin,$Hfin/100,$val,$modello['ModRec'],$modello['LenRec']);
					$efficiency[]=round($termo['efficiency']*100,1);
				}	
		//--------	
			
		
		$data[$modello['Code']]['Efficiency']=round(tofloat(I($airflow,$data[$modello['Code']]['Airflows'],$efficiency)),1);
		
		
		//--------	Calcolo la tabella del suono
		$sound_outlet = array();
		$sound_inlet = array();
		//prelevo i dati sonori (inlet)
		array_push($sound_inlet, $modello['SoundData_Inlet_63hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_125hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_250hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_500hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_1000hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_2000hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_4000hz']);
		array_push($sound_inlet, $modello['SoundData_Inlet_8000hz']);
		//prelevo i dati sonori (outlet)
		array_push($sound_outlet, $modello['SoundData_Outlet_63hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_125hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_250hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_500hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_1000hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_2000hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_4000hz']);
		array_push($sound_outlet, $modello['SoundData_Outlet_8000hz']);
		
		
		$soundtable = sounddata($sound_inlet, $sound_outlet, $modello['Name'], $modello['Dimension_A_Hor'],$modello['Dimension_B_Hor'],$modello['Dimension_C_Hor'], max($airflows),max($pressures),$airflow,$data[$modello['Code']]['Pressure'], $reg);

		$data[$modello['Code']]['Lw']=$soundtable['Breakout']['Lw'];
		$data[$modello['Code']]['Lp30']=$soundtable['Breakout']['Lp30'];
		
		
		
		unset($data[$modello['Code']]['Airflows']);
		unset($data[$modello['Code']]['Pressures']);
		unset($data[$modello['Code']]['Powers']);
		

	}
		

//--------	



//la curva di potenza non serve e quindi non la riadattiamo.

	

	// $soundtable = sounddata($sound_inlet, $sound_outlet, $modello['Name'], $modello['Dimension_A_Ver'],$modello['Dimension_B_Ver'],$modello['Dimension_C_Ver'], max($airflows),max($pressures),$modello['NominalAirflow'],$NominalPressure);

}

//--------		

//--- Test before json
	$test = array( "indoor" => $indoor
				  ,"ex1" => $ex1
				  ,"ex2" => $ex2
				  ,"layout" => $layout
				  ,"airflow" => $airflow
				  ,"pressure" => $pressure
				  ,"Trin" => $Trin
				  ,"Hrin" => $Hrin
				  ,"Tfin" => $Tfin
				  ,"Hfin" => $Hfin
				 );
//--- end test


	if (empty($data))
		$data='Empty';
	
	header('Content-Type: application/json');
	$json1 = safe_json_encode($data);
	$json = safe_json_encode($test);
	//echo $json;	
	echo $json1;	
	
?>

