<?php
include 'db_connector.php';


if (isset($_GET['subject'])) {
   $subject = $_GET['subject'];
   $query = "SELECT * FROM schedule WHERE subject = ?";
   $stmt = $connection->prepare($query);
   $stmt->bind_param("s", $subject);
   $stmt->execute();
   $result = $stmt->get_result();


   if ($result->num_rows > 0) {
       $added_count = 0;
       $existing_count = 0;
       while ($row = $result->fetch_assoc()) {
           $check_query = "SELECT * FROM enlisted WHERE subject = ? AND section = ? AND day = ? AND start_time = ? AND end_time = ?";
           $check_stmt = $connection->prepare($check_query);
           $check_stmt->bind_param(
               "sisss",
               $row['subject'],
               $row['section'],
               $row['day'],
               $row['start_time'],
               $row['end_time']
           );
           $check_stmt->execute();
           $check_result = $check_stmt->get_result();


           if ($check_result->num_rows == 0) {
               $insert_query = "INSERT INTO enlisted (subject, section, day, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
               $insert_stmt = $connection->prepare($insert_query);
               $insert_stmt->bind_param(
                   "sisss",
                   $row['subject'],
                   $row['section'],
                   $row['day'],
                   $row['start_time'],
                   $row['end_time']
               );
               $insert_stmt->execute();
               $insert_stmt->close();
               $added_count++;
           } else {
               $existing_count++;
           }
           $check_stmt->close();
       }


       if ($added_count > 0) {
           echo "Selected schedules added. ($added_count new, $existing_count existing)";
       } else {
           echo "All selected schedules already exist.";
       }
   } else {
       echo "No schedules found for the selected subject.";
   }


   $stmt->close();
} else {
   echo "Subject parameter is missing.";
}
$connection->close();
?>



