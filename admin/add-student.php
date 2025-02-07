<?php
include_once '../server/conn.php'; 
checkLogin(); 

$errors = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'] ?? '';
    $national_id = $_POST['national_id'] ?? '';
    $college_id = $_POST['college'] ?? '';
    $year = $_POST['year'] ?? '';
    $blood_type = $_POST['blood_type'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';

    $password = password_hash($national_id . '@', PASSWORD_BCRYPT);

    if ($student_name && $national_id && $college_id && $year && $blood_type && $phone && $email) {
        $db = new Database();
        $conn = $db->connect();

        try {
            $checkSql = "SELECT COUNT(*) FROM students WHERE national_id = :national_id";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->execute([':national_id' => $national_id]);
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $errors = "!الطالب موجود بالفعل";
            } else {
                $sql = "INSERT INTO students (student_name, national_id, college_id, year, blood_type, phone, email, password) 
                        VALUES (:student_name, :national_id, :college_id, :year, :blood_type, :phone, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':student_name' => $student_name,
                    ':national_id' => $national_id,
                    ':college_id' => $college_id,
                    ':year' => $year,
                    ':blood_type' => $blood_type,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':password' => $password,
                ]);
                header("Location: students.php");
                exit();
            }
        } catch (PDOException $e) {
            $errors = "حدث خطأ أثناء إضافة الطالب: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    } else {
        $errors = "تأكد من إدخال جميع الحقول المطلوبة";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">
<head>
    <title>بنك الدم الإلكتروني</title>
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
        <div id="page">
            <div class="layout-wrap">
                <?php include 'inc/slide.php'; ?>
                <div class="section-content-right">
                    <?php include 'inc/header.php'; ?>
                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>إضافة طالب جديد</h3>
                                    <?php if (!empty($errors)) : ?>
                                        <div class="alert alert-danger"><?php echo $errors; ?></div>
                                    <?php endif; ?>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li><a href="index.php"><div class="text-tiny">اللوحة الرئيسية</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><a href="students.php"><div class="text-tiny">الطلاب</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><div class="text-tiny">إضافة طالب جديد</div></li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="#" method="POST" enctype="multipart/form-data">
                                        <fieldset class="name">
                                            <div class="body-title">اسم الطالب <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم الطالب" name="student_name" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">الرقم القومي <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="الرقم القومي" name="national_id" required="">
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
                                                    echo '<option value="' . $college['id'] . '">' . $college['name'] . '</option>';
                                                }
                                                $conn = null;
                                                ?>
                                            </select>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اختيار الفرقة <span class="tf-color-1">*</span></div>
                                            <select name="year" class="flex-grow" required="">
                                                <option value="">اختر الفرقة</option>
                                                <option value="الفرقة الأولى">الفرقة الأولى</option>
                                                <option value="الفرقة الثانية">الفرقة الثانية</option>
                                                <option value="الفرقة الثالثه">الفرقة الثالثه</option>
                                                <option value="الفرقة الرابعه">الفرقة الرابعه</option>
                                            </select>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اختيار الفصيلة <span class="tf-color-1">*</span></div>
                                            <select name="blood_type" class="flex-grow" required="">
                                                <option value="">اختر الفصيلة</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </select>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="tel" placeholder="رقم الهاتف" name="phone" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">الإيميل <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="email" placeholder="الإيميل" name="email" required="">
                                        </fieldset>
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">إضافة متبرع</button>
                                        </div>
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
