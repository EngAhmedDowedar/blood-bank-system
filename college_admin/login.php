<?php
include_once '../server/conn.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    if (!verifyCsrfToken($_POST['csrf_token'])) {
        $error = "رمز التحقق غير صالح. حاول مرة أخرى.";
    } else {
        $username = cleanInput($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM colleges WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['college'] = $user['college'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['college_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "اسم المستخدم أو كلمة المرور غير صحيحة.";
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body">
    <div id="wrapper">
        <div id="page">
            <div class="layout-wrap">
                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="index.php" id="site-logo-inner">
                            <img id="logo_header" alt="" src="../assets/images/logo/logo.png">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="index.php">
                                    <img id="logo_header_mobile" alt="" src="../assets/images/logo/logo.png" data-width="154px" data-height="52px">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>تسجيل الدخول</h3>
                                </div>
                                <?php if (isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; unset($error); } ?>
                                <div class="wg-box">
                                    <form class="form-new-admin form-style-1" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <fieldset class="name">
                                            <div class="body-title">اسم المستخدم <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم المستخدم" name="username" required>
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">كلمة المرور <span class="tf-color-1">*</span></div>
                                            <div class="password-container">
                                                <input class="flex-grow" type="password" id="password" placeholder="كلمة المرور" name="password" required>
                                                <button type="button" id="togglePassword" class="toggle-password">
                                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </fieldset>
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit" name="login">تسجيل الدخول</button>
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
