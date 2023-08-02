<?php

namespace App\Services;

/**
 * Class SMSGatewayService.
 */
class SMSGatewayService
{        
    private $connection_id = null;
    private $password      = null;
        
    private $doHTTPS       = true;    
    private $requestMethod = 1;
    
    protected $endpointHTTP  = "http://www.smslink.ro/sms/gateway/communicate/index.php";
    protected $endpointHTTPS = "https://secure.smslink.ro/sms/gateway/communicate/index.php";
        
    public $communicationLogs = array();
    
    /**
     *   Initialize SMSLink - SMS Gateway
     *
     *   Initializing SMS Gateway will require the parameters $connection_id and $password. $connection_id and $password can be generated at 
     *   https://www.smslink.ro/sms/gateway/setup.php after authenticated with your account credentials.
     *
     *   @param string    $connection_id     SMSLink - SMS Gateway - Connection ID
     *   @param string    $password          SMSLink - SMS Gateway - Password             
     *
     *   @return void
     */
    public function __construct()
    {         
        $connection_id = env('SMSLINK_CONNECTTION_ID');
        $password = env('SMSLINK_PASSWORD');
        if (!is_null($connection_id))         
            $this->connection_id = $connection_id;        
        
        if (!is_null($password))        
            $this->password = $password;
               
        if ((is_null($this->connection_id)) or (is_null($this->password)))
            exit("SMS Gateway initialization failed, credentials not provided. Please see documentation."); 
        
    }

    public function __destruct()
    {
        $this->connection_id = null;
        $this->password = null;      
        
        $this->doHTTPS = true;
        $this->requestMethod = 1;
    }
    
    /**
     *   Sets the method in which the parameters are sent to SMS Gateway
     *
     *   @param int    $requestMethod     1 for cURL GET (recommended and default value)
     *                                    2 for cURL POST
     *                                    3 for file_get_contents (recommended if you do not have PHP cURL installed)
     *
     *   @return bool     true if method was set or false otherwise
     */
    public function setRequestMethod($requestMethod = 1)
    {
        if (in_array($requestMethod, array(1, 2, 3))) $this->requestMethod = $requestMethod;
            else return false;
    
        return true;
    }
    
    /**
     *   Returns the method in which the parameters are sent to SMS Gateway
     *
     *   @return int     
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }
    
    /**
     *   Sets the protocol that will be used by SMS Gateway (HTTPS or HTTP).
     *
     *   @param string    $methodName     POST or GET
     *
     *   @return bool     true if method was set or false otherwise
     */
    public function setProtocol($protocolName = "HTTPS")
    {
        $protocolName = strtoupper($protocolName);
        
        if ($protocolName == "HTTPS") $this->doHTTPS = true;
            elseif ($protocolName == "HTTP") $this->doHTTPS = false;
            else return false;
    
        return true;
    }
    
    /**
     *   Returns the protocol that is used by SMS Gateway (HTTPS or HTTP)
     *
     *   @return string     GET or POST possible values
     */
    public function getProtocol()
    {
        return ($this->doHTTPS) ? "HTTPS" : "HTTP";
    }
        
    /**
     *   Sends SMS
     *   
     *   @param string    $receiverNumber           Receiver mobile phone number. Phone numbers should be formatted as a Romanian national mobile phone number (07xyzzzzzz)
     *                                              or as an International mobile phone number (00 + Country Code + Phone Number, example 0044zzzzzzzzz).
     *                                              
     *   @param string    $messageText              Message of the SMS, up to 160 alphanumeric characters, or longer than 160 characters. 
     *   
     *   @param string    $senderId                 (Optional) Sender alphanumeric string:   
     *      
     *                                                 numeric    - sending will be done with a shortcode (ex. 18xy, 17xy)
     *                                                 SMSLink.ro - sending will be done with SMSLink.ro (use this for tests only)
     *                                                
     *                                                 Any other preapproved alphanumeric sender assigned to your account:
     *                                     
     *                                                     Your alphanumeric sender list:        http://www.smslink.ro/sms/sender-list.php
     *                                                     Your alphanumeric sender application: http://www.smslink.ro/sms/sender-id.php
     *                                         
     *                                                 Please Note:
     *                                     
     *                                                 SMSLink.ro sender should be used only for testing and is not recommended to be used in production. Instead, you 
     *                                                 should use numeric sender or your alphanumeric sender, if you have an alphanumeric sender activated with us.
     *                                         
     *                                                 If you set an alphanumeric sender for a mobile number that is in a network where the alphanumeric sender has not
     *                                                 been activated, the system will override that setting with numeric sender.
     *                                         
     *                                         
     *   @param int       $timestampProgrammed    (Optional) Should be 0 (zero) for immediate sending or other UNIX timestamp in the future for future sending   
     *   
     *   @return int      representing SMSLink Message ID on success or false on failure.
     *                                        
    */    
    public function sendMessage($receiverNumber, $messageText, $senderId = NULL, $timestampProgrammed = 0)
    {        
        $messageId = false;
        $requestURL = ($this->getProtocol() == "HTTPS") ? $this->endpointHTTPS : $this->endpointHTTP;   
        
        $requestParameters = array(
                "connection_id" => $this->connection_id,
                "password"      => $this->password,
                "to"            => $receiverNumber,
                "message"       => $messageText,                       
            );
    
        if (!is_null($senderId))
            $requestParameters["sender"] = urlencode($senderId);
        
        if (!is_null($timestampProgrammed))
            if ($timestampProgrammed > 0)
                $requestParameters["timestamp"] = $timestampProgrammed;                    
        
        $requestResult = $this->sendRequest($requestURL, $requestParameters);            
        $requestResult = explode(";", $requestResult);
        
        if ($requestResult[0] == "MESSAGE")
        {
            $requestResultVariabiles = explode(",", $requestResult[3]);
            $messageId = $requestResultVariabiles[0];
        }
                
        return $messageId;            
    }                
    
    /**
     *   Account Balance
     *   
     *   @return array      associative array decribing national-SMS and internationa-SMS account balance      
     *                                          
    */
    public function accountBalance()
    {
        $accountBalance = array(
                "national-SMS"      => 0,
                "international-SMS" => 0                
            );
                
        $requestURL = ($this->getProtocol() == "HTTPS") ? $this->endpointHTTPS : $this->endpointHTTP;   
        
        $requestParameters = array(
                "connection_id" => $this->connection_id,
                "password"      => $this->password,
                "mode"          => "account-balance"                                      
            );

        $requestResult = $this->sendRequest($requestURL, $requestParameters);
        $requestResult = explode(";", $requestResult);
        
        if ($requestResult[0] == "MESSAGE")
        {
            $requestResultVariabiles = explode(",", $requestResult[3]);
            $accountBalance = array(
                    "national-SMS"      => $requestResultVariabiles[0],
                    "international-SMS" => $requestResultVariabiles[1]                
                );
        }
     
        return $accountBalance;         
    }

    /**
     *   Sends Request to SMSLink
     *
     *   @param string    $requestURL     
     *   @param array     $requestParameters          
     *
     *   @return string
     *   
     */
    private function sendRequest($requestURL, $requestParameters)
    {                
        $requestResult  = false;
        $returnedResult = "ERROR;0;Unknown error.";
        
        $requestMethod = $this->getRequestMethod();                

        $logMessage = date("d-m-Y H:i:s")." - Sending Request using ";
        
        if ($requestMethod == 1)
            $logMessage = $logMessage."cURL GET";
        
        if ($requestMethod == 2)
            $logMessage = $logMessage."cURL POST";
        
        if ($requestMethod == 3)
            $logMessage = $logMessage."file_get_contents()";
                
        $serializedParameters = http_build_query($requestParameters);  
        
        if (($requestMethod == 1) or ($requestMethod == 2))
        {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $requestURL.(($requestMethod == 1) ? "?".$serializedParameters : ""));
            
            $logMessage = $logMessage." to URL: [".$requestURL.(($requestMethod == 1) ? "?".$serializedParameters : "")."]";
            
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            if ($requestMethod == 2)
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $serializedParameters);
                
                $logMessage = $logMessage." with POST parameters: [".$serializedParameters."]";
            }
            
            if (strpos($requestURL, "https://") !== false)
            {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
            
            $requestResult = curl_exec($ch);
            
            $connectionErrorCode    = curl_errno($ch);
            $connectionErrorMessage = curl_error($ch);
            $requestStatusCode      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if ($connectionErrorCode == 0)
            {
                if (($requestStatusCode >= 200) and ($requestStatusCode <= 299))
                {
                    $returnedResult = $requestResult;
                }
                else
                {
                    $returnedResult = "ERROR;0;Unexpected HTTP code ".$requestStatusCode;
                }
            }
            else
            {
                $returnedResult = "ERROR;0;".$connectionErrorMessage;
            }
            
            curl_close($ch);
        }
        else
        {
            if ($requestMethod == 3)
            {
                $requestResult = file_get_contents($requestURL."?".$serializedParameters);
                $logMessage = $logMessage." to URL: [".$requestURL."?".$serializedParameters."]";
                
                if ($requestResult !== false)
                {
                    $returnedResult = $requestResult;
                }
                else
                {
                    $returnedResult = "ERROR;0;Connection failed using file_get_contents().";
                }
            }
        }

        $logMessage = $logMessage." => Request Result: [".$returnedResult."]";
        
        $this->communicationLogs[] = $logMessage;
        
        return $returnedResult;        
    }
    
    /**
     *   Returns the latest log message from communication log 
     *
     *   @return string   
     */
    public function getLastLogMessage()
    {
        return $this->communicationLogs[sizeof($this->communicationLogs) - 1];
    }
    
    /**
     *   Displays the communication log
     *
     *   @return string
     */
    public function displayLogMessages()
    {
        echo "<b>Communication Log:</b><br />";
        foreach ($this->communicationLogs as $key => $logMessage)
            echo $logMessage."<br />";
    }
}
