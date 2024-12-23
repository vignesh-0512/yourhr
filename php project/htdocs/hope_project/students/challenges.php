
<?php
include '../db.php'; // Adjust path as needed

$sql = "SELECT question_id, title, description, testcase, output, difficulty FROM challenge";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='challenge'>";
        echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
        echo "<p class='description'>" . htmlspecialchars($row["description"]) . "</p>";
        echo "<pre class='testcase'><strong>Testcase:</strong> " . htmlspecialchars($row["testcase"]) . "</pre>";
        echo "<p class='difficulty'><strong>Difficulty:</strong> " . htmlspecialchars($row["difficulty"]) . "</p>";
        echo "<pre class='output'><strong>Output:</strong> " . htmlspecialchars($row["output"]) . "</pre>";
        echo "<button class='solve-btn' onclick='openEditor(" . $row['question_id'] . ")'>Solve Challenge</button>";
        echo "</div>";
    }
} else {
    echo "No challenges found.";
}
$conn->close();
?>
