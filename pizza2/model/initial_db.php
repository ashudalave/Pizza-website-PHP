<?php

function initial_db($db) {
    try {
        $query = 'delete from order_topping;';
        $query.='delete from pizza_orders;';
        $query.='delete from pizza_size;';
        $query.='delete from toppings;';
        $query.='delete from pizza_sys_tab;';
        $query.='insert into pizza_sys_tab values (1);';
        $query.="insert into toppings values (1,'Pepperoni');";
        $query.="insert into pizza_size values (1,'Small');";
        $query.='delete from undelivered_orders;';
        $query.='delete from inventory;';
        $query.="insert into inventory values (11,'flour', 100);";
        $query.="insert into inventory values (12,'cheese', 100);";
      
        $statement = $db->prepare($query);
        $statement->execute();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('errors/database_error.php');
        exit();
    }
    return $statement;
}
?>

