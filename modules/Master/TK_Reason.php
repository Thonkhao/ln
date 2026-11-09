<!--begin::App Content Header-->
<style>

</style>
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">TK Reason</h3>
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

                <!-- ปุ่มเพิ่ม Reason -->
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditReason">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <!-- ตาราง Reason -->
                            <div class="table-responsive mt-4">
                                <table id="example" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th class="min-width70" style="width:18%;">Reason Type</th>
                                            <th class="min-width70" style="width:12%;">Reason Code</th>
                                            <th class="min-width70">Reason Description</th>
                                            <th class="min-width40" style="width:10%;">Active</th>
                                            <th class="min-width40" style="width:6%;">Edit</th>
                                            <th class="min-width40" style="width:6%;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $TKReson_List = controller_tk_reason::GetTK_Reason();
                                        if ($TKReson_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                            $i = 0;
                                            if ($TKReson_List != null && count($TKReson_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TKReson_List as $key => $resultReson_List) {
                                                    $ReasonType = $resultReson_List['ReasonType'];
                                                    $ReasonCode = $resultReson_List['ReasonCode'];
                                                    $ReasonDesc = $resultReson_List['ReasonDesc'];
                                                    $Active = $resultReson_List['Is_Active'];
                                                    $i++;
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;padding-right: 0;"><?= $ReasonType; ?></td>
                                                        <td style="text-align:center;padding-right: 0;"><?= $ReasonCode; ?></td>
                                                        <td><?= $ReasonDesc; ?></td>
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
                                                            <a class="editReason" title="แก้ไข" onclick="EditReason(<?= $i; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="deleteReason ml-2" title="ลบ" onclick="DeleteReason(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $ReasonType . '|' . $ReasonCode . '|' . $ReasonDesc . '|' . $Active; ?>">
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
<div class="modal fade" id="modal-InsEditReason" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Edit TK Reason</b></font>
                <hr>
                <!-- <br> -->
                <form id="reasonForm" method="post" action="?s=master&r=tk_reason">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason Type</label>
                                <select class="form-control select2bs4 " style="width: 100%;"
                                    aria-hidden="true" id="ReasonType" name="ReasonType" maxlength="29" required>
                                    <option></option>
                                    <?php


                                    if ($TKReson_List === false) {
                                        echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                        echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                    } else {
                                        ##ตรวจสอบว่ามีข้อมูลกลับมาหรือไม่ SRO
                                        $i = 0;
                                        if ($TKReson_List != null && count($TKReson_List) > 0) {
                                            $shownTypes = [];
                                            ## Loop ตามข่อมูลใน Array
                                            foreach ($TKReson_List as $key => $resultReson_List) {
                                                if (!in_array($resultReson_List["ReasonType"], $shownTypes)) {
                                                    $shownTypes[] = $resultReson_List["ReasonType"];
                                    ?>
                                                    <option value="<?= $resultReson_List["ReasonType"]; ?>"> <?= $resultReson_List["ReasonType"]; ?></option>
                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason Code</label>
                                <input type="text" class="form-control" name="ReasonCode" id="ReasonCode" maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason Description</label>
                                <input type="text" class="form-control" name="ReasonDesc" id="ReasonDesc" maxlength="120" required>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex flex-column justify-content-center">
                            <div class="form-group" style="gap: 10px;display: flex; align-items: center;">
                                <label style="margin-bottom: 0px;">Active</label>
                                <div class="toggle-container ">
                                    <input type="checkbox" id="Active" name="Active" checked>
                                    <label for="Active" style="margin-bottom: 0rem;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row" style="gap: 10%;">
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateReason();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mt-3 text-right"> -->
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="reasonedit" id="reasonedit">
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#reasonForm');"
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
<script src="views/js/master/tk_reason.js"></script>
<?php
$ActionTkReason = new controller_tk_reason;
$ActionTkReason->Action_TKReason();
?>