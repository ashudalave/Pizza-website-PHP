<?php

require('../../util/main.php');
require('../../model/database.php');
require('../../model/day_db.php');
require('../../model/order_db.php');
require('../../model/inventory_db.php');
require('../../model/initial_db.php');
require('curl_fns.php');
require('day_helpers.php');

$spot = strpos($app_path, 'pizza2');
$part = substr($app_path, 0, $spot);
$base_url = $_SERVER['SERVER_NAME'] . $part . 'proj2_server/rest';

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_orders';
    }
}
if ($action == 'initial_db') {
    try {
        initial_db($db);
        post_day($base_url, 0, $error_message);
        header("Location: .");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include ('../errors/database_error.php');
        exit();
    }
} else if ($action == 'list_orders') {
    try {
        $current_day = get_current_day($db);
        $todays_orders = get_todays_orders($db, $current_day);
      //  $start_inventory = get_start_inventory();
        $error_message = null;
        $supply = get_supply_orders($base_url, $error_message);
        $undelivered_orders = get_undelivered_orders($db);
        $inventory = get_inventory($db);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('errors/database_error.php');
        exit();
    }

    include('day_list.php');
} else if ($action == 'change_to_nextday') {
    try {
        $current_day = get_current_day($db);
        $todays_orders = get_todays_orders($db, $current_day);
        $next_day = $current_day + 1;
        change_to_finished($db, $current_day);
        increment_day($db, $next_day);
        $current_day = $next_day;

        $error_message = null;
        post_day($base_url, $next_day, $error_message);

        // credit now-delivered orders
        $undelivered_orders = get_undelivered_orders($db);
        $supply = get_supply_orders($base_url, $error_message);
        record_deliveries($db, $supply, $undelivered_orders);
        
        // Using updated inventory, figure out what to order, if anything
        $inventory = get_inventory($db);
        error_log('inventory; '. print_r($inventory, true));

        order_supplies($db, $inventory, $base_url);
        $undelivered_orders = get_undelivered_orders($db); // for display
        // TODO:temp: get latest supply for display (not reqd)
        $supply = get_supply_orders($base_url, $error_message);
        include('day_list.php');
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('errors/database_error.php');
        exit();
    }
}

?>