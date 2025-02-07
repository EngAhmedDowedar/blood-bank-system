<?php
include_once '../server/conn.php'; 
checkLogin();
$db = new Database();
$conn = $db->connect();

try {
    $userCollegeId = $_SESSION['college_id']; 

    $sql = "SELECT students.*, colleges.name 
            FROM students 
            JOIN colleges ON students.college_id = colleges.id 
            WHERE students.college_id = :college_id AND students.status = 1"; 

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':college_id', $userCollegeId, PDO::PARAM_INT); 
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "حدث خطأ أثناء جلب بيانات الطلاب: " . $e->getMessage();
} finally {
    $conn = null;
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
                                    <h3>الطلاب</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li><a href="index.php"><div class="text-tiny">اللوحة الرئيسية</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><div class="text-tiny">الطلاب</div></li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="ابحث هنا..." class="" name="name" tabindex="2" value="" aria-required="true" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <a class="tf-button style-1 w208" href="add-student.php"><i class="icon-plus"></i>إضافة طالب جديد</a>
                                    </div>
                                    <div class="wg-table table-all-user" style="overflow: hidden;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 80px">#</th>
                                                        <th class="text-center" style="width: 300px">اسم الطالب</th>
                                                        <th class="text-center" style="width: 150px">الرقم القومي</th>
                                                        <th class="text-center" style="width: 150px">الكلية</th>
                                                        <th class="text-center" style="width: 150px">الفرقة</th>
                                                        <th class="text-center" style="width: 150px">الفصيلة</th>
                                                        <th class="text-center" style="width: 150px">رقم الهاتف</th>
                                                        <th class="text-center" style="width: 280px">الإيميل</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($students)): ?>
                                                        <?php $counter = 1; ?>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr>
                                                                <td class="text-center"><?= $counter++; ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['student_name']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['national_id']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['name']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['year']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['blood_type']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['phone']); ?></td>
                                                                <td class="text-center"><?= htmlspecialchars($student['email']); ?></td>
                                                                <td class="text-center">
                                                                    <div class="list-icon-function">
                                                                        <a href="edit_student.php?id=<?= htmlspecialchars($student['id']); ?>">
                                                                            <div class="item edit"><i class="icon-edit-3"></i></div>
                                                                        </a>
                                                                        <form action="delete_student.php" method="POST" style="display:inline;">
                                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']); ?>">
                                                                            <button type="submit" class="item text-danger delete" style="border:none; background:none;"><i class="icon-trash-2"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center">لا توجد بيانات للعرض</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                    </div>
                                </div>
                                <style>
                                    .wg-table::-webkit-scrollbar { width: 8px; }
                                    .wg-table::-webkit-scrollbar-track { background: #f0f0f0; }
                                    .wg-table::-webkit-scrollbar-thumb { background-color: #808080; border-radius: 10px; }
                                    .wg-table::-webkit-scrollbar-thumb:hover { background-color: #555; }
                                </style>
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
