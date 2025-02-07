<?php
include_once '../server/conn.php'; 
checkLogin();

$studentId = null;
$student = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $db = new Database();
    $conn = $db->connect();
    try {
        $sql = "SELECT * FROM students WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $studentId);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$student) {
            die('الطالب غير موجود');
        }
    } catch (PDOException $e) {
        echo "حدث خطأ أثناء جلب بيانات الطالب: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $student_name = $_POST['student_name'];
    $national_id = $_POST['national_id'];
    $college_id = $_POST['college'];
    $year = $_POST['year'];
    $blood_type = $_POST['blood_type'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $db = new Database();
    $conn = $db->connect();
    try {
        $sql = "UPDATE students SET student_name = :student_name, national_id = :national_id, college_id = :college_id, 
                year = :year, blood_type = :blood_type, phone = :phone, email = :email" . 
                ($password ? ", password = :password" : "") . 
                " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':national_id', $national_id);
        $stmt->bindParam(':college_id', $college_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':blood_type', $blood_type);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed_password);
        }
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('Location: students.php');
        exit();
    } catch (PDOException $e) {
        echo "خطأ أثناء تحديث البيانات: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">
<head>
    <title>تعديل معلومات الكلية</title>
    <meta charset="utf-8">
    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/animation.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/font/fonts.css">
    <link rel="stylesheet" href="../assets/icon/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/custom.css">
</head>
<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">
                <?php include 'inc/slide.php'; ?>
                <div class="section-content-right">
                    <?php include 'inc/header.php'; ?>
                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>إضافة طالب جديد</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li><a href="index.php"><div class="text-tiny">اللوحة الرئيسية</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><a href="students.php"><div class="text-tiny">الطلاب</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><div class="text-tiny">إضافة طالب جديد</div></li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="" method="POST" enctype="multipart/form-data">
                                        <?php if ($student): ?>
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']); ?>">
                                            <fieldset class="name">
                                                <div class="body-title">اسم الطالب <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="text" placeholder="اسم الطالب" name="student_name" tabindex="0" value="<?= htmlspecialchars($student['student_name']); ?>" required="">
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">الرقم القومي <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="text" placeholder="الرقم القومي" name="national_id" tabindex="0" value="<?= htmlspecialchars($student['national_id']); ?>" required="">
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">اختيار الكلية <span class="tf-color-1">*</span></div>
                                                <select name="college" class="flex-grow" required="">
                                                    <option value="">اختر الكلية</option>
                                                    <?php
                                                    $db = new Database();
                                                    $conn = $db->connect();
                                                    $sql = "SELECT id, name FROM colleges";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($colleges as $college) {
                                                        $selected = $college['id'] == $student['college_id'] ? 'selected' : '';
                                                        echo '<option value="' . $college['id'] . '" ' . $selected . '>' . $college['name'] . '</option>';
                                                    }
                                                    $conn = null;
                                                    ?>
                                                </select>
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">اختيار الفرقة <span class="tf-color-1">*</span></div>
                                                <select name="year" class="flex-grow" required="">
                                                    <option value="">اختر الفرقة</option>
                                                    <option value="الفرقة الأولى" <?= ($student['year'] == "الفرقة الأولى") ? 'selected' : ''; ?>>الفرقة الأولى</option>
                                                    <option value="الفرقة الثانية" <?= ($student['year'] == "الفرقة الثانية") ? 'selected' : ''; ?>>الفرقة الثانية</option>
                                                    <option value="الفرقة الثالثه" <?= ($student['year'] == "الفرقة الثالثه") ? 'selected' : ''; ?>>الفرقة الثالثه</option>
                                                    <option value="الفرقة الرابعه" <?= ($student['year'] == "الفرقة الرابعه") ? 'selected' : ''; ?>>الفرقة الرابعه</option>
                                                </select>
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">اختيار الفصيلة <span class="tf-color-1">*</span></div>
                                                <select name="blood_type" class="flex-grow" required="">
                                                    <option value="">اختر الفصيلة</option>
                                                    <option value="A+" <?= ($student['blood_type'] == "A+") ? 'selected' : ''; ?>>A+</option>
                                                    <option value="A-" <?= ($student['blood_type'] == "A-") ? 'selected' : ''; ?>>A-</option>
                                                    <option value="B+" <?= ($student['blood_type'] == "B+") ? 'selected' : ''; ?>>B+</option>
                                                    <option value="B-" <?= ($student['blood_type'] == "B-") ? 'selected' : ''; ?>>B-</option>
                                                    <option value="O+" <?= ($student['blood_type'] == "O+") ? 'selected' : ''; ?>>O+</option>
                                                    <option value="O-" <?= ($student['blood_type'] == "O-") ? 'selected' : ''; ?>>O-</option>
                                                    <option value="AB+" <?= ($student['blood_type'] == "AB+") ? 'selected' : ''; ?>>AB+</option>
                                                    <option value="AB-" <?= ($student['blood_type'] == "AB-") ? 'selected' : ''; ?>>AB-</option>
                                                </select>
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="text" placeholder="رقم الهاتف" name="phone" tabindex="0" value="<?= htmlspecialchars($student['phone']); ?>" required="">
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">البريد الالكتروني <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="email" placeholder="البريد الالكتروني" name="email" tabindex="0" value="<?= htmlspecialchars($student['email']); ?>" required="">
                                            </fieldset>
                                            <fieldset class="name">
                                                <div class="body-title">كلمة المرور <span class="tf-color-1">* (اختياري)</span></div>
                                                <input class="flex-grow" type="password" placeholder="كلمة المرور" name="password" tabindex="0" value="">
                                                <small>يمكنك ترك هذا الحقل فارغًا إذا لم ترغب في تغيير كلمة المرور</small>
                                            </fieldset>
                                            <button class="tf-button w208">تحديث البيانات</button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
