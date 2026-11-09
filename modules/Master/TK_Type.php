<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">TK Type</h3>
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
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditType">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>
                <!--begin::Row-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <!-- ฟอร์มเพิ่ม/แก้ไข Type -->

                            <!-- ตาราง Type -->
                            <div class="table-responsive mt-4">
                                <table id="example" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th class="min-width70">Type Num</th>
                                            <th class="min-width70">Group</th>
                                            <th class="min-width70">Description TH</th>
                                            <th class="min-width40">Description EN</th>
                                            <th class="min-width40">Seq </th>
                                            <th class="min-width40">Approve Require</th>
                                            <th class="min-width40">Active</th>
                                            <th class="min-width40">Edit</th>
                                            <th class="min-width40">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $TKReson_List = controller_tk_type::GetTK_Type();
                                        if ($TKReson_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                            $i = 0;

                                            if ($TKReson_List != null && count($TKReson_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TKReson_List as $key => $resultReson_List) {
                                                    $Type_Num = $resultReson_List['Type_Num'];
                                                    $Type_Group = $resultReson_List['Type_Group'];
                                                    $Type_DescTh = $resultReson_List['Type_DescTh'];
                                                    $Type_DescEn = $resultReson_List['Type_DescEn'];
                                                    $Type_Seq = $resultReson_List['Type_Seq'];
                                                    $Approve_Req = $resultReson_List['Approve_Req'];
                                                    $Active = $resultReson_List['Is_Active'];
                                                    $i++;
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;"><?= $Type_Num; ?></td>
                                                        <td style="text-align:center;"><?= $Type_Group; ?></td>
                                                        <td><?= $Type_DescTh; ?></td>
                                                        <td><?= $Type_DescEn; ?></td>
                                                        <td  style="text-align:center;"><?= $Type_Seq; ?></td>
                                                        <td  style="text-align:center;">
                                                         <?php
                                                            if ($Approve_Req === 'Y') {
                                                                echo 'Approve';
                                                            } else {
                                                                echo 'Not Approve';
                                                            }
                                                            ?>
                                                        </td>
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
                                                            <a class="edittype" title="แก้ไข" onclick="EditType(<?= $i; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a class="deletetype ml-2" title="ลบ" onclick="DeletetType(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $Type_Num . '|' . $Type_Group . '|' . $Type_DescTh . '|' . $Type_DescEn . '|' . $Type_Seq . '|' . $Approve_Req . '|' . $Active; ?>">
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
<div class="modal fade" id="modal-InsEditType" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Ticket Type</b></font>
                <hr>
                <!-- <br> -->
                <form id="typeForm" method="post" action="?s=master&r=tk_type">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Type Num</label>
                                <input type="text" class="form-control" name="Type_num" id="Type_num" maxlength="4" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Group</label>
                                <!--  -->
                                <select class="form-control select2bs4" style="width: 100%;"
                                    tabindex="-1" aria-hidden="true" id="Group" name="Group" required>
                                    <option></option>
                                    <?php
                                    $TK_GroupList = controller_tk_type::GetTK_Group();
                                    if ($TK_GroupList === false) {
                                        echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                        echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                    } else {
                                        ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                        if ($TK_GroupList != null && count($TK_GroupList) > 0) {
                                            ## Loop ตามข่อมูลใน Array
                                            foreach ($TK_GroupList as $key => $resultTK_Group) {
                                    ?>
                                                <option value="<?= $resultTK_Group["TK_Group"]; ?>">
                                                    <?= $resultTK_Group["TK_Group"] . '-' . $resultTK_Group["Description"]; ?></option>
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
                                <label>Seq</label>
                                <input type="text" class="form-control" name="Seq" id="Seq" OnKeyPress="return OnlyInt(event)" maxlength="4" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description TH</label>
                                <input type="text" class="form-control" name="Type_DescTH" id="Type_DescTH" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description EN</label>
                                <input type="text" class="form-control" name="Type_DescEN" id="Type_DescEN" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-3 col-md-3 mid-top-bottom">
                                    <label style="margin-bottom: 0px;">Approve Req</label>
                                </div>
                                <div class="col-9 col-md-9  font-c-777 font-s-14">
                                    <div class="toggle-container ">
                                        <input type="checkbox" id="Approve_Req" name="Approve_Req" checked>
                                        <label for="Approve_Req" style="margin-bottom: 0rem;"></label>
                                    </div>
                                </div> <!-- / .row -->
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
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateType();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="typeedit" id="typeedit">
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#typeForm');"
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
<script src="views/js/master/tk_type.js"></script>
<?php
$ActionTkType = new controller_tk_type;
$ActionTkType->Action_TKType();
?>