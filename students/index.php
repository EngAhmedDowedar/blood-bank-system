<?php
include_once '../server/conn.php'; 

$errors = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استخدام htmlspecialchars() لتجنب XSS
    $student_name = htmlspecialchars(trim($_POST['student_name'] ?? ''));
    $national_id = htmlspecialchars(trim($_POST['national_id'] ?? ''));
    $college_id = htmlspecialchars(trim($_POST['college'] ?? ''));
    $year = htmlspecialchars(trim($_POST['year'] ?? ''));
    $blood_type = htmlspecialchars(trim($_POST['blood_type'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));

    if (empty($student_name) || empty($national_id) || empty($college_id) || empty($year) || empty($blood_type) || empty($phone) || empty($email)) {
        $errors = "تأكد من إدخال جميع الحقول المطلوبة";
    } elseif (!preg_match("/^[\p{Arabic}\p{Latin}\s]+$/u", $student_name)) {
        $errors = "اسم الطالب يجب أن يحتوي على حروف فقط (عربي أو إنجليزي)";
    } elseif (!preg_match("/^\d{14}$/", $national_id)) {
        $errors = "الرقم القومي يجب أن يتكون من 14 رقمًا";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $errors = "رقم الهاتف يجب أن يتكون من 10 أرقام";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = "الإيميل غير صحيح";
    } else {
        $password = password_hash($national_id . '@', PASSWORD_BCRYPT);

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
                $sql = "INSERT INTO students (student_name, national_id, college_id, year, blood_type, phone, email, password, status) 
                        VALUES (:student_name, :national_id, :college_id, :year, :blood_type, :phone, :email, :password, 'غير مفعل')";
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
                $success = "تم إضافة الطالب بنجاح!";
                header("Location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            $errors = "حدث خطأ أثناء إضافة الطالب: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">
<head>
    <title>قطرة أمل</title>
    <!-- CSS files -->
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

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <!-- Custom JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        function validateInput(evt) {
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();
            }
        }

        // عرض النافذة المنبثقة
        <?php if (!empty($success)) : ?>
            window.onload = function() {
                swal("نجاح!", "<?php echo $success; ?>", "success");
            };
        <?php elseif (!empty($errors)) : ?>
            window.onload = function() {
                swal("خطأ!", "<?php echo $errors; ?>", "error");
            };
        <?php endif; ?>
    </script>
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
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="#" method="POST" enctype="multipart/form-data">
                                        <fieldset class="name">
                                            <div class="body-title">اسم الطالب <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم الطالب" name="student_name" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">الرقم القومي <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="tel" placeholder="الرقم القومي" name="national_id" required="" maxlength="14" onkeypress="validateInput(event)">
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
                                                <option value="الفرقة الخامسة">الفرقة الخامسة</option>
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
                                            <input class="flex-grow" type="tel" placeholder="رقم الهاتف" name="phone" required="" maxlength="10" onkeypress="validateInput(event)">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="email" placeholder="البريد الإلكتروني" name="email" required="">
                                        </fieldset>
                                        <div class="footer-form">
                                            <button class="tf-button w208" type="submit">تقديم الاستمارة</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'inc/footer.php'; ?>
            </div>
        </div>
    </div>
</body>
</html>
