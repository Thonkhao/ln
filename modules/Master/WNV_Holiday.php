<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0" style="margin-bottom: 0;">Holiday</h3>
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
                <button class="fixed-circle-button" onclick="cleardata();" data-toggle="modal" data-target="#modal-InsEditHoliday">
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
                                            <th class="min-width40" style="width:15%;">Date</th>
                                            <th class="min-width70" style="width:15%;">Discription</th>
                                            <th class="min-width40" style="width:5%;text-align:center;">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $Holiday_List = controller_holiday::GetHoliday();
                                        if ($Holiday_List === false) {
                                            echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                            echo '<a style="display:none;"  data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                        } else {
                                            if ($Holiday_List != null && count($Holiday_List) > 0) {
                                                ## Loop ตามข่อมูลใน Array
                                                foreach ($Holiday_List as $key => $resultHoliday_List) {
                                                    $Hol_Date = $resultHoliday_List['Hol_Date'];
                                                    $Hol_DateShow = $resultHoliday_List['Hol_DateShow'];
                                                    $Hol_Desc = $resultHoliday_List['Hol_Desc'];
                                                    $i++;
                                        ?>
                                                    <tr>

                                                        <td style="text-align:center;padding-right: 0;"><?= $Hol_DateShow; ?></td>
                                                        <td style="padding-right: 0;"><?= $Hol_Desc; ?></td>
                                                        <td style="text-align:center;padding-right: 0;">
                                                            <a class="deleteMenu ml-2" title="ลบ" onclick="DeleteHol_D(<?= $i; ?>);"><i class="fa fa-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <input type="text" style="display:none;" name="editshow-<?= $i; ?>" id="editshow-<?= $i; ?>"
                                                        value="<?= $Hol_Date . '|' . $Hol_Desc; ?>">
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
<div class="modal fade" id="modal-InsEditHoliday" aria-modal="true">
    <div class="modal-dialog" style="max-width:550px;margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Add/Edit Holiday</b></font>
                <hr>
                <form id="HolidayForm" method="post" action="?s=master&r=wnv_holiday">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Date<span style="color:red">*</span></label>
                                <input type="date" class="form-control" name="Hol_Date" id="Hol_Date" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description<span style="color:red">*</span></label>
                                <input type="text" class="form-control" name="Hol_Desc" id="Hol_Desc" maxlength="50" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row" style="gap: 10%;">
                                <button type="button" style="width:45%" class="btn btn btn-outline-dark" id="saveBtn" onclick="ValidateHoliday();">Save</button>
                                <button type="reset" style="width:45%" class="btn btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="I">
                    <input type="hidden" name="Hol_Date_edit" id="Hol_Date_edit">
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
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#HolidayForm');"
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
<script src="views/js/master/wnv_holiday.js"></script>
<?php
$ActionHoliday = new controller_holiday;
$ActionHoliday->Action_Holiday();
?>