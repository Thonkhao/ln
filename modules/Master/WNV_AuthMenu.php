<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">Auth Menu</h3>
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

                <!-- ปุ่มเพิ่ม Menu -->
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditAuthMenu">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <!-- ตาราง Menu -->
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
                                            <th class="min-width40" style="width:5%;text-align:center;">Edit</th>
                                            <th class="min-width40" style="width:5%;text-align:center;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $TKMenu_List = controller_auth_menu::Getauth_menu();
                                        if ($TKMenu_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            if ($TKMenu_List != null && count($TKMenu_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TKMenu_List as $key => $resultMenu_List) {
                                                    $MenuName = $resultMenu_List['MenuName'];
                                                    $MenuDisp = $resultMenu_List['MenuDisp'];
                                                    $MenuDesc = $resultMenu_List['MenuDesc'];
                                                    $MenuLink = $resultMenu_List['MenuLink'];
                                                    $seq = $resultMenu_List['seq'];
                                                    $IconID = $resultMenu_List['IconID'];
                                                    $IconCode = $resultMenu_List['IconCode'];
                                                    $Active = $resultMenu_List['Is_Active'];
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
                                                            <a class="editMenu" title="แก้ไข" onclick="EditMenu(<?= $i; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="deleteMenu ml-2" title="ลบ" onclick="DeleteMenu(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $MenuName . '|' . $MenuDisp . '|' . $MenuDesc . '|' . $MenuLink . '|' . $seq . '|' . $IconID . '|' . $Active  ; ?>">
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
<div class="modal fade" id="modal-InsEditAuthMenu" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Edit Auth Menu</b></font>
                <hr>
                <form id="MenuForm" method="post" action="?s=master&r=wnv_authmenu">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuName<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="MenuName" id="MenuName" maxlength="30" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuDisp<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="MenuDisp" id="MenuDisp" maxlength="100" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuDesc</label>
                                <input type="text" class="form-control" name="MenuDesc" id="MenuDesc" maxlength="255" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>MenuLink<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="MenuLink" id="MenuLink" maxlength="255" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>seq<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="MenuSeq" id="MenuSeq" maxlength="5" autocomplete="off" OnKeyPress="return OnlyInt(event)" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Icon</label>
                                <select class="form-control " style="width:100%;" id="MenuIcon" name="MenuIcon" required>
                                    <option></option>
                                    <?php
                                    $TKIcon_List = controller_auth_icon::Getauth_icon();
                                    if ($TKIcon_List && count($TKIcon_List) > 0) {
                                        foreach ($TKIcon_List as $row) {
                                            $IconID   = $row['IconID'];
                                            $IconDesc = $row['IconDesc'];
                                            $IconCode = $row['IconCode'];
                                    ?>
                                            <option value="<?= $IconID; ?>" data-icon='<?= htmlspecialchars($IconCode, ENT_QUOTES); ?>'>
                                                <?= $IconDesc; ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex flex-column justify-content-center">
                            <div class="row">
                                <div class="col-3 col-md-3 mid-top-bottom">
                                    <label style="margin-bottom: 0px;">Active</label>
                                </div>
                                <div class="col-9 col-md-9 font-c-777 font-s-14">
                                    <div class="toggle-container ">
                                        <input type="checkbox" id="Active" name="Active" checked>
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#MenuForm');"
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
<script src="views/js/master/wnv_authmenu.js"></script>
<?php
$ActionTkMenu = new controller_auth_menu;
$ActionTkMenu->Action_AuthMenu();
?>