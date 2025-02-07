<?php
include_once '../server/conn.php'; 
checkLogin(); 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">
<head>
    <title>الرئيسية</title>
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
                                <div class="tf-section-2 mb-30">
                                    <div class="flex gap20 flex-wrap-mobile">
                                        <div class="w-half">
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-user"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">إجمالي الطلاب</div>
                                                            <h4>150</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-user"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">إجمالي المرضى</div>
                                                            <h4>75</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-heartbeat"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">طلبات التبرع المعلقة</div>
                                                            <h4>20</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-notes"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">طلبات التبرع المكتملة</div>
                                                            <h4>130</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-half">
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-user"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">طلبات معلقة</div>
                                                            <h4>100</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-info"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">عدد أكياس الدم المتاحة</div>
                                                            <h4>300</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-heart-broken"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">طلبات التبرع الملغاة</div>
                                                            <h4>5</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wg-chart-default">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg"><i class="icon-file-text"></i></div>
                                                        <div>
                                                            <div class="body-text mb-2">عدد الأكياس المتبقية</div>
                                                            <h4>200</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="tf-section mb-30">
                                    <div class="wg-box">
                                        <div class="flex items-center justify-between">
                                            <h5>طلبات استمارات المتبرعين الأخيرة</h5>
                                            <div class="dropdown default">
                                                <a class="btn btn-secondary dropdown-toggle" href="pending-requests.php"><span class="view-all">عرض الكل</span></a>
                                            </div>
                                        </div>
                                        <div class="wg-table table-all-user">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 80px">رقم الطلب</th>
                                                            <th>اسم المتبرع</th>
                                                            <th class="text-center">رقم الهاتف</th>
                                                            <th class="text-center">فصيلة الدم</th>
                                                            <th class="text-center">الحالة</th>
                                                            <th class="text-center">تاريخ الطلب</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">محمد علي</td>
                                                            <td class="text-center">9876543210</td>
                                                            <td class="text-center">O+</td>
                                                            <td class="text-center">مكتمل</td>
                                                            <td class="text-center">2024-07-11 00:54:14</td>
                                                            <td class="text-center">
                                                                <a href="pending-requests.php">
                                                                    <div class="list-icon-function view-icon">
                                                                        <i class="icon-view"></i>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">2</td>
                                                            <td class="text-center">أحمد حسن</td>
                                                            <td class="text-center">1234567890</td>
                                                            <td class="text-center">B-</td>
                                                            <td class="text-center">معلق</td>
                                                            <td class="text-center">2024-07-10 14:30:14</td>
                                                            <td class="text-center">
                                                                <a href="pending-requests.php">
                                                                    <div class="list-icon-function view-icon">
                                                                        <i class="icon-view"></i>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">3</td>
                                                            <td class="text-center">سارة محمد</td>
                                                            <td class="text-center">3216549870</td>
                                                            <td class="text-center">AB+</td>
                                                            <td class="text-center">مكتمل</td>
                                                            <td class="text-center">2024-07-09 12:15:10</td>
                                                            <td class="text-center">
                                                                <a href="pending-requests.php">
                                                                    <div class="list-icon-function view-icon">
                                                                        <i class="icon-view"></i>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%; 
    table-layout: auto; 
}


::-webkit-scrollbar {
    width: 12px;
    background-color: #f1f1f1; 
}

::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 6px; 
}

::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}
</style>