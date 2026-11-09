<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">TK User Email</h3>
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
            <!-- <div class="col-lg-2"></div> -->

            <div class="col-md-12 col-lg-12">
                <!-- ปุ่มเพิ่ม Type -->
                <a href="views/modules/getallemail.php" style="background-color:white;" class="fixed-circle-button" target="_blank">
                    <i class="fa-solid fa-arrows-rotate" style="font-size: 26px;color: #828282;"></i>
                </a>
                <!--begin::Row-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">


                            <!-- ตาราง Type -->
                            <div class="table-responsive mt-4">
                                <table id="example" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th class="min-width40" style="width:5%!important;">User ID</th>
                                            <th class="min-width70" style="width:30%!important;">Email</th>
                                            <th class="min-width40" style="width:15%!important;">Name</th>
                                            <th class="min-width70" style="width:10%!important;">Dept</th>
                                            <th class="min-width70" style="width:20%!important;">Organization</th>
                                            <th class="min-width40" style="width:10%!important;">Active</th>
                                            <th class="min-width40" style="width:5%!important;">Edit</th>
                                            <th class="min-width40" style="width:5%!important;">Edit Auth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $TK_UserEmailList = controller_tk_UserEmail::GetTK_UserEmail();
                                        if ($TK_UserEmailList === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                            if ($TK_UserEmailList != null && count($TK_UserEmailList) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TK_UserEmailList as $key => $resultUserEmail_List) {
                                                    $UserID = $resultUserEmail_List['UserID'];
                                                    $User_Email = $resultUserEmail_List['User_Email'];
                                                    $F_Name = $resultUserEmail_List['F_Name'];
                                                    $Active = $resultUserEmail_List['Is_Active'];
                                                    $Dept_Code = $resultUserEmail_List['Dept_Code'];
                                                    $Dept_Desc = $resultUserEmail_List['Dept_Desc'];
                                                    $Org_Seq = $resultUserEmail_List['Org_Seq'];
                                                    $Org_DescEh = $resultUserEmail_List['Org_DescEh'];
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;"><?= $UserID; ?></td>
                                                        <td><?= $User_Email; ?></td>
                                                        <td style="text-align:center;"><?= $F_Name; ?></td>
                                                        <td style="text-align:center;"><?= $Dept_Desc; ?></td>
                                                        <td style="text-align:center;"><?= $Org_DescEh; ?></td>
                                                        <td style="text-align:center;">
                                                            <?php
                                                            if ($Active === 'Y') {
                                                                echo 'Active';
                                                            } else {
                                                                echo 'InActive';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a class="editUserEmail" title="แก้ไข" onclick="EditUserEmail(<?= $UserID; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a class="editUserEmail" href="?s=master&r=wnv_authuseremailgroup&u=<?= $UserID; ?>&e=<?= $User_Email; ?>"><i class="fa-solid fa-user"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $UserID; ?>" id="editshow-<?= $UserID; ?>"
                                                        value="<?= $UserID . '|' . $User_Email . '|' . $F_Name . '|' . $Active; ?>">
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
            <!-- <div class="col-lg-2"></div> -->
            <!-- /.col-md-6 -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>


<!-- ==================Start ConfirmMail=========================================== -->
<!-- Start :: Model -->
<div class="modal fade" id="modal-InsEditUserEmail" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 2%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Ticket User Email</b></font>
                <hr>
                <!-- <br> -->
                <form id="UserEmailForm" method="post" action="?s=master&r=TK_ManageUserEmail" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="image-container">
                                <span id="ShowUserImg" style="display:none;"></span>
                                <span id="ShowShortName" style="display:none;"></span>
                                <img id="preview" class="image-preview hidden">
                                <div class="file-input-wrapper" style="display:none;">
                                    <span class="btn btn btn-outline-dark">Browse</span>
                                    <input class="btn btn btn-outline-dark" type="file" name="profile_image" id="profile_image" accept="image/*">
                                    <input type="hidden" name="changeImage" id="changeImage" value="N">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>User Email</label>
                                <input type="text" class="form-control" name="TK_UserEmail" id="TK_UserEmail" maxlength="100" required readonly>
                                <input type="hidden" class="form-control" name="TK_UserID" id="TK_UserID" maxlength="10" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name </label>
                                <input type="text" class="form-control" name="UserEmail_Desc" id="UserEmail_Desc" maxlength="255" required readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Shot Name </label>
                                <input type="text" class="form-control" name="User_Shortname" id="User_Shortname" maxlength="2" required readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Department</label>
                                <select class="form-control select2bs4 " style="width: 100%;"
                                    aria-hidden="true" id="UserDept" name="UserDept" maxlength="29" onchange="DeptChange(this.value,'999999')">
                                    <option></option>
                                    <?php

                                    $DeptList = controller_tk_UserEmail::GetDepartment();
                                    if ($DeptList === false) {
                                        echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                        echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                    } else {
                                        ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                        if ($DeptList != null && count($DeptList) > 0) {
                                            $shownDept = [];
                                            ## Loop ตามข่อมูลใน Array
                                            foreach ($DeptList as $key => $resultDept_List) {
                                    ?>
                                                <option value="<?= $resultDept_List["Dept_Code"]; ?>"> <?= $resultDept_List["Dept_Desc"]; ?></option>
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
                                <label>Organization</label>
                                <select class="form-control select2bs4 " style="width: 100%;"
                                    aria-hidden="true" id="TKUSer_Org" name="TKUSer_Org" maxlength="29">
                                    <option></option>
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
                            <div class="row mid-left-right">
                                <button type="button" style="width:50%" class="btn btn btn-outline-dark" id="saveBtn" onclick="Confirmaction();">Save</button>
                                <!-- <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn">Clear</button> -->
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="E">
                    <!-- <input type="hidden" name="UserEmailedit" id="UserEmailedit"> -->
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#UserEmailForm');"
                                        class="btn btn btn-outline-dark" style="width:100%;">Confirm</button>
                                </div>
                                <div class="col-6" style="padding-top:5px;">
                                    <button id="cancelledbtn" style="border-radius: 0.3rem;width:100%;"
                                        type="button" class="btn btn btn-outline-dark"
                                        onclick="hideModel();">Cancel</button>
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
<script src="views/js/master/tk_manageuseremail.js"></script>
<?php
$ActionTkUserEmail = new controller_tk_UserEmail;
$ActionTkUserEmail->Action_TKUserEmail();
?>