<?php
include 'db_connector.php';

$query = "SELECT DISTINCT subject FROM enlisted";
$result = $connection->query($query);

if ($result->num_rows > 0) {
    echo "<div class='added-subjects'>";
    echo "<h2>Enlisted Subjects</h2>";
    echo "<div id='addedSubjects'>";
    while ($row = $result->fetch_assoc()) {
        $subject = htmlspecialchars($row['subject']);
        echo "<div class='schedule-item' id='subject-$subject'>";
        echo $subject;
        echo "<button class='delete-sub' onclick=\"deleteEnlistedSubject('$subject')\">Delete</button>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='added-subjects'>";
    echo "<h2>Added Subjects</h2>";
    echo "No added subjects found.";
    echo "</div>";
}

$connection->close();
?>
