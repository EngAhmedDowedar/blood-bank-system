<?php
include_once '../server/conn.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($student_id > 0) {
        $sql = "UPDATE students SET status = 'مفعل' WHERE id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            header("Location: pending-requests.php?message=Student activated successfully.");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        header("Location: pending-requests.php?error=Invalid student ID.");
        exit();
    }
} else {
    header("Location: pending-requests.php");
    exit();
}
?>
