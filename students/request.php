<?php
include_once '../server/conn.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
$errors = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    } elseif (!preg_match("/^\d{11}$/", $phone)) {
        $errors = "رقم الهاتف يجب أن يتكون من 11 أرقام";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = "الإيميل غير صحيح";
    } else {
        // البحث عن متبرع بنفس فصيلة الدم وحالته مفعل
        $db = new Database();
        $conn = $db->connect();

        try {
            $donorSql = "SELECT email FROM students WHERE blood_type = :blood_type AND status = 'مفعل'";
            $donorStmt = $conn->prepare($donorSql);
            $donorStmt->execute([':blood_type' => $blood_type]);
            $donor_emails = $donorStmt->fetchAll(PDO::FETCH_COLUMN); // استرجاع جميع الإيميلات

            // إرسال بريد إلكتروني للمحتاج إذا كان هناك متبرعين
            if (!empty($donor_emails)) {
                $mail = new PHPMailer(true);
                try {
                    // إعداد خادم SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // عنوان خادم SMTP
                    $mail->SMTPAuth = true;
                    $mail->Username = 'dowedartech@gmail.com'; // بريدك الإلكتروني
                    $mail->Password = 'Your App password'; // كلمة مرور بريدك الإلكتروني
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // إعداد البريد للمحتاج
                    $mail->setFrom('dowedartech@gmail.com', 'قطرة أمل');
                    $mail->addAddress($email, $student_name); // البريد للمحتاج
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8'; // ضبط الترميز على UTF-8
                    $mail->Subject = 'تأكيد طلب فصيلة الدم';
                    $mail->Body    = 'مرحبًا ' . $student_name . '!<br>لقد تم تقديم طلبك لفصيلة الدم ' . $blood_type . ' بنجاح. يرجى التوجه للرعاية الصحية غدًا في الساعة 10 صباحًا.';

                    $mail->send();

                    // إعداد البريد للمتبرعين
                    foreach ($donor_emails as $donor_email) {
                        $mail->clearAddresses(); // مسح المستلم السابق
                        $mail->addAddress($donor_email); // البريد للمتبرع
                        $mail->Subject = 'طلب فصيلة دم';
                        $mail->CharSet = 'UTF-8'; // ضبط الترميز على UTF-8
                        $mail->Body    = 'مرحبًا!<br>هناك أحد الطلاب بأمس الحاجة لفصيلة دمك ' . $blood_type . '. إن كنت ترغب في التبرع، يرجى التوجه للرعاية الصحية غدًا في الساعة 10 صباحًا.';

                        $mail->send();
                    }
                    
                    // إذا تم إرسال البريد بنجاح
                    $success = "تم تسجيل طلبك بنجاح!";
                } catch (Exception $e) {
                    $errors = "حدث خطأ أثناء إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
                }
            }
        } catch (PDOException $e) {
            $errors = "حدث خطأ أثناء البحث عن المتبرع: " . $e->getMessage();
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
                                        <script>
                                            swal("خطأ!", "<?php echo $errors; ?>", "error");
                                        </script>
                                    <?php endif; ?>
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
                                                <option value="الفرقة الثالثة">الفرقة الثالثة</option>
                                                <option value="الفرقة الرابعة">الفرقة الرابعة</option>
                                                <option value="الفرقة الخامسة">الفرقة الخامسة</option>
                                            </select>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اختيار فصيلة الدم <span class="tf-color-1">*</span></div>
                                            <select name="blood_type" class="flex-grow" required="">
                                                <option value="">اختر فصيلة الدم</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="tel" placeholder="رقم الهاتف" name="phone" required="" maxlength="11" onkeypress="validateInput(event)">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="email" placeholder="البريد الإلكتروني" name="email" required="">
                                        </fieldset>
                                        <div class="footer-form">
                                            <button class="tf-button w208" type="submit">طلب الفصيلة</button>
                                        </div>
                                    </form>
                                    <?php if (!empty($success)) : ?>
                                        <script>
                                            swal("نجاح!", "<?php echo $success; ?>", "success");
                                        </script>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include 'inc/footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateInput(event) {
            const input = event.target;
            const charCode = (typeof event.which === "undefined") ? event.keyCode : event.which;
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>
