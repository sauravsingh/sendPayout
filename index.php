function getAccessToken(){
		$ch = curl_init();
$clientId = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$secretKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secretKey);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

		$result = curl_exec($ch);
		$response = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close ( $ch );

		if(empty($result))die("Something went wrong, contact to Admin");
		else
		{
		    $json = json_decode($result);
		    return $json->access_token;
		}

		curl_close($ch);
	}
	
	
	$curl = curl_init();
			$headr = array();
			$headr[] = "Content-Type: application/json";
			$headr[] = "Authorization: Bearer ".getAccessToken();

			$json_data = '{
			  "sender_batch_header": {
			    "sender_batch_id": "'.rand().'",
			    "email_subject": "Payout credited",
			    "recipient_type": "EMAIL"
			  },
			  "items": [
			    {
			      "recipient_type": "EMAIL",
			      "amount": {
				"value": "30",
				"currency": "USD"
			      },
			      "note": "Amount transfer",
			      "sender_item_id": "'.date('YmdHis').'",
			      "receiver": "test@gmail.com"
			    }
			  ]
			}';

	
			curl_setopt($curl, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/payments/payouts?sync_mode=true");

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headr); //setting custom header
			$result = curl_exec($curl);
			$response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			//return $result;
			if($result == false){
			  print_r('Curl error: ' . curl_error($curl));
			}
			$json = json_decode($result);
			//echo "<br>STATUS: " .$json->batch_header->batch_status;
							
			print_r($json);
