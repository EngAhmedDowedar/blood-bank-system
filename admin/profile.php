<?php
include_once '../server/conn.php'; // Ensure this file sets up the Database class

checkLogin(); // Ensure this checks for admin login

// Create an instance of the Database class and connect
$database = new Database();
$pdo = $database->connect();

// Fetch existing admin data using the logged-in admin's ID
$adminId = $_SESSION['admin_id']; // Change this according to how you store the admin ID
$query = $pdo->prepare("SELECT name, phone, email, profile_image, role FROM admin WHERE id = :id");
$query->execute(['id' => $adminId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Check if user was found
if (!$user) {
    echo '<div class="alert alert-danger">لا يمكن العثور على المستخدم.</div>';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = cleanInput($_POST['name']);
    $mobile = cleanInput($_POST['phone']);
    $email = cleanInput($_POST['email']);
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $newPasswordConfirmation = $_POST['new_password_confirmation'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'assistant_admin';

    // Validate the old password
    $stmt = $pdo->prepare("SELECT password FROM admin WHERE id = :id");
    $stmt->execute(['id' => $adminId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($oldPassword, $userData['password'])) {
        // Update user information
        $updateQuery = $pdo->prepare("UPDATE admin SET name = :name, phone = :mobile, email = :email, role = :role WHERE id = :id");
        $updateQuery->execute([
            'name' => $name,
            'mobile' => $mobile,
            'email' => $email,
            'role' => $role,
            'id' => $adminId
        ]);

        // Handle profile image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["profile_image"]["name"]);
            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile);

            $updateImageQuery = $pdo->prepare("UPDATE admin SET profile_image = :profile_image WHERE id = :id");
            $updateImageQuery->execute([
                'profile_image' => $targetFile,
                'id' => $adminId
            ]);
        }

        // If new password is provided and confirmed
        if (!empty($newPassword) && $newPassword === $newPasswordConfirmation) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password in the database
            $updatePasswordQuery = $pdo->prepare("UPDATE admin SET password = :password WHERE id = :id");
            $updatePasswordQuery->execute([
                'password' => $hashedPassword,
                'id' => $adminId
            ]);
        }

        // Redirect or show success message
        header("Location: settings.php?success=1");
        exit;
    } else {
        $_SESSION['error'] = "كلمة المرور القديمة غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">
<head>
    <title>بنك الدم الإلكتروني - إعدادات المسؤول</title>
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
<body>
    <div id="wrapper">
        <div class="layout-wrap">
            <?php include 'inc/slide.php'; ?>
            <div class="section-content-right">
                <?php include 'inc/header.php'; ?>
                <div class="main-content">
                    <style>
                        .text-danger {
                            font-size: initial;
                            line-height: 36px;
                        }
                        .alert {
                            font-size: initial;
                        }
                    </style>
                    <div class="main-content-inner">
                        <div class="main-content-wrap">
                            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                <h3>الإعدادات</h3>
                                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                    <li>
                                        <a href="index.php">
                                            <div class="text-tiny">الرئيسية</div>
                                        </a>
                                    </li>
                                    <li>
                                        <i class="icon-chevron-right"></i>
                                    </li>
                                    <li>
                                        <div class="text-tiny">الإعدادات</div>
                                    </li>
                                </ul>
                            </div>
                        <div class="wg-box">
                            <form name="account_edit_form" action="#" method="POST"
                                class="form-new-product form-style-1 needs-validation" enctype="multipart/form-data" novalidate="">

                                <fieldset class="name">
                                    <div class="body-title">اسم النظام <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="اسم النظام" name="name" tabindex="0"
                                        value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </fieldset>
                                <fieldset class="name">
                                    <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="رقم الهاتف" name="phone" tabindex="0"
                                        value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                </fieldset>
                                <fieldset class="name">
                                    <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="البريد الإلكتروني" name="email"tabindex="0" 
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </fieldset>
                                <fieldset class="name">
                                    <div class="body-title">الدور <span class="tf-color-1">*</span></div>
                                    <select name="role" required>
                                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>مدير</option>
                                        <option value="assistant_admin" <?php echo $user['role'] == 'assistant_admin' ? 'selected' : ''; ?>>مساعد المدير</option>
                                    </select>
                                </fieldset>
                                <fieldset class="name">
                                    <div class="body-title">صورة الملف الشخصي <span class="tf-color-1">*</span></div>
                                    <input type="file" name="profile_image">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" width="100">
                                    <?php endif; ?>
                                </fieldset>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <h5 class="text-uppercase mb-0">تغيير كلمة المرور</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">كلمة المرور القديمة <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="كلمة المرور القديمة"
                                                id="old_password" name="old_password" required>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">كلمة المرور الجديدة <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="كلمة المرور الجديدة"
                                                id="new_password" name="new_password">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="name">
                                            <div class="body-title pb-3">تأكيد كلمة المرور الجديدة <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="تأكيد كلمة المرور الجديدة"
                                                id="new_password_confirmation" name="new_password_confirmation" >
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="tf-button w208">تحديث البيانات</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                            if (isset($_GET['success'])) {
                                echo '<div class="alert alert-success">تم تحديث البيانات بنجاح!</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
