<?php
include_once "config.php";

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(!$connection){
        throw new Exception("Cannot connection with Database");
    }else{
        echo "Connected";
        //INSERT QUERY
        //echo mysqli_query($connection, "insert into tasks (task, date) values ('tomorrow class', '2019-05-20')");
        //SELECT QUERY
//       $result = mysqli_query($connection, "select * from tasks");
//        while($data = mysqli_fetch_assoc($result)){
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
//        }
        //mysqli_query($connection, "delete from tasks");

        //mysqli_close($connection);
    }