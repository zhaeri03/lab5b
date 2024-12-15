<?php
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'Lab_5b');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT matric, name, accessLevel FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($matric, $name, $accessLevel);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $accessLevel = $_POST['accessLevel'];

    $sql = "UPDATE users SET matric = ?, name = ?, accessLevel = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $matric, $name, $accessLevel, $id);

    if ($stmt->execute()) {
        header("Location: display.php");
        exit();
    } else {
        echo "<p>Error updating record: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Update User</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" value="<?= htmlspecialchars($matric) ?>" required><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required><br>
        <label for="accessLevel">Access Level:</label>
        <select id="accessLevel" name="accessLevel">
            <option value="Student" <?= $accessLevel == 'Student' ? 'selected' : '' ?>>Student</option>
            <option value="Lecturer" <?= $accessLevel == 'Lecturer' ? 'selected' : '' ?>>Lecturer</option>
        </select><br>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>
