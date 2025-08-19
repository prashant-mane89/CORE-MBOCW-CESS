<?php
require_once '../config/db.php';

$category_id = intval($_GET['category_id']);
$query = $conn->prepare("SELECT id, name FROM project_types WHERE category_id = ?");
$query->bind_param("i", $category_id);
$query->execute();
$result = $query->get_result();

echo '<option value="">-- Select Type --</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
