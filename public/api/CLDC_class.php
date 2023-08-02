
<?php
	include_once("functions.php");
	
	class CLDC
	{
		
		// Connessione al DB di CLDC
		function connection() 
		{
			$host="localhost";  // connessione al server locale 
			$user="root";      //  utente
			$pas="";          //   passsword dell'utente
			$database="cldatacentral2"; // connessimone a mysql e database
			$connect=mysqli_connect($host,$user,$pas,$database);
			mysqli_set_charset($connect, "utf8");
			return $connect;
		} 
		
		
		//Estrazione serie di recuperatori
		public function serie( $is_SSW = null) 
		{
			$conn_cldc = $this->connection();
			
			$sqlquery = "SELECT Id
			,Code
			,Name
			,Description
			FROM CLSeries ";		
			
			if ($is_SSW !== null)
			{
				$sqlquery = $sqlquery . " where Code <= '9' and Code >= '0' and Code not like '31' ";
				//echo $sqlquery;
			}
			
			
			$risultato = mysqli_query( $conn_cldc, $sqlquery);
			$serie = array();
			
			while($recordestratti= mysqli_fetch_array($risultato) )
			{ 
				$ser['id']   = $recordestratti['Id'];
				$ser['code'] = $recordestratti['Code'];
				$ser['name'] = $recordestratti['Name'];
				$ser['feature'] = $recordestratti['Description'];
				array_push($serie, $ser);
			}
			
			return $serie;
		}
		
		//Estrazione spigots layout
		public function layout() 
		{
			$conn_cldc = $this->connection();
			
			$sqlquery = "SELECT Id
			,TextCode
			FROM CLEnumItems
			WHERE IdEnum = 4";		
			
			$risultato = mysqli_query( $conn_cldc, $sqlquery);
			$layout = array();
			
			while($recordestratti= mysqli_fetch_array($risultato) )
			{ 
				$lay['id'] = $recordestratti['Id'];
				$lay['TextCode'] = $recordestratti['TextCode'];
				array_push($layout, $lay);
			}
			
			return $layout;
		}		
		
		//Estrazione modelli prodotti - filtro su serie e layout disponibile
		public function modelli( $idserie = null, $idlayout= null) 
		{
			$conn_cldc = $this->connection();
			
			$filter = "";
			
			if ($idlayout != null)
			$filter .= " and IdAeraulicConnection = '".$idlayout."'";
			
			if ($idserie != null)
			$filter .= " and IdSerie = '".$idserie."'";
			
			
			$sqlquery = "SELECT CLHeatRecoveryModels.Id
			,Code
			,IdSerie
			,CLHeatRecoveryModels.Name
			,TextCode
			FROM CLHeatRecoveryModels
			inner join CLEnumItems on CLEnumItems.id = IdAeraulicConnection
			WHERE ModRec is not null ".$filter."
			order by TextCode desc, CLHeatRecoveryModels.Name asc";	
			
			//echo $sqlquery;
			
			$risultato = mysqli_query( $conn_cldc, $sqlquery);
			$models = array();
			
			if($risultato)
			{
				while($recordestratti= mysqli_fetch_array($risultato) )
				{ 
					$mod['id'] = $recordestratti['Id'];
					$mod['code'] = $recordestratti['Code'];
					$mod['name'] = $recordestratti['Name'];
					$mod['layout'] = $recordestratti['TextCode'];
					array_push($models, $mod);
				}				
			}
			else
			{
					$mod['id'] = "N/A";
					$mod['code'] = "N/A";
					$mod['name'] = "N/A";
					$mod['layout'] = "N/A";	
					array_push($models, $mod);
			}

			return $models;
		}	
		
		
			public function data_model( $id = 139) 
		{
			$conn_cldc = $this->connection();
					
			
			$sqlquery = "SELECT CLHeatRecoveryModels.*
			FROM CLHeatRecoveryModels
			inner join CLEnumItems on CLEnumItems.id = IdAeraulicConnection
			WHERE ModRec is not null and CLHeatRecoveryModels.Id = ".$id."
			order by TextCode desc, CLHeatRecoveryModels.Name asc";	
			
			$risultato = mysqli_query( $conn_cldc, $sqlquery);
			$model = array();
			
			if($risultato)
			{
				while($recordestratti= mysqli_fetch_array($risultato))
				{ 
					array_push($model, $recordestratti);
				}				
			}

			return $model;
		}
		
		
			public function add_newmodel($newmodel, $oldmodel, $idSerie=null, $idLayout=null)
		{
			
			$conn_cldc = $this->connection();
			
			if($idSerie==null)
			{
			$idSerie='IdSerie';
			}
			if($idLayout==null)
			{
			$idLayout='IdAeraulicConnection';
			}
			
			$sqlquery="
			insert into CLHeatRecoveryModels 
			SELECT 
				'".$newmodel."'
				,".$idSerie."
				,'".$newmodel."'
				,".$idLayout."
				,ModRec
				,LenRec
				,FilterArea
				,MotorType
				,Airflows
				,Pressures
				,Powers
				,SoundData_Inlet_63hz
				,SoundData_Inlet_125hz
				,SoundData_Inlet_250hz
				,SoundData_Inlet_500hz
				,SoundData_Inlet_1000hz
				,SoundData_Inlet_2000hz
				,SoundData_Inlet_4000hz
				,SoundData_Inlet_8000hz
				,SoundData_Inlet_Total
				,SoundData_Outlet_63hz
				,SoundData_Outlet_125hz
				,SoundData_Outlet_250hz
				,SoundData_Outlet_500hz
				,SoundData_Outlet_1000hz
				,SoundData_Outlet_2000hz
				,SoundData_Outlet_4000hz
				,SoundData_Outlet_8000hz
				,SoundData_Outlet_Total
				,PDFCommercialSheets
				,CWD_IdFinsStep
				,CWD_Length
				,CWD_Height
				,CWD_NumerOfRows
				,CWD_NumerOfCircuits
				,CWD_IdHeaderType
				,HWD_IdFinsStep
				,HWD_Length
				,HWD_Height
				,HWD_NumerOfRows
				,HWD_NumerOfCircuits
				,HWD_IdHeaderType
				,Size
				,MotorsNumbers
				,PulseForRoundNumbers
				,Weakening
				,NumbersNTC
				,TFreshPosition
				,TReturnPosition
				,TSupplyPosition
				,TExaustPosition
				,VirtualCAF
				,VirtualCAP
				,StaticPressure
				,NominalAirflow
				,Power
				,PowerWithIPEHD
				,IdCommercialLine
				,HorVariants
				,VerVariants
				,IND_VarVer_Caption
				,IND_VarVer_EW_Img
				,IND_VarVer_NS_Img
				,IND_VarHor_Caption
				,IND_VarHor_Floor_Img
				,IND_VarHor_Ceiling_Img
				,Voltage
				,Phase
				,Frequency
				,HeatRecovered_Winter
				,HeatRecovered_Summer
				,InternationalProtection
				,Dimension_A_Ver
				,Dimension_A_Hor
				,Dimension_B_Ver
				,Dimension_B_Hor
				,Dimension_C_Ver
				,Dimension_C_Hor
				,Dimension_D_Ver
				,Dimension_D_Hor
				,Weight
				,IND_Logo_Img
				,IND_Photo_Hor_Img
				,IND_Photo_Ver1_Img
				,IND_Photo_Ver1_Caption
				,IND_Photo_Ver2_Img
				,IND_Photo_Ver2_Caption
				,IND_Performance_Img
				,NomimalCurrent
				,LoudPressure_Inlet
				,LoudPressure_Outlet
				,Exaust_X
				,Exaust_Y
				,Exaust_K
				,Exaust_Z
				,Fresh_X
				,Fresh_Y
				,Fresh_K
				,Fresh_Z
				,Return_X
				,Return_Y
				,Return_K
				,Return_Z
				,Supply_X
				,Supply_Y
				,Supply_K
				,Supply_Z
				,Efficiency
				,SYSIdRevision
				,IdProcessingTaskType
				,PDFInstallationOperationManuals
			FROM CLHeatRecoveryModels
			where CLHeatRecoveryModels.code = '".$oldmodel."'";
			
			//echo $sqlquery;
			
			$risultato = mysqli_query( $conn_cldc, $sqlquery);
			
			return $risultato;

		}
		
		
	}	  
	//---------------- EOF -------------------//
	?>
	
		