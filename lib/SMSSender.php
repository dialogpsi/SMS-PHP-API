<?php
// ==========================================
// Ideamart : PHP SMS API Sender Class
// ==========================================
// Author : Pasindu De Silva
// Licence : MIT License
// http://opensource.org/licenses/MIT
// ==========================================

require_once 'SMSServiceException.php';
class SMSSender{
	private $applicationId,
			$password,
			$charging_amount='',
			$encoding='',
			$version='',
			$deliveryStatusRequest='',
			$binaryHeader='',
			$sourceAddress='',
			$serverURL;
	
	/* Send the server name, app password and app id
	*	Dialog Production Severurl : HTTPS : - HTTPS://api.dialog.lk/sms/send 
	*				     HTTP  : - http//api.dialog.lk:8080/send/sms
	*/		
	public function __construct($serverURL, $applicationId, $password)
	{
		if(!(isset($serverURL, $applicationId, $password)))
			throw new SMSServiceException('Request Invalid.', 'E1312');
		else {
			$this->applicationId = $applicationId;
			$this->password = $password;
			$this->serverURL = $serverURL;
		}
	}
	
	// Broadcast a message to all the subcribed users
	public function broadcastMessage($message){
		return $this->sendMessage($message, array('tel:all'));
	}
	
	// Send a message to the user with a address or send the array of addresses
	public function sendMessage($message, $addresses){
		if(empty($addresses))
			throw new SMSServiceException('Format of the address is invalid.', 'E1325');
		else {
			$jsonStream = (is_string($addresses))?$this->resolveJsonStream($message, array($addresses)):(is_array($addresses)?$this->resolveJsonStream($message, $addresses):null);
			return ($jsonStream!=null)?$this->sendRequest($jsonStream):false;
		
		}
	}

	// Send post the request to the server and get the response
	private function sendRequest($jsonStream){

		$ch = curl_init($this->serverURL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       		curl_setopt($ch, CURLOPT_POST, 1);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStream);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$res = curl_exec($ch);
        	curl_close($ch);

		return $this->handleResponse(json_decode($response));

	}
	
	private function handleResponse($jsonResponse){
	
		$statusCode = $jsonResponse->statusCode;
		$statusDetail = $jsonResponse->statusDetail;
		
		if(empty($jsonResponse))
			throw new SMSServiceException('Invalid server URL', '500');
		else if(strcmp($statusCode, 'S1000')==0)
			return true;
		else
			throw new SMSServiceException($statusDetail, $statusCode);
	}
	
	// Encode json for POST
	private function resolveJsonStream($message, $addresses){
	
		$messageDetails = array("message"=>$message,
	   	           				"destinationAddresses"=>$addresses,
           						"sourceAddress" => $sourceAddress,
           						"chargingAmount" => $charging_amount,
           						"encoding" => $encoding,
           						"version" => $sourceAddress,
           						"deliveryStatusRequest" => $deliveryStatusRequest,
           						"binaryHeader" => $binaryHeader
           					);
		
		$applicationDetails = array('applicationId'=>$this->applicationId,
						 'password'=>$this->password,);
		
		$jsonStream = json_encode($applicationDetails+$messageDetails);
		
		return $jsonStream;
	}

	public function setsourceAddress($sourceAddress){
		$this->sourceAddress=$sourceAddress;
	}

	public function setcharging_amount($charging_amount){
		$this->charging_amount=$charging_amount;
	}

	public function setencoding($encoding){
		$this->encoding=$encoding;
	}

	public function setversion($version){
		$this->version=$version;
	}

	public function setbinaryHeader($binaryHeader){
		$this->binaryHeader=$binaryHeader;
	}

	public function setdeliveryStatusRequest($deliveryStatusRequest){
		$this->deliveryStatusRequest=$deliveryStatusRequest;
	}
}
?>
