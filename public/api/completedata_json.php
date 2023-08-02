<?php
	
	include_once "CLDC_class.php";
	include_once "calc_library.php";
	
	$c= new CLDC();

// --- Check minimum parameters	
// --- Calc parameter


	if(isset($_GET['id']))
	{
		$id = floatval(trim($_GET['id']));
			if($id<=0)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($airflow).' must be greater than 0');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: id variable missing.');
		die();
	}

	if(isset($_GET['Reg']))
	{
		$reg = floatval(trim($_GET['Reg']));
			if($reg<20 || $reg>100)
			{
				echo safe_json_encode('Invalid data : '.print_var_name($airflow).' must be greater than 20 and less than 100');
				die();
			}
	}		
	else 
	{
		echo safe_json_encode('Incomplete data: Reg variable missing.');
		die();
	}


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



// --- Calculation

	$airflow;
	$pressure;
	$reg;
	$id;
	
	$data =array();
	
	$efficiency=array();
	$airflows = array();
	$pressures = array();
	$powers = array();
	
	
//--------	
	
	$found = $c->data_model($id);
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

		tointercept3($airflows, $pressures, $powers);
		
		
		list($new_airflows, $new_pressures, $new_powers) = fanlaws ($airflows, $pressures, $powers, $reg); // ricalcolo i vettori di lavoro
		tointercept3($new_airflows, $new_pressures, $new_powers);
		$pressure = round(I($airflow,$new_airflows,$new_pressures),1); // ricalcolo il punto di lavoro	
		$power = round(I($airflow,$new_airflows,$new_powers),1); // ricalcolo il punto di lavoro	

		list($psfp_af_max, $psfp_max) = psfp($airflows, $powers);
		list($psfp_af, $psfp) = psfp($new_airflows, $new_powers);

		$data[$modello['Code']]['id']=$modello['Id'];
		$data[$modello['Code']]['Max_Airflows']=$airflows;
		$data[$modello['Code']]['Max_Pressures']=$pressures;
		$data[$modello['Code']]['Max_Powers']=$powers;
		$data[$modello['Code']]['Max_PSFP']=$psfp_max;
		$data[$modello['Code']]['Max_PSFP_af']=$psfp_af_max;
		$data[$modello['Code']]['Regulate_Airflows']=$new_airflows;
		$data[$modello['Code']]['Regulate_Pressures']=$new_pressures;
		$data[$modello['Code']]['Regulate_Powers']=$new_powers;
		$data[$modello['Code']]['Regulate_PSFP']=$psfp;
		$data[$modello['Code']]['Regulate_PSFP_af']=$psfp_af;
		$data[$modello['Code']]['Reg']=$reg;
		$data[$modello['Code']]['Airflow']=$airflow;
		$data[$modello['Code']]['Pressure']=$pressure;
		$data[$modello['Code']]['Power']=$power;
		$data[$modello['Code']]['Unit_SEL']=round(2*$power/($airflow/3600),0);
		$data[$modello['Code']]['PSFP']=round($power/($airflow/3600),0);
		
		$data[$modello['Code']]['IND_VarHor_Ceiling_Img']=$modello['IND_VarHor_Ceiling_Img'];
	
		
		for ($i = 0; $i < count($data[$modello['Code']]['Max_Airflows']); $i++ )
		{
			if ($data[$modello['Code']]['Max_Airflows'][$i] <= 0)
				$value = 1;
			else
				$value = $data[$modello['Code']]['Max_Airflows'][$i];
			$termo = termo_calc($Trin,$Hrin/100,$Tfin,$Hfin/100,$value,$modello['ModRec'],$modello['LenRec']);
			array_push($efficiency, round($termo['efficiency']*100,1));
			
		}
		

		
		
		
		//--------	Calcolo dati termodinamici	
		$termo = termo_calc($Trin,$Hrin/100,$Tfin,$Hfin/100,$airflow,$modello['ModRec'],$modello['LenRec']);

		$termo["Supply_outlet_temp"] = round($termo["Supply_outlet_temp"],1);
        $termo["Supply_outlet_rh"] = round($termo["Supply_outlet_rh"]*100,0);
        $termo["Exhaust_outlet_temp"] = round($termo["Exhaust_outlet_temp"],1);
        $termo["Exhaust_outlet_rh"] = round($termo["Exhaust_outlet_rh"]*100,0);
        $termo["water_produced"] = round($termo["water_produced"],2);
        $termo["Return_inlet_temp"] = round($termo["Return_inlet_temp"],1);
        $termo["Return_inlet_rh"] = round($termo["Return_inlet_rh"]*100,0);
        $termo["Fresh_inlet_temp"] = round($termo["Fresh_inlet_temp"],1);
        $termo["Fresh_inlet_rh"] = round($termo["Fresh_inlet_rh"]*100,0);
        $termo["efficiency"] = round($termo['efficiency']*100,1);
        $termo["heat_recovery"] = round($termo['heat_recovery'],0);
        $termo["sensible_heat"] = round($termo['sensible_heat'],0);
        $termo["latent_heat"] = round($termo['latent_heat'],0);
		
		//--------	

		$data[$modello['Code']]['ThermodynamicData'] = $termo;
		$data[$modello['Code']]['ThermodynamicData'] ['Efficiencies']=$efficiency;
		
		
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
		
		
		
		$soundtable = sounddata($sound_inlet, $sound_outlet, $modello['Name'], $modello['Dimension_A_Hor'],$modello['Dimension_B_Hor'],$modello['Dimension_C_Hor'], max($data[$modello['Code']]['Max_Airflows']),max($data[$modello['Code']]['Max_Pressures']),$airflow,$data[$modello['Code']]['Pressure'], $reg);

		$data[$modello['Code']]['Soundtable']=$soundtable;
	




	if (empty($data))
		$data='Empty';
	
	header('Content-Type: application/json');
	$json1 = safe_json_encode($data);
	
	echo $json1;	
	
?>

