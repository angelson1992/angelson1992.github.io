<?php

   //The connection data to access the database
   $con=mysqli_connect("localhost","server","aisle4","aisle4");

   //Validation to make sure that we've successfully accessed the database
   if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

   $command = $_GET['command']; //The command type sent to the server
   $itemName = $_GET['itemName']; //These and below will be useful variables for commands
   $item1ID = $_GET['item1ID'];
   $item2ID = $_GET['item2ID'];
   $item1Name = $_GET['item1Name'];
   $item2Name = $_GET['item2Name'];
   $steps = $_GET['steps'];
   $travel_time = $_GET['travel_time'];

   $sql = 'NULL'; //Default value for sql string

   //The command structure of the server code.
   if($command == 'new_item' && $itemName != NULL && $itemName != ''){
      $sql="INSERT INTO items (name) VALUES ('$itemName')";
   }
   if($command == 'new_item_to_item' && $item1ID != NULL && $item2ID != NULL && $steps != NULL && $travel_time != NULL){
      $sql="INSERT INTO item_to_item (item1_id, item2_id, steps, travel_time) VALUES ($item1ID, $item2ID, $steps, $travel_time)";
   }
   if($command == 'new_item_to_item' && $item1Name != NULL && $item2Name != NULL && $steps != NULL && $travel_time != NULL){
      $result = mysqli_query($con,"SELECT id FROM items where name='$item1Name'");
      $row = mysqli_fetch_array($result);
      $dynItem1ID = $row[0];
      $result2 = mysqli_query($con,"SELECT id FROM items where name='$item2Name'");
      $row2 = mysqli_fetch_array($result2);
      $dynItem2ID = $row2[0];
      $sql="INSERT INTO item_to_item (item1_id, item2_id, steps, travel_time) VALUES ($dynItem1ID, $dynItem2ID, $steps, $travel_time)";
   }

   echo $sql;

   if (mysqli_query($con,$sql)) {
      echo "Values have been inserted successfully.\n";
   }else{
      echo "A database error has occured. Values recieved are: command = ", $command, " name = ",  $itemName, " 1stID=", $item1ID, " 2ndID=", $item2ID, " steps=", $steps, " travel time=", $travel_time, " 1stName=", $item1Name, " 2ndName=", $item2Name;
   }

   
   mysqli_close($con);

?>