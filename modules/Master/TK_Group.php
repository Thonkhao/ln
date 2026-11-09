<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">TK Group</h3>
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
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditGroup">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>
                <!--begin::Row-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <!-- ฟอร์มเพิ่ม/แก้ไข Type -->

                            <!-- ตาราง Type -->
                            <div class="table-responsive mt-4">
                                <table id="example12" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th class="min-width70" style="width:13%!important;">TK Group</th>
                                            <th class="min-width70">Description</th>
                                            <th class="min-width70" style="width:10%!important;">Prefix</th>
                                            <th class="min-width70" style="width:10%!important;">Active</th>
                                            <th class="min-width70" style="width:10%!important;">Edit</th>
                                            <th class="min-width70" style="width:10%!important;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $TK_GroupList = controller_tk_group::GetTK_Group();
                                        if ($TK_GroupList === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                            $i = 0;

                                            if ($TK_GroupList != null && count($TK_GroupList) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TK_GroupList as $key => $resultGroup_List) {
                                                    $TK_Group = $resultGroup_List['TK_Group'];
                                                    $Description = $resultGroup_List['Description'];
                                                    $Prefix_Ticket = $resultGroup_List['Prefix_Ticket'];
                                                    $Active = $resultGroup_List['Is_Active'];
                                                    $i++;
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;"><?= $TK_Group; ?></td>
                                                        <td><?= $Description; ?></td>
                                                        <td style="text-align:center;"><?= $Prefix_Ticket; ?></td>
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
                                                            <a class="editgroup" title="แก้ไข" onclick="EditGroup(<?= $i; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a class="delegroup ml-2" title="ลบ" onclick="DeletetGroup(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $TK_Group . '|' . $Description . '|' . $Prefix_Ticket . '|' . $Active; ?>">
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
<div class="modal fade" id="modal-InsEditGroup" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Ticket Group</b></font>
                <hr>
                <!-- <br> -->
                <form id="groupForm" method="post" action="?s=master&r=tk_group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Group</label>
                                <!--  -->
                                <input type="text" class="form-control" name="TK_Group" id="TK_Group" maxlength="5" required>
                            
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description </label>
                                <input type="text" class="form-control" name="Group_Desc" id="Group_Desc" maxlength="255" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Prefix</label>
                                <input type="text" class="form-control" name="Prefix" id="Prefix" maxlength="2" required>
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
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateGroup();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="groupedit" id="groupedit">
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#groupForm');"
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
<script src="views/js/master/tk_group.js"></script>
<?php
$ActionTkgroup = new controller_tk_group;
$ActionTkgroup->Action_TKGroup();
?>