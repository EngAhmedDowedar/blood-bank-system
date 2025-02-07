<?php
session_start();
include_once '../server/conn.php'; 
checkLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (name, email, phone, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $admin_name, $email, $phone, $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "تم إنشاء الحساب بنجاح!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "حدث خطأ: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
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
                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="index.php" id="site-logo-inner">
                            <img id="logo_header" alt="" src="../assets/images/logo/logo.png"
                                data-light="images/logo/logo.png" data-dark="images/logo/logo.png">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">الصفحة الرئيسية</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="index.php">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">لوحة التحكم</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                        <ul class="menu-list">
                                <!-- إدارة الطلبات المعلقة -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-file"></i></div>
                                        <div class="text">الطلبات المعلقة</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="pending-requests.php" class="">
                                                <div class="text">عرض الطلبات</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                    
                                <!-- إدارة الكليات -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">الكليات</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="add-college.php" class="">
                                                <div class="text">إضافة كلية</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="colleges.php" class="">
                                                <div class="text">إدارة الكليات</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                    
                                <!-- إدارة فصائل الدم -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-droplet"></i></div>
                                        <div class="text">فصائل الدم</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="add-blood-type.php" class="">
                                                <div class="text">إضافة فصيلة دم</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="blood-types.php" class="">
                                                <div class="text">إدارة فصائل الدم</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                    
                                <!-- إدارة المتبرعين -->
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <div class="text">الطلاب</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="add-student.php" class="">
                                                <div class="text">إضافة طالب</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="students.php" class="">
                                                <div class="text">إدارة الطلاب</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                    
                                <!-- الإعدادات -->
                                <li class="menu-item">
                                    <a href="settings.php" class="">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">الإعدادات</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="index.php">
                                    <img id="logo_header_mobile" alt="" src="images/logo/bloodbank_logo.png"
                                        data-light="images/logo/bloodbank_logo.png" data-dark="images/logo/bloodbank_logo.png"
                                        data-width="154px" data-height="52px" data-retina="images/logo/bloodbank_logo.png">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>
                            </div>
                            <div class="header-grid">
                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="../assets/images/logo/admin_logo.png" alt="">
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>إنشاء حساب جديد</h3>
                                </div>
                                <?php
                                if (isset($_SESSION['message'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                                    unset($_SESSION['message']);
                                }
                                ?>
                                <div class="wg-box">
                                    <form class="form-new-admin form-style-1" action="" method="POST" enctype="multipart/form-data">
                                        <fieldset class="name">
                                            <div class="body-title">اسم الادمن <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم الادمن" name="admin_name" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="email" placeholder="البريد الإلكتروني" name="email" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="tel" placeholder="رقم الهاتف" name="phone" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">اسم المستخدم <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="اسم المستخدم" name="username" required="">
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">كلمة المرور <span class="tf-color-1">*</span></div>
                                            <div class="password-container">
                                                <input class="flex-grow" type="password" id="password" placeholder="كلمة المرور" name="password" required="">
                                                <button type="button" id="togglePassword" class="toggle-password">
                                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </fieldset>
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">إنشاء حساب</button>
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

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap-select.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
