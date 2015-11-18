<?php


$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('SAND_URL','https://www.sandbox.paypal.com/webscr?cmd=_ap-payment&paykey='); // sandbox url used below
define('API_URL', 'https://svcs.sandbox.paypal.com/AdaptivePayments/'); // url to create payrequest and get the paykey

// facilitator emails
define('EMAIL_1', 'gupta@students.iiit.ac.in');
define('EMAIL_2', 'gupta.anu1995-facilitator@gmail.com');

define('API_USER', 'gupta.anu1995-facilitator_api1.gmail.com');
define('API_PASSWORD', 'ZGTLDYSXNWNK9FHK');
define('API_SIG', 'Av.S55yXS7M-xQMwsjJL65eb6IYXATqeEpsq9-ik5tcrU7CWK4g9jxxo');
define('APP_ID', 'APP-80W284485P519543T');


class Paypal {

	function getPaymentOptions($paykey){
		$packet = array(
			"requestEnvelope" => $this->envelope,
			"payKey" => $paykey
		);
		return $this->_paypalSend($packet, "GetPaymentOptions");
	}

	function __construct(){
		$this->headers = array(
			"X-PAYPAL-SECURITY-USERID: ".API_USER,
			"X-PAYPAL-SECURITY-PASSWORD: ".API_PASSWORD,
			"X-PAYPAL-SECURITY-SIGNATURE: ".API_SIG,
			"X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
			"X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
			"X-PAYPAL-APPLICATION-ID: ".APP_ID
		);
		$this->envelope = array(
			"errorLanguage" => "en_US",
			"detailLevel" => 'ReturnAll'
		);
	}

	function _paypalSend($data, $call){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_URL.$call);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FASE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		return json_decode(curl_exec($ch), TRUE);
	}

	function splitPay(){
		// create pay request

		$createPacket = array(
			"actionType" => "PAY", 
			"currencyCode" => "USD",
			"receiverList" => array(   // here we set the amounts to be paid to each receiver, just do the total and give 30 and 70%
				"receiver" => array(
					array(
						"amount" => "5.00",
						"email" => EMAIL_1
					),
					array(
						"amount" => "10.00",
						"email" => EMAIL_2
					)
				)
			),
			"returnUrl" => "http://localhost/parallel-payment-paypal/paypal.php?action=success",
			"cancelUrl" => "http://localhost/parallel-payment-paypal/paypal.php?action=cancel",
			"requestEnvelope" => array(
				"errorLanguage" => "en_US",
				"detailLevel" => 'ReturnAll'
			)
		);
		$response = $this->_paypalSend($createPacket, "Pay");
		// working fine till now (got my paykey)

		$paykey = $response['payKey'];
		// SET PAYMENT OPTIONS (the actual items)

		$detailsPacket = array(
			"requestEnvelope" => array(
				"errorLanguage" => "en_US",
				"detailLevel" => 'ReturnAll'
			),
			"payKey" => $response['payKey'],
			"receiverOptions" => array(
				array(
					"receiver" => array("email" => EMAIL_1),   // the sum total fof all items for a receiver should match the total above.
					"invoiceData" => array(
						"item" => array(
							array(
								"name" => "product1",
								"price" => "2.00",    
								"identifier" => 'p1'
							),
							array(
								"name" => "product2",
								"price" => "3.00",
								"identifier" => 'p2'
							)
						)
					)
				),
				array(
					"receiver" => array("email" => EMAIL_2),    // the sum total fof all items for a receiver should match the total above.
					"invoiceData" => array(
						"item" => array(
							array(
								"name" => "product3",
								"price" => "4.00",
								"identifier" => 'p3'
							),
							array(
								"name" => "product4",
								"price" => "6.00",
								"identifier" => 'p4'
							)
						)
					)
				),
			)
		);

		$response = $this->_paypalSend($detailsPacket, "SetPaymentOptions");

		$dets = $this->getPaymentOptions($paykey);

		// head over to paypal
		header("Location: ".SAND_URL.$paykey);
	}

}
