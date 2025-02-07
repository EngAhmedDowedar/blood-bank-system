<?php
include_once '../server/conn.php'; 
checkLogin(); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['name'], $_POST['dean_name'], $_POST['email'], $_POST['username'], $_POST['password'])) {
        $name = cleanInput($_POST['name']);
        $dean_name = cleanInput($_POST['dean_name']);
        $email = cleanInput($_POST['email']);
        $username = cleanInput($_POST['username']);
        $password = cleanInput($_POST['password']);

        $duplicateCheckStmt = $conn->prepare("SELECT COUNT(*) FROM colleges WHERE name = :name OR email = :email OR username = :username");
        $duplicateCheckStmt->execute([
            'name' => $name,
            'email' => $email,
            'username' => $username,
        ]);
        
        $count = $duplicateCheckStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error'] = 'هذا الاسم أو البريد الإلكتروني أو اسم المستخدم موجود بالفعل. يرجى استخدام اسم آخر';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $conn->prepare("INSERT INTO colleges (name, dean_name, email, username, password) VALUES (:name, :dean_name, :email, :username, :password)");
                $stmt->execute([
                    'name' => $name,
                    'dean_name' => $dean_name,
                    'email' => $email,
                    'username' => $username,
                    'password' => $hashed_password,
                ]);
                header("Location: colleges.php");
                exit(); 
            } catch (PDOException $e) {
                $_SESSION['error'] = 'حدث خطأ أثناء إضافة الكلية: ';
            }
        }
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
        <div id="page" class="">
            <div class="layout-wrap">
                <?php include 'inc/slide.php'; ?>
                <div class="section-content-right">
                    <?php include 'inc/header.php'; ?>
                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>معلومات الكلية</h3>
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }
                                    ?>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="index.php">
                                                <div class="text-tiny">اللوحة الرئيسية</div>
                                            </a>
                                        </li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li>
                                            <a href="colleges.php">
                                                <div class="text-tiny">الكليات</div>
                                            </a>
                                        </li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><div class="text-tiny">إضافة كلية جديدة</div></li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="" method="POST" enctype="multipart/form-data">
                                        <fieldset class="name">
                                            <div class="body-title">اسم الكلية <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم الكلية" name="name" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اسم العميد <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم العميد" name="dean_name" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="email" placeholder="البريد الإلكتروني" name="email" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اسم المستخدم <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم المستخدم" name="username" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">كلمة المرور <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="كلمة المرور" name="password" required="">
                                        </fieldset>
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">إضافة</button>
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
