<form method=post action="https://www.sandbox.paypal.com/cgi-bin/webscr">
  <input type="hidden" name="cmd" value="_notify-synch">
  <input type="hidden" name="tx" value="<?php echo $_GET['tx']; ?>">
  <input type="hidden" name="at" value="H7S4_sR4mkG8n_A8Uvc0E3ePi-JmEm36F7nXB3AgKNejr5BYzZZKmGqwyUK">
  <input type="submit" value="PDT">
</form>

<?php

/*$pp_hostname = 'www.sandbox.paypal.com'; // Change to www.sandbox.paypal.com to test against sandbox
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';
$tx_token = $_GET['tx'];
$auth_token = 'H7S4_sR4mkG8n_A8Uvc0E3ePi-JmEm36F7nXB3AgKNejr5BYzZZKmGqwyUK';
$req .= '&tx='.$tx_token.'&at='.$auth_token;
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://'.$pp_hostname.'/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
//if your server does not bundled with default verisign certificates.
//curl_setopt($ch, CURLOPT_CAINFO, getcwd().'cacert.pem');
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: '.$pp_hostname));
$res = curl_exec($ch);
curl_close($ch);

if(!$res){
    //HTTP ERROR
    echo 'HTTP ERROR';
}else{
     // parse the data
    $lines = explode("\n", $res);
    $keyarray = array();
    if (strcmp ($lines[0], 'SUCCESS') == 0) {
        for ($i=1; $i<count($lines);$i++){
	        list($key,$val) = explode("=", $lines[$i]);
	        $keyarray[urldecode($key)] = urldecode($val);
	    }
	    // check the payment_status is Completed
	    // check that txn_id has not been previously processed
	    // check that receiver_email is your Primary PayPal email
	    // check that payment_amount/payment_currency are correct
	    // process payment
	    $firstname = $keyarray['first_name'];
	    $lastname = $keyarray['last_name'];
	    $itemname = $keyarray['item_name'];
	    $amount = $keyarray['payment_gross'];
	     
	    echo ("<p><h3>Thank you for your purchase!</h3></p>");
	     
	    echo ("<b>Payment Details</b><br>\n");
	    echo ("<li>Name: $firstname $lastname</li>\n");
	    echo ("<li>Item: $itemname</li>\n");
	    echo ("<li>Amount: $amount</li>\n");
	    echo ("");
    }else if (strcmp ($lines[0], 'FAIL') == 0) {
        // log for manual investigation
        echo 'FAIL';
    }
}*/

//echo getcwd().'<br>'; //===================================


$tx = $_GET['tx'];
$ID = $_GET['cm'];
$currency = $_GET['cc'];
$identity = 'H7S4_sR4mkG8n_A8Uvc0E3ePi-JmEm36F7nXB3AgKNejr5BYzZZKmGqwyUK';

/* Use the full path to your own cacert.pem, download from the interwebs if you do not have a copy */
//$cacert = 'c:/wwwroot/cacert.pem';
$cacert = getcwd().'cacert.pem';

$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$fields = array(    
    'cmd'   => '_notify-synch',
    'tx'    => $tx,
    'at'    => $identity,
);

$request = curl_init();
curl_setopt($request,CURLOPT_URL, $url);

if( parse_url( $url,PHP_URL_SCHEME )=='https' ){
    curl_setopt( $request, CURLOPT_SSL_VERIFYPEER, FALSE ); /* set to true once you get this working */
    curl_setopt( $request, CURLOPT_SSL_VERIFYHOST, 2 );
    curl_setopt( $request, CURLOPT_CAINFO, realpath( $cacert ) );
}


/* this should be true or false not count($fields): in this case true*/
/*curl_setopt($request,CURLOPT_POST, count( $fields ) );*/
curl_setopt($request,CURLOPT_POST, true );
curl_setopt($request,CURLOPT_POSTFIELDS, http_build_query( $fields ) );
curl_setopt($request,CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($request,CURLOPT_HEADER, FALSE);

/* Quite often requests get rejected for no useragent */
curl_setopt($request,CURLOPT_USERAGENT, 'paypal-mozilla-chrome-useragent' );
curl_setopt($request, CURLINFO_HEADER_OUT, TRUE );


$response = curl_exec($request);
$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

curl_close($request);
/* See what the curl request has retrieved */
echo '<pre>',print_r( $response, true ),$status,'</pre>';
// ==============================================================================

/*
// Init cURL
$request = curl_init();

// Set request options
curl_setopt_array($request, array(
  CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
  CURLOPT_POST => TRUE,
  CURLOPT_POSTFIELDS => http_build_query(
  	array(
      'cmd' => '_notify-synch',
      'tx' => $tx_token,
      'at' => $auth_token,
    )),
  CURLOPT_RETURNTRANSFER => TRUE,
  CURLOPT_HEADER => FALSE,
  // Other options...
  CURLOPT_SSL_VERIFYPEER => TRUE, // Verify peers
  CURLOPT_CAINFO => getcwd() . 'cacert.pem', // Path to file with certificates
  // From paypal github code
  CURLOPT_SSL_VERIFYHOST => 2,
  CURLOPT_HTTPHEADER => array("Host: www.sandbox.paypal.com"),
));

// Execute request and get response and status code
$res = curl_exec($request);
$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

// Close connection
curl_close($request);

echo "<pre>";print_r($status);echo "</pre>";
echo "<pre>";print_r($res);echo "</pre>";

if($status == 200 AND strpos($res, 'SUCCESS') === 0)
{
    // Remove SUCCESS part (7 characters long)
	$res = substr($res, 7);

	// URL decode
	$res = urldecode($res);

	// Turn into associative array
	preg_match_all('/^([^=\s]++)=(.*+)/m', $res, $m, PREG_PATTERN_ORDER);
	$res = array_combine($m[1], $m[2]);

	// Fix character encoding if different from UTF-8 (in my case)
	if(isset($res['charset']) AND strtoupper($res['charset']) !== 'UTF-8')
	{
	  foreach($res as $key => &$value)
	  {
	    $value = mb_convert_encoding($value, 'UTF-8', $res['charset']);
	  }
	  $res['charset_original'] = $res['charset'];
	  $res['charset'] = 'UTF-8';
	}

	// Sort on keys for readability (handy when debugging)
	ksort($res);
}
else
{
    echo 'error';
}*/
 
?>


<?php
  // read the post from PayPal system and add 'cmd'
  /*$req = 'cmd=_notify-synch';
 
  $tx_token = $_GET['tx'];
  $auth_token = "H7S4_sR4mkG8n_A8Uvc0E3ePi-JmEm36F7nXB3AgKNejr5BYzZZKmGqwyUK";
  $req .= "&tx=$tx_token&at=$auth_token";
 
  // post back to PayPal system to validate
  $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Host: http://www.sandbox.paypal.com\r\n";
  //$header .= "Host: http://www.paypal.com\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
 
  // url for paypal sandbox
  $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);   
 
  // url for payal
  // $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
  // If possible, securely post back to paypal using HTTPS
  // Your PHP server will need to be SSL enabled
  // $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
 
  if (!$fp) {
    // HTTP ERROR
  } else {
    fputs ($fp, $header . $req);
    // read the body data
    $res = '';
    $headerdone = false;
    while (!feof($fp)) {
      $line = fgets ($fp, 1024);
      if (strcmp($line, "\r\n") == 0) {
        // read the header
        $headerdone = true;
      }
      else if ($headerdone) {
        // header has been read. now read the contents
        $res .= $line;
      }
    }
 
    // parse the data
    $lines = explode("\n", $res);
    $keyarray = array();
    if (strcmp ($lines[0], "SUCCESS") == 0) {
      for ($i=1; $i<count($lines);$i++){
        list($key,$val) = explode("=", $lines[$i]);
        $keyarray[urldecode($key)] = urldecode($val);
      }
      // check the payment_status is Completed
      // check that txn_id has not been previously processed
      // check that receiver_email is your Primary PayPal email
      // check that payment_amount/payment_currency are correct
      // process payment
      $firstname = $keyarray['first_name'];
      $lastname = $keyarray['last_name'];
      $itemname = $keyarray['item_name'];
      $amount = $keyarray['payment_gross'];
 
      echo ("<p><h3>Thank you for your purchase!</h3></p>");
 
      echo ("<b>Payment Details</b><br>\n");
      echo ("<li>Name: $firstname $lastname</li>\n");
      echo ("<li>Item: $itemname</li>\n");
      echo ("<li>Amount: $amount</li>\n");
      echo ("");
    }
    else if (strcmp ($lines[0], "FAIL") == 0) {
      // log for manual investigation
    }
  }
  fclose ($fp);*/
?>