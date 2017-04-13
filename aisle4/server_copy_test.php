<?php

   //The connection data to access the database
   $con=mysqli_connect("localhost","server","aisle4","aisle4");

   //Validation to make sure that we've successfully accessed the database
   if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

   $command = $_POST['command']; //The command type sent to the server
   $itemName = $_POST['itemName']; //These and below will be useful variables for commands
   $item1ID = $_POST['item1ID'];
   $item2ID = $_POST['item2ID'];
   $steps = $_POST['steps'];
   $travel_time = $_POST['travel_time'];

   $sql = 'NULL'; //Default value for sql string

   //The command structure of the server code.
   if($command == 'new_item' && $itemName != NULL && $itemName != ''){
      $sql="INSERT INTO items (name) VALUES ('$itemName')";
   }
   if($command == 'new_item_to_item' && $item1ID != NULL && $item2ID != NULL && $steps != NULL && $travel_time != NULL){
      $sql="INSERT INTO item_to_item (item1_id, item2_id, steps, travel_time) VALUES ($item1ID, $item2ID, $steps, $travel_time)";
   }

   echo $sql;

   if (mysqli_query($con,$sql)) {
      echo "Values have been inserted successfully.\n";
   }else{
      echo "A database error has occured. Values recieved are: command = ", $command, " name = ",  $itemName, " 1stID=", $item1ID, " 2ndID=", $item2ID, " steps=", $steps, " travel time=", $travel_time;
   }

   
   mysqli_close($con);

?>