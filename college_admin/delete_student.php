<?php
include_once '../server/conn.php';
checkLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'] ?? null;

    if ($student_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM students WHERE id = :id");
            $stmt->bindParam(':id', $student_id);
            $stmt->execute();
            $_SESSION['success'] = 'تم حذف الطالب بنجاح.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'حدث خطأ أثناء حذف الطالب: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'المعطيات غير صحيحة.';
    }

    header("Location: students.php");
    exit();
}
?>
