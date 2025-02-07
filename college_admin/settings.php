<?php
include_once '../server/conn.php';
checkLogin();

$database = new Database();
$pdo = $database->connect();

$adminId = $_SESSION['college_id'];
$query = $pdo->prepare("SELECT name, phone, email, profile_image, role FROM colleges WHERE id = :id");
$query->execute(['id' => $adminId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<div class="alert alert-danger">لا يمكن العثور على المستخدم.</div>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = cleanInput($_POST['name']);
    $mobile = cleanInput($_POST['phone']);
    $email = cleanInput($_POST['email']);
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $newPasswordConfirmation = $_POST['new_password_confirmation'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'assistant_admin';

    $stmt = $pdo->prepare("SELECT password, profile_image FROM colleges WHERE id = :id");
    $stmt->execute(['id' => $adminId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($oldPassword, $userData['password'])) {
        $updateQuery = $pdo->prepare("UPDATE colleges SET name = :name, phone = :mobile, email = :email, role = :role WHERE id = :id");
        $updateQuery->execute([
            'name' => $name,
            'mobile' => $mobile,
            'email' => $email,
            'role' => $role,
            'id' => $adminId
        ]);

        if (!empty($_FILES['profile_image']['name'])) {
            $targetDir = "uploads/";
            if (!empty($userData['profile_image'])) {
                $oldFilePath = $targetDir . $userData['profile_image'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $fileExtension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $fileName = $adminId . '_' . time() . '.' . $fileExtension;
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
                $updateImageQuery = $pdo->prepare("UPDATE colleges SET profile_image = :profile_image WHERE id = :id");
                $updateImageQuery->execute([
                    'profile_image' => $fileName,
                    'id' => $adminId
                ]);
            }
        }

        if (!empty($newPassword) && $newPassword === $newPasswordConfirmation) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordQuery = $pdo->prepare("UPDATE colleges SET password = :password WHERE id = :id");
            $updatePasswordQuery->execute([
                'password' => $hashedPassword,
                'id' => $adminId
            ]);
        }

        header("Location: settings.php?success=1");
        exit;
    } else {
        $_SESSION['error'] = "كلمة المرور القديمة غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <title>بنك الدم الإلكتروني - إعدادات المسؤول</title>
    <meta charset="utf-8">
    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/font/fonts.css">
    <link rel="stylesheet" href="../assets/icon/style.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body class="body">
    <div id="wrapper">
        <div class="layout-wrap">
            <?php include 'inc/slide.php'; ?>
            <div class="section-content-right">
                <?php include 'inc/header.php'; ?>
                <div class="main-content">
                    <div class="main-content-inner">
                        <div class="main-content-wrap">
                            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                <h3>الإعدادات</h3>
                                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                    <li><a href="index.php"><div class="text-tiny">الرئيسية</div></a></li>
                                    <li><i class="icon-chevron-right"></i></li>
                                    <li><div class="text-tiny">الإعدادات</div></li>
                                </ul>
                            </div>
                            <div class="wg-box">
                                <form name="account_edit_form" action="#" method="POST" class="form-new-product form-style-1 needs-validation" enctype="multipart/form-data" novalidate>
                                    <?php if (isset($_SESSION['error'])): ?>
                                        <div class="alert alert-danger">
                                            <?php echo htmlspecialchars($_SESSION['error']); ?>
                                            <?php unset($_SESSION['error']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($_GET['success'])): ?>
                                        <div class="alert alert-success">تم تحديث المعلومات بنجاح.</div>
                                    <?php endif; ?>
                                    <fieldset class="name">
                                        <div class="body-title">اسم النظام <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="text" placeholder="اسم النظام" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">
                                    </fieldset>
                                    <fieldset class="name">
                                        <div class="body-title">رقم الهاتف <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="text" placeholder="رقم الهاتف" name="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </fieldset>
                                    <fieldset class="name">
                                        <div class="body-title">البريد الإلكتروني <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="email" placeholder="البريد الإلكتروني" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
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
                                        <input type="file" name="profile_image" accept="image/*">
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" width="100">
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
                                                <input class="flex-grow" type="password" placeholder="كلمة المرور القديمة" id="old_password" name="old_password" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="name">
                                                <div class="body-title pb-3">كلمة المرور الجديدة <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="كلمة المرور الجديدة" id="new_password" name="new_password">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="name">
                                                <div class="body-title pb-3">تأكيد كلمة المرور الجديدة <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="تأكيد كلمة المرور الجديدة" id="new_password_confirmation" name="new_password_confirmation">
                                            </fieldset>
                                        </div>
                                    </div>
                                    <button type="submit" class="tf-button w208">تحديث</button>
                                </form>
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
