<?php
include_once '../server/conn.php';
checkLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $college_id = $_POST['college_id'] ?? null;

    if ($college_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM colleges WHERE id = :id");
            $stmt->bindParam(':id', $college_id);
            $stmt->execute();
            $_SESSION['success'] = 'تم حذف الكلية بنجاح.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'حدث خطأ أثناء حذف الكلية: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'المعطيات غير صحيحة.';
    }

    header("Location: colleges.php");
    exit();
}
?>
