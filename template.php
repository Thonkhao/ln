<!doctype html>
<?php
date_default_timezone_set("Asia/Bangkok");
# Use SESSION
session_start();

if (isset($_SESSION["uid"])) {
    $user = $_SESSION["user"];
    $uid = $_SESSION["uid"];
    $short_name = $_SESSION['short_name'] ?? 'GE';
    $Color_Code = $_SESSION['Color_Code'] ?? '#DADADA';
    if (isset($_SESSION["Img_profile"]) && !empty($_SESSION["Img_profile"])) {
        $Img_profile = $_SESSION['Img_profile'] ?? '';
        $profiledisplay = "<img class='circle-container image-preview-profile profile-border' 
            src='data:image/jpeg;base64,$Img_profile' 
            alt='My Image' 
             />";
    } else {
        $profiledisplay = "<div class=' circle-container image-preview-profile profile-border' 
            style='background-color: rgba($Color_Code,0.2); color:rgba($Color_Code,1); '>
            $short_name
        </div>";
    }
?>
    <html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Management</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="./pic/HTR.ico" type="image/x-icon" />
        <!--begin::Primary Meta Tags-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
            integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
            integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
        <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="views/component/css/adminlte.css" />
        <!--begin::Required Plugin(AdminLTEV3.2.0)-->
        <link rel="stylesheet" href="views/component/css/adminlteV3.2.0.css" />
        <!-- <link rel="stylesheet" href="views/plugins/fontawesome-free/css/all.min.css"> -->
        <!-- Select2 -->
        <link rel="stylesheet" href="views/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="views/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="views/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- toastr -->
        <link rel="stylesheet" href="views/plugins/toastr/toastr.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="views/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
        <!-- Font No To San Thai -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
        <!-- Summernote CSS -->
        <link rel="stylesheet" href="views/plugins/summernote/summernote-bs4.min.css">
        <link href="views/fontawesome/css/fontawesome.css" rel="stylesheet">
        <link href="views/fontawesome/css/brands.css" rel="stylesheet">
        <link href="views/fontawesome/css/solid.css" rel="stylesheet">
        <link href="views/fontawesome/css/regular.css" rel="stylesheet">
        <?php date_default_timezone_set('Asia/Bangkok'); ?>
        <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    </head>

    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">
            <!--begin::style-->
            <link rel="stylesheet" type="text/css" href="theme/style.css">
            <!--end::style-->

            <!--begin::Sidebar-->
            <?php include "theme/Navbar.php"; ?>
            <!--end::Sidebar-->
            <?php include "theme/script.php"; ?>

            <!--begin::Topbar-->
            <nav class="app-header navbar navbar-expand bg-body">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Start Navbar Links-->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                    </ul>
                    <!--end::Start Navbar Links-->
                    <!--begin::End Navbar Links-->
                    <ul class="navbar-nav ms-auto">
                        <!--begin::User Menu Dropdown-->



                        <li class="nav-item dropdown ">
                            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                                <span class="d-none d-md-inline"><?= $user; ?></span>
                                <span id="DisplayProfileImg" name="DisplayProfileImg"><?= $profiledisplay; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right " style="left: inherit; right: 0px;min-width: 200px;">

                                <div class="dropdown-divider"></div>
                                <button type="button" onclick="EditUserProfile('<?= $uid; ?>')"
                                    class="dropdown-item">
                                    <i class="fa-regular fa-user"></i>
                                    <span class="float-right text-muted text-sm">Profile</span>
                                </button>
                                <div class="dropdown-divider"></div>
                                <a href="index.php?r=Logout" class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span class="float-right text-muted text-sm"> Sign out</span>
                                </a>
                            </div>
                        </li>
                        <!--end::User Menu Dropdown-->
                    </ul>
                    <!--end::End Navbar Links-->
                </div>
                <!--end::Container-->
            </nav>
            <!--end::Topbar-->



            <!--begin::App Main-->
            <main class="app-main">
                <?php
                if (isset($_GET["r"])) { /* route = pos/index.php?route=any */
                    /* View Module - Home */
                    $_route = strtolower($_GET["r"]);
                    if (
                       $_route == strtolower("Home") ||
                        $_route == strtolower("logout") ||

						$_route == strtolower("DeliveryOnline") ||
								  
						#Report
						$_route == strtolower("product_delivery") ||
						$_route == strtolower("actdelidaily") ||
									
						#Tracking
						$_route == strtolower("ordertracking") ||
						$_route == strtolower("ordertrackingOVS") ||
						$_route == strtolower("ordertrackingMOT") ||
						$_route == strtolower("ordertrackingTRT") ||
						
						$_route == strtolower("TruckageReport") ||
						$_route == strtolower("Onhand2Comp") ||
						
						#Marketing
						$_route == strtolower("pccustomer") ||
						
                        #Master
                        $_route == strtolower("WNV_AuthIcon") ||
                        $_route == strtolower("WNV_AuthMenu") ||
                        $_route == strtolower("WNV_AuthGroup") ||
                        $_route == strtolower("wnv_authuseremailgroup") ||
                        $_route == strtolower("WNV_AuthGroupMenu") ||
                        $_route == strtolower("WNV_Holiday") ||
                        $_route == strtolower("TK_ManageUserEmail") 
                    ) {
                        if (isset($_GET["s"])) {
                            $s = $_GET['s'];
                            include "modules/$s/$_route.php";
                        } else {
                            include "modules/$_route.php";
                        }
                    } else { /* route = 404 */
                        /* View - 404 */
                        include "modules/Home.php";
                    }
                } else { /* route = pos/index.php */
                    //     /* View - Home */
                    include "modules/Home.php";
                }
                ?>
            </main>
            <!--end::App Main-->
        </div>
        <!--end::App Wrapper-->
    </body>

    <!-- Start :: Model -->
    <div class="modal fade" id="modal-UserUpProfile" aria-modal="true">
        <div class="modal-dialog" style="max-width:550px;margin-top: 5%;">
            <div class="modal-content">
                <div class="modal-body">
                    <font style="font-size:18px;"><b>User update profile</b></font>
                    <hr>
                    <!-- <br> -->
                    <form id="UserUpProfile" method="post" action="?s=master&r=TK_ManageUserEmail" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="image-container">
                                    <span id="self_ShowUserImg" style="display:none;"></span>
                                    <span id="self_ShowShortName" style="display:none;"></span>
                                    <img id="self_preview" class="image-preview hidden">
                                    <div class="file-input-wrapper">
                                        <span class="btn btn btn-outline-dark">Browse</span>
                                        <input class="btn btn btn-outline-dark" type="file" name="self_profile_image" id="self_profile_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                        <input type="hidden" name="self_changeImage" id="self_changeImage" value="N">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>User Email</label>
                                    <input type="text" class="form-control" name="self_TK_UserEmail" id="self_TK_UserEmail" maxlength="100" required readonly>
                                    <input type="hidden" class="form-control" name="self_TK_UserID" id="self_TK_UserID" maxlength="10" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name </label>
                                    <input type="text" class="form-control" name="self_UserEmail_Desc" id="self_UserEmail_Desc" maxlength="255" required readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mid-left-right d-flex">
                                    <button type="button" style="width:50%" class="btn btn btn-outline-dark me-2" id="self_saveBtn" onclick="UserUploadProfile('999');">Save</button>
                                    <button type="button" style="width:50%" class="btn btn btn-outline-dark" onclick="Removeprofile();">Remove photo</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Start :: Model -->
    <div class="modal fade" id="alert-model" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="text-align:center">
                    <!-- <span id="check-circle" style="display:none;"> -->
                    <center>
                        <i id="check-circle" class="fas fa-check-circle" style="font-size:80px;color:#0060de;display:none;"></i>
                        <i id="times-circle" class="fas fa-times-circle" style="font-size:80px;color:#dc3545;display:none;"></i>
                        <h5 style="margin-top:20px;margin-bottom:15px;font-size:25px;"><b id="textshow"></b></h5>
                        <font style="font-size:15px;"></font>
                    </center>

                    <div class="row">
                        <div class="container-fluid col-md-8">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <button id="cancelledbtn" style="border-radius: 0.3rem;width:100%;"
                                        type="button" class="btn btn btn-outline-dark">OK</button>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- END :: Model -->
    <!--end::Body-->
    <!--begin::Script-->
    <script src="views/js/template.js?v=<?php echo date("YmdHis");?>"></script>
    <!--end::Script-->

    </html>
<?php
    if (isset($_SESSION['msg'])) {
        $m = $_SESSION['msg'];
        $s = $_SESSION['stat'];
        $title = $_SESSION['title'];
        echo "<script>toastralert('$title','$m','$s')</script>";
        unset($_SESSION['msg']);
        unset($_SESSION['stat']);
        unset($_SESSION['title']);
    }
} else {
    // /* View - Home */
    include "modules/Loginpage.php";
}
?>