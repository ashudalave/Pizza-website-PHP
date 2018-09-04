<?php
// used only for getting Location back from POST
include 'curl_helper.php';

function post_day($base_url, $day, &$error_message){
    error_log('post_day to server: '. $day);
    try {
        $url = $base_url.'/day/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $day);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));      
        curl_exec($ch);
        curl_close($ch);      
        $error_message = null;
    } catch (Exception $e) {
        error_log('error in client_post_day' . $e->getMessage());
        $error_message = 'Error: ' . $e->getMessage();
    }
    
}
// POST order and get back location, using curl_helper.php
function post_order1($base_url, $order, &$error_message){
    $url = $base_url.'/orders/';
    $location = null;
    $data = json_encode($order);
    try {
        curl_request_get_location($url, 'POST',
                $data,'application/json', $location);
        $error_message = null;
    } catch (Exception $e) {
        error_log('error in post_order1' . $e->getMessage());
        $error_message = 'Error: ' . $e->getMessage();
        return null;
    }
    return $location;
} 

function post_order($base_url, $order, &$error_message){
    
    try {
        $url = $base_url.'/orders/';
        $ch = curl_init($url);
        $data_string = json_encode($order);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        $response = curl_exec($ch);        
        curl_close($ch);         
        $error_message = null;
        return $response;
    } catch (Exception $e) {
        error_log('error in client_post_order' . $e->getMessage());
        $error_message = 'Error: ' . $e->getMessage();
    }
    
}

function get_supply_orders($base_url, &$error_message){  
    try {       
        $url = $base_url.'/orders/';
        $ch = curl_init($url);
        $verbose_file = null;
        curl_setopt($ch, CURLOPT_VERBOSE, true);                
        $verbose_file = fopen('php://temp', 'rw+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
        $response0 = curl_exec($ch);
        $response = json_decode($response0,true);
        curl_close($ch);              
        $error_message = null;      
        return $response;
    } catch (Exception $e) {
        error_log('error in client_get_supply_orders' . $e->getMessage());
        $error_message = 'Error: ' . $e->getMessage();
    }   
}

function get_one_supply_order($base_url,$its_orderid,&$error_message){
    
    try {
        $url = $base_url.'/orders/'.$its_orderid;
        $ch = curl_init($url);
               
        $verbose_file = null;
        curl_setopt($ch, CURLOPT_VERBOSE, true);                
        $verbose_file = fopen('php://temp', 'rw+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        $response0 = curl_exec($ch);
        $response = json_decode($response0,true);
        curl_close($ch);
        $error_message = null;   
        return $response;
    } catch (Exception $e) {
        error_log('error in client_get_one_supply_order' . $e->getMessage());
        $error_message = 'Error: ' . $e->getMessage();
    }   
}

