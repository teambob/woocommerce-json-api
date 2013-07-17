<?php
function curl_post($url, array $post = NULL, array $options = array()) 
{ 
    $defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => http_build_query($post) 
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
} 
function verifyHasErrors($test,$result, $code) {
  echo "Verifying: $test\n";
  $r = json_decode($result,true);
  if ( $r['status'] == false && $r['errors'][0]['code'] == $code) {
    echo "PASSED\n";
  } else {
    echo "FAILED\n";
  }
}
function verifyHasWarnings($test,$result, $code) {
  echo "Verifying: $test\n";
  $r = json_decode($result,true);
  if ( $r['status'] == true && $r['warnings'][0]['code'] == $code) {
    echo "PASSED\n";
  } else {
    echo "FAILED\n";
  }
}
function verifySuccess($test,$result) {
  echo "Verifying: $test\n";
  $r = json_decode($result,true);
  if ( $r['status'] == true) {
    echo "PASSED\n";
  } else {
    echo "FAILED\n";
    echo "Result is: " . $result . "\n\n";
  }
}
$url = 'http://woo.localhost/c6db13944977ac5f7a8305bbfb06fd6a/';
$data = array(
  'action'      => 'woocommerce_json_api',
  'proc'        => 'get_categories',
  'arguments'   => array(
    'token' => '1234',
  )
);
$result = curl_post($url,$data);
verifySuccess("Get Categories", $result);

$data = array(
  'action'      => 'woocommerce_json_api',
  'proc'        => 'get_categories',
  'arguments'   => array(
    'token' => '1234',
    'order_by' => 'non-exisent-column'
  )
);
$result = curl_post($url,$data);
verifyHasErrors("Sort by bad column", $result, -5);

$data = array(
  'action'      => 'woocommerce_json_api',
  'proc'        => 'get_categories',
  'arguments'   => array(
    'token' => '1234',
    'ids' => array(33,54),
  )
);
$result = curl_post($url,$data);
echo $result;
verifySuccess("Get Categories by ids", $result);