<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <?php
                $GroupName=$_GET['g'] ?? '';
                ?>
                <h3 class="mb-0" style="margin-bottom: 0;"><a href="?s=master&r=wnv_authgroup">Auth GroupMenu: <?= $GroupName; ?></a></h3>
            </div>
            <!-- </div> -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <!-- <div class="col-lg-1"></div> -->

            <div class="col-md-12 col-lg-12">
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditAuthGroupMenu">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <div class="table-responsive mt-4">
                                <table id="example" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <!-- <th class="min-width40"></th> -->
                                            <th class="min-width40" style="width:15%;">Name</th>
                                            <th class="min-width70" style="width:15%;">Display</th>
                                            <th class="min-width70" style="width:21%;">Description</th>
                                            <th class="min-width70" style="width:21%;">Link</th>
                                            <th class="min-width40" style="width:5%;">seq</th>
                                            <th class="min-width40" style="width:5%;">Icon</th>
                                            <th class="min-width40" style="width:8%;text-align:center;">Active</th>
                                            <th class="min-width40" style="width:5%;text-align:center;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $GroupMenu_List = controller_auth_groupmenu::Getauth_menu();
                                        if ($GroupMenu_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            if ($GroupMenu_List != null && count($GroupMenu_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($GroupMenu_List as $key => $resultGroupMenu_List) {
                                                    $MenuName = $resultGroupMenu_List['MenuName'];
                                                    $MenuDisp = $resultGroupMenu_List['MenuDisp'];
                                                    $MenuDesc = $resultGroupMenu_List['MenuDesc'];
                                                    $MenuLink = $resultGroupMenu_List['MenuLink'];
                                                    $seq = $resultGroupMenu_List['seq'];
                                                    $IconCode = $resultGroupMenu_List['IconCode'];
                                                    $Active = $resultGroupMenu_List['Is_Active'];
                                                    // gm.[GroupName],m.[MenuName],m.[MenuDisp],m.[MenuDesc],m.[MenuLink],m.[seq],m.[Is_Active],m.[IconID] ,i.IconCode
                                                    $i++;
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;padding-right: 0;"><?= $MenuName; ?></td>
                                                        <td style="text-align:center;padding-right: 0;"><?= $MenuDisp; ?></td>
                                                        <td style="padding-right: 0;"><?= $MenuDesc; ?></td>
                                                        <td style="padding-right: 0;"><?= $MenuLink; ?></td>
                                                        <td style="text-align:center;padding-right: 0;"><?= $seq; ?></td>
                                                        <td style="text-align:center;padding-right: 0"><?= $IconCode; ?></td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <?php
                                                            if ($Active === 'Y') {
                                                                echo 'Active';
                                                            } else {
                                                                echo 'InActive';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="deleteMenu ml-2" title="ลบ" onclick="DeleteMenu(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $GroupName .'|'. $MenuName;?>">
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- <div class="col-lg-1"></div> -->
            <!-- /.col-md-6 -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>


<!-- ==================Start ConfirmMail=========================================== -->
<!-- Start :: Model -->
<div class="modal fade" id="modal-InsEditAuthGroupMenu" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Insert Auth Menu</b></font>
                <hr>
                <form id="GroupMenuForm" method="post" action="?s=master&r=wnv_authgroupmenu&g=<?= $GroupName?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuName</label> 
                                <input type="hidden" class="form-control" id="GroupName" name="GroupName" value="<?= $GroupName;?>">
                                <select class="form-control select2bs4 " style="width: 100%;"
                                    aria-hidden="true" id="MenuName" name="MenuName" oninput="getMenuDetail(this.value);">
                                    <option></option>
                                    <?php
                                     $MenuActive_List = controller_auth_groupmenu::Getauth_menuActive();
                                    if ($MenuActive_List === false) {
                                        echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                        echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                    } else {
                                        ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                        if ($MenuActive_List != null && count($MenuActive_List) > 0) {
                                            ## Loop ตามข่อมูลใน Array  
                                            foreach ($MenuActive_List as $key => $resultMenuActive) {
                                    ?>
                                                  <option value="<?= $resultMenuActive["MenuName"]; ?>">
                                                    <?= $resultMenuActive["MenuName"]; ?></option>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuDisp</label>
                                <input type="text" class="form-control" id="MenuDisp" name="MenuDisp" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuDesc</label>
                                <input type="text" class="form-control" id="MenuDesc" name="MenuDesc" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuLink</label>
                                <input type="text" class="form-control" id="MenuLink" name="MenuLink" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Seq</label>
                                <input type="text" class="form-control" id="MenuSeq" name="MenuSeq" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Icon</label><span id='IconShow'></span>
                                <input type="text" class="form-control" id="MenuIcon" name="MenuIcon" disabled>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex flex-column justify-content-center">
                            <div class="row">
                                <div class="col-3 col-md-3 mid-top-bottom">
                                    <label style="margin-bottom: 0px;">Active</label>
                                </div>
                                <div class="col-9 col-md-9 font-c-777 font-s-14">
                                    <div class="toggle-container ">
                                        <input type="checkbox" id="Active" name="Active" checked onclick="return false">
                                        <label for="Active" style="margin-bottom: 0rem;"></label>
                                    </div>
                                </div> <!-- / .row -->
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row" style="gap: 10%;">
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateMenu();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mt-3 text-right"> -->
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="Menuedit" id="Menuedit">
                    <!-- </div> -->
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END :: Model -->
<!-- Start :: Model -->
<div class="modal fade" id="Confirmaction" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <font style="font-size:18px;"><b> <span id="textshow"></span> </b></font>
                <br>

                <div class="row">
                    <div class="container-fluid col-md-8">
                        <div class="row" style="padding-top:5px;">
                            <div class="row">
                                <div class="col-6" style="padding-top:5px;">
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#GroupMenuForm');"
                                        class="btn btn btn-outline-dark" style="width:100%;">Confirm</button>
                                </div>
                                <div class="col-6" style="padding-top:5px;">
                                    <button id="cancelledbtn" style="border-radius: 0.3rem;width:100%;"
                                        type="button" class="btn btn btn-outline-dark"
                                        onclick="hideModel('Confirmaction');">Cancel</button>
                                </div>
                            </div>
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

<!--end::App Content-->
<script src="views/js/master/wnv_authgroupmenu.js"></script>
<?php
$ActionGroupMenu = new controller_auth_groupmenu;
$ActionGroupMenu->Action_AuthGroupMenu();
?>