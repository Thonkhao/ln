<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">Auth Icon</h3>
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

                <!-- ปุ่มเพิ่ม Icon -->
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditAuthIcon">
                    <i class="fa-solid fa-circle-plus"></i>
                </button>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex row">
                            <!-- ตาราง Icon -->
                            <div class="table-responsive mt-4">
                                <table id="example" class="table table-head-fixed" style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th class="min-width40">ID</th>
                                            <th class="min-width70">Description</th>
                                            <th class="min-width70">Code</th>
                                            <th class="min-width40" style="text-align:center;">Icon</th>
                                            <th class="min-width40" style="text-align:center;">Edit</th>
                                            <th class="min-width40" style="text-align:center;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=0;
                                        $TKIcon_List = controller_auth_icon::Getauth_icon();
                                        if ($TKIcon_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            if ($TKIcon_List != null && count($TKIcon_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($TKIcon_List as $key => $resultIcon_List) {
                                                    $IconID = $resultIcon_List['IconID'];
                                                    $IconDesc = $resultIcon_List['IconDesc'];
                                                    $IconCode = $resultIcon_List['IconCode'];
                                                    $i++;
                                        ?>
                                                    <tr>
                                                        <td style="text-align:center;padding-right: 0;"><?= $i; ?></td>
                                                        <td style="padding-right: 0;"><?= $IconDesc; ?></td>
                                                        <td><?= htmlspecialchars($IconCode); ?></td>
                                                        <td style="text-align:center;padding-right: 0"><?= $IconCode; ?></td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="editIcon" title="แก้ไข" onclick="EditIcon(<?= $IconID; ?>);"><i class="fa fa-edit text-primary"></i></a>
                                                        </td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="deleteIcon ml-2" title="ลบ" onclick="DeleteIcon(<?= $IconID; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $IconID; ?>" id="editshow-<?= $IconID; ?>"
                                                      value="<?= $IconID . '|' . $IconDesc . '|' . htmlspecialchars($IconCode); ?>">
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
<div class="modal fade" id="modal-InsEditAuthIcon" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Edit Auth Icon</b></font>
                <hr>
                <!-- <br> -->
                <form id="IconForm" method="post" action="?s=master&r=wnv_authicon">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Icon Desc</label>
                                <input type="text" class="form-control" name="IconDesc" id="IconDesc" maxlength="100" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Icon Code</label> <span id='IconShow'></span>
                                <input onchange="ChangeIconShow();" type="text" class="form-control" name="IconCode" id="IconCode" maxlength="150" autocomplete="off" required>
                            </div>
                       
                        </div> 
                        <div class="col-md-12">
                            <div class="row" style="gap: 10%;">
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateIcon();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mt-3 text-right"> -->
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="Iconedit" id="Iconedit">
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#IconForm');"
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
<script src="views/js/master/wnv_authicon.js"></script>
<?php
$ActionTkIcon = new controller_auth_icon;
$ActionTkIcon->Action_AuthIcon();
?>