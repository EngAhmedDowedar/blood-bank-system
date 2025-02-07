<?php
include_once '../server/conn.php'; 
checkLogin(); 
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
                                    <h3>الكليات</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li><a href="index.php"><div class="text-tiny">اللوحة الرئيسية</div></a></li>
                                        <li><i class="icon-chevron-right"></i></li>
                                        <li><div class="text-tiny">الكليات</div></li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="ابحث هنا..." name="name" tabindex="2" value="" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <a class="tf-button style-1 w208" href="add-college.php"><i class="icon-plus"></i>إضافة جديدة</a>
                                    </div>
                                    <div class="wg-table table-all-user" style="overflow-y: auto; max-height: 400px;">
                                    <?php
                                        $db = new Database();
                                        $conn = $db->connect();
                                        $sql = "SELECT * FROM colleges";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if (count($result) > 0) {
                                            echo '<table class="table table-striped table-bordered">';
                                            echo '<thead>';
                                            echo '<tr>';
                                            echo '<th class="text-center">#</th>';
                                            echo '<th class="text-center" style="width: 200px">اسم الكلية</th>';
                                            echo '<th class="text-center" style="width: 200px">عميد الكلية</th>';
                                            echo '<th class="text-center" style="width: 260px">البريد الإلكتروني</th>';
                                            echo '<th class="text-center" style="width: 200px">اسم المستخدم</th>';
                                            echo '<th class="text-center">عدد الطلاب المسجلين</th>';
                                            echo '<th class="text-center">عدد الأقسام</th>';
                                            echo '<th class="text-center">الإجراء</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';
                                            foreach ($result as $row) {
                                                echo '<tr>';
                                                echo '<td class="text-center">' . $row['id'] . '</td>';
                                                echo '<td class="text-center">' . $row['name'] . '</td>';
                                                echo '<td class="text-center">أ.د/ ' . $row['dean_name'] . '</td>';
                                                echo '<td class="text-center">' . $row['email'] . '</td>';
                                                echo '<td class="text-center">' . $row['username'] . '</td>';
                                                echo '<td class="text-center">' . 20 . '</td>';
                                                echo '<td class="text-center">' . 4 . '</td>';
                                                echo '<td>';
                                                echo '<div class="list-icon-function">';
                                                echo '<a href="edit-college.php?id=' . $row['id'] . '">';
                                                echo '<div class="item edit"><i class="icon-edit-3"></i></div>';
                                                echo '</a>';
                                                echo '<form action="delete-college.php" method="POST" style="display:inline;">';
                                                echo '<input type="hidden" name="college_id" value="' . $row['id'] . '">';
                                                echo '<button type="submit" class="item text-danger delete" onclick="return confirm(\'هل تريد حذف هذه الكلية؟\');">';
                                                echo '<i class="icon-trash-2"></i>';
                                                echo '</button>';
                                                echo '</form>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                            echo '</tbody>';
                                            echo '</table>';
                                        } else {
                                            echo '<p>لا توجد بيانات للعرض.</p>';
                                        }
                                        $conn = null; 
                                    ?>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                    </div>
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
<style>
.wg-table::-webkit-scrollbar {
    width: 8px; 
}
.wg-table::-webkit-scrollbar-track {
    background: #f0f0f0;
}
.wg-table::-webkit-scrollbar-thumb {
    background-color: #808080; 
    border-radius: 10px; 
}
.wg-table::-webkit-scrollbar-thumb:hover {
    background-color: #555; 
}
</style>
