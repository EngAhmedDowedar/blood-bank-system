<?php
include_once 'conn.php'; 

checkLogin(); 

$database = new Database();
$pdo = $database->connect();

$adminId = $_SESSION['college_id']; 
$query = $pdo->prepare("SELECT name, phone, email, profile_image, role FROM colleges WHERE id = :id");
$query->execute(['id' => $adminId]);
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<div class="header-dashboard">
    <div class="wrap">
        <div class="header-left">
            <a href="index.php">
                <img class="" id="logo_header_mobile" alt="" src="../assets/images/logo/logo.png"
                    data-light="../assets/images/logo/logo.png" data-dark="../assets/images/logo/logo.png"
                    data-width="154px" data-height="52px" data-retina="../assets/images/logo/logo.png">
            </a>
            <div class="button-show-hide">
                <i class="icon-menu-left"></i>
            </div>
            <form class="form-search flex-grow">
                <fieldset class="name">
                    <input type="text" placeholder="ابحث عن المتبرعين..." class="show-search" name="name"
                        tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
                <div class="box-content-search" id="box-content-search">
                    <ul class="mb-24">
                        <li class="mb-14">
                            <div class="body-title">أفضل المتبرعين بالدم</div>
                        </li>
                        <li class="mb-14">
                            <div class="divider"></div>
                        </li>
                        <li>
                            <ul>
                                <li class="donor-item gap14 mb-10">
                                    <div class="image no-bg">
                                        <img src="images/donors/1.png" alt="">
                                    </div>
                                    <div class="flex items-center justify-between gap20 flex-grow">
                                        <div class="name">
                                            <a href="students.php" class="body-text">Ahmed Ali - O+</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-10">
                                    <div class="divider"></div>
                                </li>
                                <li class="donor-item gap14 mb-10">
                                    <div class="image no-bg">
                                        <img src="images/donors/2.png" alt="">
                                    </div>
                                    <div class="flex items-center justify-between gap20 flex-grow">
                                        <div class="name">
                                            <a href="students.php" class="body-text">Sara Youssef - AB-</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="header-grid">
            <div class="popup-wrap message type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-item">
                            <span class="text-tiny">3</span>
                            <i class="icon-bell"></i>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton2">
                        <li>
                            <h6>الإشعارات</h6>
                        </li>
                        <li>
                            <div class="message-item item-1">
                                <div class="image">
                                    <i class="icon-noti-1"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">طلب دم: O+</div>
                                    <div class="text-tiny">حاجة ماسة في القاهرة</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="message-item item-2">
                                <div class="image">
                                    <i class="icon-noti-2"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">تم تسجيل متبرع جديد</div>
                                    <div class="text-tiny">Ahmed Ali - O+</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="message-item item-3">
                                <div class="image">
                                    <i class="icon-noti-3"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">نجاح التبرع</div>
                                    <div class="text-tiny">شكراً لمساهمتك!</div>
                                </div>
                            </div>
                        </li>
                        <li><a href="#" class="tf-button w-full">عرض الكل</a></li>
                    </ul>
                </div>
            </div>
            <div class="popup-wrap user type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-user wg-user">
                            <span class="image">
                                <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="صورة المستخدم">
                            </span>
                            <span class="flex flex-column">
                                <span class="body-title mb-2"><?php echo htmlspecialchars($user['name']); ?></span>
                                <span class="text-tiny"><?php echo htmlspecialchars($user['role'] == 'admin' ? 'مدير' : 'مساعد المدير'); ?></span>
                            </span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton3">
                        <li>
                            <a href="settings.php" class="user-item">
                                <div class="icon">
                                    <i class="icon-user"></i>
                                </div>
                                <div class="body-title-2">الحساب</div>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="user-item">
                                <div class="icon">
                                    <i class="icon-log-out"></i>
                                </div>
                                <div class="body-title-2">تسجيل الخروج</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
