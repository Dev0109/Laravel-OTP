<?php
	
	include_once  (__DIR__ ."/../../vendor/autoload.php");

    use MathPHP\NumericalAnalysis\Interpolation;

	
function tofloat($num) {  //rendo float qualsiasi formato di numero indipendentemente che si usi la , o il . come delimitatore decimale
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
  
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}

function tointercept(&$x, &$y){ //Controllo le interecette agli assi dei dati se non ci sono proietto lineramente e inserisco il valore
	
	if (reset($x)>=1)
	{
		$y0 =$y[0] - $x[0]*($y[1]-$y[0])/($x[1]-$x[0]);
		array_unshift($x, 0);	
		array_unshift($y, round($y0,1));	
	}
	
	if (end($y)>1)
	{
		$len = count($y)-1;
		$x0 = $x[$len-1] - $y[$len-1]*($x[$len]-$x[$len-1])/($y[$len]-$y[$len-1]);
		array_push($x, round($x0,1));
		array_push($y, 0);
		
	}
}

function tointercept3(&$x, &$y, &$z){ //Controllo le interecette agli assi dei dati se non ci sono proietto lineramente e inserisco il valore
	
	if (reset($x)>=1)
	{
		$y0 =$y[0] - $x[0]*($y[1]-$y[0])/($x[1]-$x[0]);
		$z0 =$z[0] - $x[0]*($z[1]-$z[0])/($x[1]-$x[0]);
		array_unshift($x, 0);	
		array_unshift($y, round($y0,1));
		array_unshift($z, round($z0,1));
	}
	
	if (end($y)>1)
	{
		$len = count($x)-1;
		$x0 = $x[$len-1] - $y[$len-1]*($x[$len]-$x[$len-1])/($y[$len]-$y[$len-1]);
		
		$z0 = $z[$len-1] +($x0- $x[$len-1])*($z[$len]-$z[$len-1])/($x[$len]-$x[$len-1]);
		
		array_push($x, round($x0,1));
		array_push($z, round($z0,1));
		array_push($y, 0);
		
	}
}




function I($x,$xi,$yi) {  //Interpolo la funzione e trovo il punto di lavoro

  $N = count($xi);
  
  // creo le coppie di punti
  $points = array();
  
   for ($k=0; $k<$N; $k++) {
		
		array_push( $points, array($xi[$k], $yi[$k]));
   
   }
   
    // Interpolazione Cubica
	
	$p = Interpolation\NaturalCubicSpline::interpolate($points); 
	
	if (is_array($x))
	{
		$Nx = count($x);
		$y = array();
		for ($k=0; $k<$Nx; $k++) {
		  array_push($y,  round($p($x[$k]),1)  );
		}
	}
	else
		$y = round($p($x),1);
	
	return $y; 
  
}


// Funzioni per calcolare i valori psicrometrici dell'aria umida
// Partendo da temperatura e umidità relativa

   Function PsychroCalc($t,$rh) {

        $calcolato=array();
        $p = 101325; //J/kgK

        $tk = $t + 273.15;
		
        $c1 = -5674.5359;
        $c2 = 6.3925247;
        $c3 = -0.009677843;
        $c4 = 0.00000062215701;
        $c5 = 0.0000000020747825;
        $c6 = -0.0000000000009484024;
        $c7 = 4.1635019;
        $c8 = -5800.2206;
        $c9 = 1.3914993;
        $c10= -0.048640239;
        $c11= 0.000041764768;
        $c12= -0.000000014452093;
        $c13= 6.5459673;
        $c14= 6.54;
        $c15= 14.526;
        $c16= 0.7389;
        $c17= 0.09486;
        $c18= 0.4569;

        $calcolato['rh'] = $rh;

        //calcolo la pressione vapore alla saturazione in Pascal

        if ($t < 0)
            $calcolato['pwsat'] = exp($c1 / $tk + $c2 + $c3 * $tk + $c4 * $tk ** 2 + $c5 * $tk ** 3 + $c6 * $tk ** 4 + $c7 * log($tk));
        else
            $calcolato['pwsat'] = exp($c8 / $tk + $c9 + $c10 * $tk + $c11 * $tk ** 2 + $c12 * $tk ** 3 + $c13 * log($tk));


        //calcolo la pressione vapore in Pascal

        $calcolato['pw'] = $rh * $calcolato['pwsat'];

        //calcolo l'umidita' assoluta  kg/kg
        $calcolato['w']= 0.62198 * $calcolato['pw'] / (101325 - $calcolato['pw']);

        //calcolo l'umidita' assoluta di saturazione in kg/kg
        $calcolato['wsat'] = 0.62198 * $calcolato['pwsat'] / (101325 - $calcolato['pwsat']);

        //calcolo  grado di saturazione

        $calcolato['u'] = $calcolato['w'] / $calcolato['wsat'];

        //calcolo il volume specifico in m3/h

        $calcolato['v'] = 0.2871 * $tk * (1 + 1.6078 * $calcolato['w']) / $p;

        //calcolo l'entalpia

        $calcolato['h'] = 1.006 * $t + $calcolato['w'] * (2501 + 1.805 * $t);

        //calcolo la temperatura di rugiada
        $alpha =log($calcolato['pw'] / 1000);

        if ($t > 0) 
            $calcolato['tdew'] = $c14 + $c15 * $alpha + $c16 * $alpha ** 2 + $c17 * $alpha ** 3 + $c18 * ($calcolato['pw'] / 1000) ** 0.1984;
        else
            $calcolato['tdew'] = 6.09 + 12.608 * $alpha + 0.4959 * $alpha ** 2;

        // calcolo della temperatura di bulbo umido

        $passo = 0.001;


        $calcolato['twb'] = $t;


        do
		{
            $calcolato['twb'] = $calcolato['twb'] - $passo;
            $tkwb = $calcolato['twb'] + 273.15;

            if( $calcolato['twb'] < 0 )
                $pwswet = exp($c1 / $tkwb + $c2 + $c3 * $tkwb + $c4 * $tkwb ** 2 + $c5 * $tkwb ** 3 + $c6 * $tkwb ** 4 + $c7 * log($tkwb));
            Else
                $pwswet =exp($c8 / $tkwb + $c9 + $c10 * $tkwb + $c11 * $tkwb ** 2 + $c12 * $tkwb ** 3 + $c13 * log($tkwb));

            $wswet = 0.62198 * $pwswet / (101325 - $pwswet);

            $err = $calcolato['w'] - (((2501 - 2.381 * $calcolato['twb']) * $wswet + 1.006 * ($calcolato['twb'] - $t)) / (2501 + 1.805 * $t - 4.186 * $calcolato['twb']));


        } while ($err < 0.00003);

        return $calcolato;
		
   }
// Partendo da temperatura e umidità assoluta

    Function PsychroCalcW($t, $w )
	{

        $calcolato=array();
        $p = 101325; //J/kgK

        $tk = $t + 273.15;
		
        $c1 = -5674.5359;
        $c2 = 6.3925247;
        $c3 = -0.009677843;
        $c4 = 0.00000062215701;
        $c5 = 0.0000000020747825;
        $c6 = -0.0000000000009484024;
        $c7 = 4.1635019;
        $c8 = -5800.2206;
        $c9 = 1.3914993;
        $c10= -0.048640239;
        $c11= 0.000041764768;
        $c12= -0.000000014452093;
        $c13= 6.5459673;
        $c14= 6.54;
        $c15= 14.526;
        $c16= 0.7389;
        $c17= 0.09486;
        $c18= 0.4569;

        $calcolato['w'] = $w;

        //calcolo la pressione vapore alla saturazione in Pascal

        if ($t < 0)
            $calcolato['pwsat'] = exp($c1 / $tk + $c2 + $c3 * $tk + $c4 * $tk ** 2 + $c5 * $tk ** 3 + $c6 * $tk ** 4 + $c7 * log($tk));
        else
            $calcolato['pwsat'] = exp($c8 / $tk + $c9 + $c10 * $tk + $c11 * $tk ** 2 + $c12 * $tk ** 3 + $c13 * log($tk));

			
        //calcolo la pressione vapore in Pascal

        $calcolato['pw'] = $calcolato['w'] * 101325 / (0.62198 + $calcolato['w']);

        //calcolo l'umidita' relativa

        $calcolato['rh'] = $calcolato['pw']/ $calcolato['pwsat'];


        //calcolo l'umidita' assoluta di saturazione in kg/kg
        $calcolato['wsat'] = 0.62198 * $calcolato['pwsat'] / (101325 - $calcolato['pwsat']);

        //calcolo  grado di saturazione

        $calcolato['u'] = $calcolato['w'] / $calcolato['wsat'];

        //calcolo il volume specifico in m3/h

        $calcolato['v'] = 0.2871 * $tk * (1 + 1.6078 * $calcolato['w']) / $p;

        //calcolo l'entalpia

        $calcolato['h'] = 1.006 * $t + $calcolato['w'] * (2501 + 1.805 * $t);

       //calcolo la temperatura di rugiada
        $alpha =log($calcolato['pw'] / 1000);

        if ($t > 0) 
            $calcolato['tdew'] = $c14 + $c15 * $alpha + $c16 * $alpha ** 2 + $c17 * $alpha ** 3 + $c18 * ($calcolato['pw'] / 1000) ** 0.1984;
        else
            $calcolato['tdew'] = 6.09 + 12.608 * $alpha + 0.4959 * $alpha ** 2;

        // calcolo della temperatura di bulbo umido


        $passo = 0.001;


        $calcolato['twb'] = $t;


        do
		{
            $calcolato['twb'] = $calcolato['twb'] - $passo;
            $tkwb = $calcolato['twb'] + 273.15;

            if( $calcolato['twb'] < 0 )
                $pwswet = exp($c1 / $tkwb + $c2 + $c3 * $tkwb + $c4 * $tkwb ** 2 + $c5 * $tkwb ** 3 + $c6 * $tkwb ** 4 + $c7 * log($tkwb));
            Else
                $pwswet =exp($c8 / $tkwb + $c9 + $c10 * $tkwb + $c11 * $tkwb ** 2 + $c12 * $tkwb ** 3 + $c13 * log($tkwb));

            $wswet = 0.62198 * $pwswet / (101325 - $pwswet);

            $err = $calcolato['w'] - (((2501 - 2.381 * $calcolato['twb']) * $wswet + 1.006 * ($calcolato['twb'] - $t)) / (2501 + 1.805 * $t - 4.186 * $calcolato['twb']));


        } while ($err < 0.00003);

        return $calcolato;
	}


// Calcolo i vettori di portata, pressione e potenza al variare della regolazione secondo le Fan Laws

function fanlaws ($airflows, $pressures, $powers, $reg)
{
	

	foreach ($airflows as $value) {
		$new_airflows[] = round($value * $reg/100, 1);
	}


	foreach ($pressures as $value) {
		$new_pressures[] = round($value * ($reg/100) * ($reg/100), 1);
	}


	foreach ($powers as $value) {
		$new_powers[] = round($value * ($reg/100) * ($reg/100) * ($reg/100), 1);
	}

	
	//print_r(array($reg, $new_airflows, $new_pressures));
	
	return array($new_airflows, $new_pressures, $new_powers);


}

// Cerco una curva della macchina che soddisfi il punto di lavoro con un livello di regolazione minima di accettabilità, altrimenti restituisco FALSE


function searchwp ($airflows, $pressures, $powers, $af, $pres, $reg_min)
{
	
	// Regolazione al massimo e assegnazione valori iniziali
	$reg = 100;
	$new_airflows = $airflows;
	$new_pressures = $pressures;
	$new_powers =$powers;
	
	tointercept3($new_airflows, $new_pressures, $new_powers);

	
	// Controllo il valore subito il valore di portata e se non soddisfa esco dalla funzione
	if($af > max($airflows))
		return false;
	//print_r($airflows);
	//echo " ".$af;
	// Controllo il valore subito il valore di pressione e se non soddisfa esco dalla funzione
	$check_pressure = round(I($af,$new_airflows,$new_pressures),1);
	
	if($check_pressure < $pres)
		return false;
	
	// Controllo se il massimo di regolazione soddisfa già il requisito
	
	if( ($check_pressure - $pres) <= 5)
	{
		$check_power = round(I($af,$new_airflows,$new_powers),1); // ricalcolo il punto di lavoro	
		return array($new_airflows, $new_pressures, $new_powers, $reg, $check_pressure, $check_power);
	}
	// In caso contrario regolo fintanto che non ottengo quanto cercato
	else
	{
		
		while($reg >= $reg_min && (($check_pressure - $pres) >= 0) )
		{
			$reg--; // calo la regolazione
			list($new_airflows, $new_pressures, $new_powers) = fanlaws ($airflows, $pressures, $powers, $reg); // ricalcolo i vettori di lavoro
			tointercept3($new_airflows, $new_pressures, $new_powers);
			if($af > max($new_airflows))
				break;
			$check_pressure = round(I($af,$new_airflows,$new_pressures),1); // ricalcolo il punto di lavoro			
		}
		
		if ($reg <= $reg_min) // sono sotto la soglia di accettaibilità, scarto l'unità
			return false;
		else
		{
			$reg++; // aumento la regolazione per trovare il primo punto che soddisfa i requisiti
			list($new_airflows, $new_pressures, $new_powers) = fanlaws ($airflows, $pressures, $powers, $reg); // ricalcolo i vettori di lavoro
			tointercept3($new_airflows, $new_pressures, $new_powers);
			$check_pressure = round(I($af,$new_airflows,$new_pressures),1); // ricalcolo il punto di lavoro	
			$check_power = round(I($af,$new_airflows,$new_powers),1); // ricalcolo il punto di lavoro	
			return array($new_airflows, $new_pressures, $new_powers, $reg, $check_pressure, $check_power);
		}
			
	}
		

}






// Calcola il punto di lavoro termodinamico noto modello unità e condizioni termodinamiche

function termo_calc($tin, $hrin, $tout, $hrout, $af, $model, $pl, $onlyeff=false) {

        $calcolato = array();

        //costanti
        $cpa = 1006.45;
        $rhoa = 1.293;
        $pr = 101325;
        $T0 = 273.15;
        $cpm = 1859.74;
        $rhom = 0.8037;
        $hc = 2454111;

        //Calcolo viscosità
        $ztin = ($T0 + $tin - 1400) / 200;
        $ztout = ($T0 + $tout - 1400) / 200;
        $ztstd = ($T0 + 20 - 1400) / 200;
        $vtin = (51.537354 + 4.858258 * $ztin - 0.106514 * $ztin ** 2 - 0.002914 * $ztin ** 3 - 0.00163 * $ztin ** 4 + 0.000448 * $ztin ** 5) * 0.000001;
        $vtout = (51.537354 + 4.858258 * $ztout - 0.106514 * $ztout ** 2 - 0.002914 * $ztout ** 3 - 0.00163 * $ztout ** 4 + 0.000448 * $ztout ** 5) * 0.000001;
        $vtstd = (51.537354 + 4.858258 * $ztstd - 0.106514 * $ztstd ** 2 - 0.002914 * $ztstd ** 3 - 0.00163 * $ztstd ** 4 + 0.000448 * $ztstd ** 5) * 0.000001;

        $vmed = ($vtin + $vtout) / 2;
        $ratiov = $vmed / $vtstd;

        //Calcolo Portate
        $aftin = $af;
        $aftout = $af * ($tout + $T0) / ($tin + $T0);
        $afstd = $af * (20 + $T0) / ($tin + $T0);

        $afmed = ($aftin + $aftout) / 2;

        $ratioaf = $afmed / $afstd;

        //Calcolo lunghezza
        $ratiopl = (0.4 - 0.008) / ($pl - 0.008);

        //Calcolo valori IN
        $mass_dry_air = $rhoa * $af * ($T0 / ($tin + $T0)) / 3600;
        $psatin = 100 * exp(17.433 - 19513.7 * exp(-1.27095 * log($tin + $T0)));
        $ppartin = $hrin * $psatin;
        $tdew_in = exp((1 / 1.27095) * log(19513.7 / (17.433 - log($ppartin / 100)))) - $T0;
        $mass_moisture_in = $mass_dry_air * ($rhom / $rhoa) * ($ppartin) / ($pr - ($ppartin));

        $volume_moisture_in = 3600 * $mass_moisture_in / ($rhom * ($tin + $T0) / $T0);
        $mda = $mass_dry_air * $af / ($volume_moisture_in + $af);
        $mm = $mass_moisture_in * $af / ($volume_moisture_in + $af);
        $massin = $mm + $mda;

        $cpwetin = $cpa * $mda / $massin + $cpm * $mm / $massin;
        $mcp_wet = $cpwetin * $massin;
        $mcp_dry = $cpa * $mass_dry_air;

        $ratio_mcp = $mcp_wet / $mcp_dry;


        //Calcolo valori OUT
        $mass_dry_air_out = $mda;

        $psatout = 100 * exp(17.433 - 19513.7 * exp(-1.27095 * log($tout + $T0)));
        $ppartout = $hrout * $psatout;
        $tdew_out = exp((1 / 1.27095) * log(19513.7 / (17.433 - log($ppartout / 100)))) - $T0;
        $mass_moisture_out = $mass_dry_air_out * ($rhom / $rhoa) * ($ppartout) / ($pr - ($ppartout));

        $volume_moisture_out = 3600 * $mass_moisture_out / ($rhom * ($tout + $T0) / $T0);

        $massout = $mass_moisture_out + $mass_dry_air_out;

        $cpwetout = $cpa * $mass_dry_air_out / $massout + $cpm * $mass_moisture_out / $massout;
        $mcp_wet_out = $cpwetout * $massout;

        $mda_out = $mass_dry_air_out * $mcp_wet / $mcp_wet_out;
        $mm_out = $mass_moisture_out * $mcp_wet / $mcp_wet_out;

        $average_moisture = $volume_moisture_in + $volume_moisture_out;
        $ratio_moist = ($af + $average_moisture) / $af;

        $mf = $ratio_mcp * $ratiopl * $massin;
        $vf = $af * $ratiov * $ratioaf * $ratiopl * $ratio_moist;

        //Caratteristiche RS160
        if (strtoupper($model) == "MODEL 1")
            $ef = 0.97 - (6.10139 * $mf**3 - 4.01818 * $mf**2 + 1.77231 * $mf); // 0.97 - ....corretivo per evitare 100%

      
        //Caratteristiche RS300
        if (strtoupper($model) == "MODEL 2")
            $ef = 0.97 - (5.77875 * $mf**3 - 4.36345 * $mf**2 + 2.0758 * $mf); // ' 0.97 - ....corretivo per evitare 100%

        //Caratteristiche RS220
        if (strtoupper($model) == "MODEL 3")
		{
            $mf = $mf * 82 / 392;
            $vf = $vf * 82 / 392;
            $ef = (0.0017748 * $vf * $vf - 0.455242 * $vf + 100) / 100 - 0.03; // -0.03  ....corretivo per evitare 100%
		}
		
		//Caratteristiche NL180
        if (strtoupper($model) == "MODEL NL180")
            $ef = 1 - (-0.442211055*$mf**2 + 0.954371859*$mf + 0.048143729);
        

        if (strpos(strtoupper($model), "MODEL R")!==false) {

			
            if (strpos(strtoupper($model), "500")!==false)
			{
				$eff34 = (0.000007 * $af**2 - 0.0235 * $af + 89.6) / 100;
				
			}
           

            if (strpos(strtoupper($model), "600")!==false)
			{
                $eff34 = (0.000004 * $af**2 - 0.017 * $af + 90.15) / 100;
				
			}
            

            if (strpos(strtoupper($model), "700")!==false)
			{
                $eff34 = (0.0000025 * $af**2 - 0.0132 * $af + 90.56) / 100;
				
			}
            

            if (strpos(strtoupper($model), "1000")!==false)
			{
                $eff34 = (0.0000004 * $af**2 - 0.0053 * $af + 89.351) / 100;
			
			}

            if (strpos(strtoupper($model), "1200")!==false)
			{
                $eff34 = (0.0000002 * $af**2 - 0.0039 * $af + 92.253) / 100;
			
			}
			
			 if (strpos(strtoupper($model), "1300")!==false)
			{
                $eff34 = (-0.00248514	 * $af + 91.89238095 ) / 100;
			
			}

			 if (strpos(strtoupper($model), "1316")!==false)
			{
                $eff34 = (-0.00140114	 * $af + 91.62133333 ) / 100;
			
			}

			 if (strpos(strtoupper($model), "1700")!==false)
			{
                $eff34 = (-0.00140114	 * $af + 91.62133333 ) / 100;
			
			}
			


			
		}
		elseif (strpos(strtoupper($model), "MODEL PCF")!==false)
		{
			if (strpos(strtoupper($model), "45")!==false)
			{
				$eff_calc = array(97.8, 85.1, 84.5,83.1,80.3,80,79.4,78.8,78.5,78.2,77.8,77.6,77.5,76.8,75.7,75.3);
				$af_calc = array(0,229,262.72,340,600.53,630,716,805,862.5,920,993.5,1030.25,1067,1235,1542,1706.65);
				tointercept($af_calc, $eff_calc);
				$eff34 = I($af, $af_calc,$eff_calc)  / 100;
				
				
			}
			if (strpos(strtoupper($model), "-K 35")!==false)
			{
				$eff_calc = array(97.8,88.2,83.9,81.8,80.3,79.2,78.2,77.5);
				$af_calc = array(5.9374,262.72,600.53,912.48,1235,1542,1871.3,2167.7);
				tointercept($af_calc, $eff_calc);
				$eff34 = I($af, $af_calc,$eff_calc)  / 100;
				
					
			}
			$eff34 =$eff34*( 0.0014*abs($tin-$tout)+0.9584);
			
		}
		elseif (strpos(strtoupper($model), "MODEL EN366")!==false)
		{
			 $eff34 = (-0.046 * $af + 84.15) / 100;
		}
        else
		{
            // Identifico quali sono il ramo caldo e ramo freddo, per procedere con il calcolo
            // che consisterà nel convergere sul ramo che da caldo si raffredda (indipendentemente dal
            // fatto che si chiami Exhaust o Supply
            if ($tin > $tout)
			{
                $thot = $tin;
                $tcold = $tout;
                $tdew = $tdew_in;
                $Mdamphot = $mm;
                $mldryhot = $mda;
                $Mdampcold = $mm_out;
                $mldrycold = $mda_out;
			}
            else
			{
                $thot = $tout;
                $tcold = $tin;
                $tdew = $tdew_out;
                $Mdamphot = $mm_out;
                $mldryhot = $mda_out;
                $Mdampcold = $mm;
                $mldrycold = $mda;
			}
            

            $rsens = 1; //valore iniziale del rapporto tra calore sensibile e totale (1 = no consensazione, no calore latente)
            $stepsCounter= 0;
            $qPrevious = 0;
			$T2new = 0;
            do
			{
                $eff34 = $ef + (1.588 * (1 - $rsens) - 0.5793 * (1 - $rsens) * (1 - $rsens)) * (1 - $ef);

                $T4 = $tcold + $eff34 * ($thot - $tcold);
                $Q = ($T4 - $tcold) * $mcp_wet;

                if ($stepsCounter = 0)
                    $T2 = $thot - $rsens * ($Q / $mcp_wet);
                else
                    $T2 = $T2new - ($Q - $qPrevious) / (7 * $mcp_wet);
                

                $psat2 = 100 * exp(17.433 - 19513.7 * exp(-1.27095 * log($T2 + $T0)));

                if ($T2 < $tdew)  //Controllo se sono sotto la temperatura di condensa e quindi decido il valore dell'umidità relativa
                    $rh2 = 1;
                else
                    $rh2 = (($Mdamphot * $rhoa * $pr) / ($rhom * $mldryhot)) / ($psat2 * (1 + ($Mdamphot * $rhoa) / ($rhom * $mldryhot)));
                
                $mm2 = $mldryhot * ($rhom / $rhoa) * ($psat2 * $rh2) / ($pr - ($psat2 * $rh2));
                $Qsens = ($thot - $T2) * $mcp_wet;
                $Qlat = $hc * ($Mdamphot - $mm2);
                $dQ = $Q - $Qsens - $Qlat;
                $T2new = $tdew - (($Q - ($thot - $tdew) * $mcp_wet) / ($Qsens + $Qlat - ($thot - $tdew) * $mcp_wet)) * ($tdew - $T2);
                $rsens = $Qsens / ($Qsens + $Qlat);

                $stepsCounter += 1;
                $qPrevious = $Q;

            } while (abs($dQ) > 0.001); //Fintanto che la condizione è VERA = livello di precisione della convergenza

            //Compleatamento dello stato termodinamico Punto 4
            $psat4 = 100 * exp(17.433 - 19513.7 * exp(-1.27095 * log($T4 + $T0)));
            $rh4 = (($Mdampcold * $rhoa * $pr) / ($rhom * $mldrycold)) / ($psat4 * (1 + ($Mdampcold * $rhoa) / ($rhom * $mldrycold)));
            $mm4 = $mldrycold * ($rhom / $rhoa) * ($psat4 * $rh4) / ($pr - ($psat4 * $rh4));

            //Devo riassegnare le temperature ai rami corretti
            if ($tin > $tout)
			{
                $calcolato['Supply_outlet_temp'] = $T4;
                $calcolato['Supply_outlet_rh'] = $rh4;
                $calcolato['Exhaust_outlet_temp'] = $T2;
                $calcolato['Exhaust_outlet_rh'] = $rh2;
                $calcolato['water_produced'] = 3600 * ($mm - $mm2);
			}
            else 
			{
                $calcolato['Supply_outlet_temp'] = $T2;
                $calcolato['Supply_outlet_rh'] = $rh2;
                $calcolato['Exhaust_outlet_temp'] = $T4;
                $calcolato['Exhaust_outlet_rh'] = $rh4;              
				$calcolato['water_produced'] = 3600 * ($mm_out - $mm2);
			}
            
        }


        //Se sono MODEL R devo cambiare il calcolo (dopo che l'efficienza è stata calcolata perchè dipende tutto da qui)
        if (  ( (strpos(strtoupper($model), "MODEL R")!==false) or (strpos(strtoupper($model), "MODEL EN366")!==false) )and ($onlyeff == false) ) {

            $psychro_r=array();
            $psychro_f=array();
            $psychro_s=array();
            $psychro_e=array();
            $eff_hr = 1.035 * $eff34;
            
            
            
            //portata massica
            $rho = 1.2;
            $m   = $rho * $af / 3600;

            //riposiziono le temperature
            If ($tin > $tout)
			{
                $tr = $tin;
                $tf = $tout;
                $hrr =$hrin;
                $hrf =$hrout;
			}
            else
			{
                $tr  = $tout;
                $tf  = $tin;
                $hrr = $hrout;
                $hrf = $hrin;
            }


            //punti termodinamici noti
            $psychro_r = PsychroCalc($tr, $hrr);
            $psychro_f = PsychroCalc($tf, $hrf);

            //temperatura uscita 1
            $tsup = $tf + $eff34 * ($tr - $tf);
            $wsup = $psychro_f['w'] + $eff_hr * ($psychro_r['w'] - $psychro_f['w']);
            $psychro_s = PsychroCalcW($tsup, $wsup);

            //temperatura uscita 1
            $wexh = $psychro_r['w'] - ($wsup - $psychro_f['w']);
            $texh = $tr - ($tsup - $tf);
            $psychro_e = PsychroCalcW($texh, $wexh);

            $Q = $m * ($psychro_s['h'] - $psychro_f['h']) * 1000;
            $Qsens = $m * 1005 * ($tsup - $tf);
            $Qlat = $Q - $Qsens;
            $calcolato['water_produced'] = ($wsup - $psychro_f['w']) * $m * 3600;


            if ($tin > $tout)
			{
                $calcolato['Supply_outlet_temp'] = $tsup;
                $calcolato['Supply_outlet_rh'] = $psychro_s['rh'];
                $calcolato['Exhaust_outlet_temp'] = $texh;
                $calcolato['Exhaust_outlet_rh'] = $psychro_e['rh'];
			}
            else
			{
                $calcolato['Supply_outlet_temp'] = $texh;
                $calcolato['Supply_outlet_rh'] = $psychro_e['rh'];
                $calcolato['Exhaust_outlet_temp'] = $tsup;
                $calcolato['Exhaust_outlet_rh'] = $psychro_s['rh'];
            }


        }
		
		 if ((strpos(strtoupper($model), "MODEL PCF")!==false) and ($onlyeff == false) ) {
		 
		    $psychro_r=array();
            $psychro_f=array();
            $psychro_s=array();
            $psychro_e=array();
            $eff_hr = 1.135 * $eff34;
            
            
            
            //portata massica
            $rho = 1.2;
            $m   = $rho * $af / 3600;

            //riposiziono le temperature
            If ($tin > $tout)
			{
                $tr = $tin;
                $tf = $tout;
                $hrr =$hrin;
                $hrf =$hrout;
			}
            else
			{
                $tr  = $tout;
                $tf  = $tin;
                $hrr = $hrout;
                $hrf = $hrin;
            }


            //punti termodinamici noti
            $psychro_r = PsychroCalc($tr, $hrr);
            $psychro_f = PsychroCalc($tf, $hrf);
			
			
			$tsup = $tf + $eff34 * ($tr - $tf);
			$wsup = $psychro_f['w'];
			
			$psychro_s = PsychroCalcW($tsup, $wsup);

			$Q = $m * ($psychro_s['h'] - $psychro_f['h']) * 1000;
			$texh = $tr - ($tsup - $tf); 
			
			//verifico la condensazione
			
			if ($texh < $psychro_r['tdew'])
			{
			$Q = $m * ($psychro_s['h'] - $psychro_f['h']) * 1000;
			$Qsens = $m * ($tr - $psychro_r['tdew']) * 1005;
			$Qlat = $Q - $Qsens;
			$wexh =  $psychro_r['w']- $Qlat/($m*2501000);
			$psychro_e = PsychroCalcW($texh, $wexh);
			$calcolato['water_produced']=$Qlat/(2501*$m);
			}
			else
			{
			$wexh = $psychro_r['w'];
            $psychro_e = PsychroCalcW($texh, $wexh);
            $Q = $m * ($psychro_s['h'] - $psychro_f['h']) * 1000;
            $Qsens = $Q;
            $Qlat = 0;
            $calcolato['water_produced'] = 0;			
			}
					 
			if ($tin > $tout)
			{
                $calcolato['Supply_outlet_temp'] = $tsup;
                $calcolato['Supply_outlet_rh'] = $psychro_s['rh'];
                $calcolato['Exhaust_outlet_temp'] = $texh;
                $calcolato['Exhaust_outlet_rh'] = $psychro_e['rh'];
			}
            else
			{
                $calcolato['Supply_outlet_temp'] = $texh;
                $calcolato['Supply_outlet_rh'] = $psychro_e['rh'];
                $calcolato['Exhaust_outlet_temp'] = $tsup;
                $calcolato['Exhaust_outlet_rh'] = $psychro_s['rh'];
            }
		 		 
		 }
		

        $calcolato['Return_inlet_temp'] = $tin;
        $calcolato['Return_inlet_rh'] = $hrin;
        $calcolato['Fresh_inlet_temp'] = $tout;
        $calcolato['Fresh_inlet_rh'] = $hrout;
        $calcolato['efficiency'] = $eff34;
        $calcolato['heat_recovery'] = $Q;
        $calcolato['sensible_heat'] = $Qsens;
        $calcolato['latent_heat'] = $Qlat;

        return $calcolato;

}

// Funzioni di calcolo sonore

function sound_power_correction($qref, $pref, $q1, $p1, $sound_value, $reg=100)
{

	$k1 = -0.0000508304;
	$k2 = 0.010028623;
	$k3 = 0.505101875;
	$b1 = 0.12782;
	$b2 = -7;

	if ($reg == 100)
		$corr_reg = 1;
	else
		$corr_reg = $k1 * $reg ** 2 + $k2 * $reg + $k3;


	$corr_flow = $b1 * log10($p1 / $pref) + $b2 * log10($q1 / $qref);

	$new_sound = $corr_reg * $sound_value - $corr_flow;

	if ($new_sound <= 0)
		  $new_sound = 3.5;

	return round($new_sound,1);
}

function totalsound($sound)
{
	$sum =0;
	foreach ($sound as $freq)
	{
		$sum += pow(10,$freq/10);
	}
	$total = round(10*log10($sum),1);
	
	return $total;
}

	
function sounddata($sound_inlet, $sound_outlet, $unit, $A=0,$B=0,$C=0, $qmax, $pmax, $q, $p, $reg=100, $directivity = 2){
	
	$fonoIsolamento =array(3, 8, 14, 20, 23, 26, 27, 35);
	$soundtable = array();
	$knew = 0;
	
	$section =$A * $C;
    $wallAndBase =($A + $B) * $C * 2 + $B * $A;
	if($section>0)
    $knew = 10 * log10($wallAndBase / $section);
	
	if($knew <= 0)
		$knew = 0;
		
		
	$sound_fresh = array();
	$sound_return = array();
	$sound_supply = array();
	$sound_exhaust = array();
	$sound_breakout = array();
		
	$N = count($sound_inlet);
	
	$key=array('63Hz','125Hz','250Hz','500Hz','1000Hz','2000Hz','4000Hz','8000Hz');
	 
	if(strpos($unit, 'HCI')!==false or strpos($unit, 'FS')!==false or strpos($unit, 'ST')!==false) 
	{
		for ($k=0; $k<$N; $k++) {
		$sound_fresh[$key[$k]]    = round(1.01*$sound_outlet[$k],1);
		$sound_return[$key[$k]]   = round(0.99*$sound_inlet[$k],1);
		$sound_supply[$key[$k]]   = round($sound_inlet[$k],1);
		$sound_exhaust[$key[$k]]  = round(1.01*$sound_outlet[$k],1);
		$sound_breakout[$key[$k]] = round($sound_inlet[$k],1);
		$sound_fresh[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_fresh[$key[$k]], $reg);
		$sound_return[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_return[$key[$k]], $reg);
		$sound_supply[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_supply[$key[$k]], $reg);
		$sound_exhaust[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_exhaust[$key[$k]], $reg);
		$sound_breakout[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_breakout[$key[$k]], $reg);
		}
	}
	 else
	 {
		for ($k=0; $k<$N; $k++) {
			$sound_fresh[$key[$k]]    = round(1.01*$sound_inlet[$k],1);
			$sound_return[$key[$k]]   = round(0.99*$sound_inlet[$k],1);
			$sound_supply[$key[$k]]   = round($sound_outlet[$k],1);
			$sound_exhaust[$key[$k]]  = round(1.01*$sound_outlet[$k],1);
			$sound_breakout[$key[$k]] = round($sound_outlet[$k] - $fonoIsolamento[$k] -  $knew,1);
			$sound_fresh[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_fresh[$key[$k]], $reg);
			$sound_return[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_return[$key[$k]], $reg);
			$sound_supply[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_supply[$key[$k]], $reg);
			$sound_exhaust[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_exhaust[$key[$k]], $reg);
			$sound_breakout[$key[$k]] = sound_power_correction($qmax, $pmax, $q, $p, $sound_breakout[$key[$k]], $reg);
			}
	}
	
	$Lw_fresh = totalsound($sound_fresh);
	$Lw_return = totalsound($sound_return);
	$Lw_supply = totalsound($sound_supply);
	$Lw_exhaust = totalsound($sound_exhaust);
	$Lw_breakout = totalsound($sound_breakout);
	
	$Lp15_fresh    = round($Lw_fresh - 11 + 10 * log10($directivity)- 20 * log10(1.5),1);
	$Lp15_return   = round($Lw_return - 11 + 10 * log10($directivity)- 20 * log10(1.5),1);
	$Lp15_supply   = round($Lw_supply - 11 + 10 * log10($directivity)- 20 * log10(1.5),1);
	$Lp15_exhaust  = round($Lw_exhaust - 11 + 10 * log10($directivity)- 20 * log10(1.5),1);
	$Lp15_breakout = round($Lw_breakout - 11 + 10 * log10($directivity)- 20 * log10(1.5),1);
	
	$Lp30_fresh    = round($Lw_fresh - 11 + 10 * log10($directivity)- 20 * log10(3),1);
	$Lp30_return   = round($Lw_return - 11 + 10 * log10($directivity)- 20 * log10(3),1);
	$Lp30_supply   = round($Lw_supply - 11 + 10 * log10($directivity)- 20 * log10(3),1);
	$Lp30_exhaust  = round($Lw_exhaust - 11 + 10 * log10($directivity)- 20 * log10(3),1);
	$Lp30_breakout = round($Lw_breakout - 11 + 10 * log10($directivity)- 20 * log10(3),1);
	
	
	$sound_fresh['Lw']=$Lw_fresh;
	$sound_return['Lw']=$Lw_return;
	$sound_supply['Lw']=$Lw_supply;
	$sound_exhaust['Lw']=$Lw_exhaust; 
	$sound_breakout['Lw']=$Lw_breakout;
	
	$sound_fresh['Lp15']=$Lp15_fresh;
	$sound_return['Lp15']=$Lp15_return;
	$sound_supply['Lp15']=$Lp15_supply;
	$sound_exhaust['Lp15']=$Lp15_exhaust; 
	$sound_breakout['Lp15']=$Lp15_breakout;
	
	$sound_fresh['Lp30']=$Lp30_fresh;
	$sound_return['Lp30']=$Lp30_return;
	$sound_supply['Lp30']=$Lp30_supply;
	$sound_exhaust['Lp30']=$Lp30_exhaust; 
	$sound_breakout['Lp30']=$Lp30_breakout;
		
	$soundtable['Fresh']=$sound_fresh;
	$soundtable['Return']=$sound_return;
	$soundtable['Supply']=$sound_supply;
	$soundtable['Exhaust']=$sound_exhaust;
	$soundtable['Breakout']=$sound_breakout;
	
	
	return $soundtable;
}



function psfp($airflows, $powers)
{
	
	$psfp = array();
	$psfp_af = array();
		
		if(count($airflows) === count($powers))
		{
			for($i = 1; $i < count($airflows); $i++)
			{
				$value = round(2* $powers[$i] / ($airflows[$i] / 3600), 1);

				if ($value < 5000)
				{
					$psfp[]=$value;
					$psfp_af[]=$airflows[$i];
				}
			}
		}
	

	return array($psfp_af, $psfp);	
	

}

	
?>	