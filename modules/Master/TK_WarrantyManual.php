<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row all-center">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0">
                    <i class="fa-solid fa-users" style="color:#555;margin-right:8px;"></i>
                    บันทึกข้อมูลลูกค้า
                </h3>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>

            <div class="col-md-12 col-lg-8">

                <!-- /.card -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color:#fff; border-bottom:1px solid #e9ecef; padding: 16px 20px;">
                        <h5 class="mb-0" style="font-weight:700; color:#333;">
                            <i class="fa-solid fa-user-plus" style="color:#6c757d;margin-right:8px;"></i>
                            ข้อมูลลูกค้า
                        </h5>
                    </div>

                    <div class="card-body" style="padding:24px;">
                        <form id="CustomerForm" method="post" action="?s=master&r=TK_WarrantyManual">
                            <div class="row">

                                <!-- Serial Number -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cus_serialnumber">
                                            Serial Number <span style="color:#dc3545;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-barcode" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="text"
                                                class="form-control"
                                                id="cus_serialnumber"
                                                name="cus_serialnumber"
                                                placeholder="กรอก Serial Number"
                                                maxlength="17"
                                                autocomplete="off"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="serial-spinner" style="display:none; background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-spinner fa-spin" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="serial-status" style="margin-top:6px; font-size:13px;"></div>
                                    </div>
                                </div>

                                <!-- ชื่อลูกค้า -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cus_name">
                                            ชื่อ-นามสกุล <span style="color:#dc3545;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-user" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="text"
                                                class="form-control"
                                                id="cus_name"
                                                name="cus_name"
                                                placeholder="กรอกชื่อ-นามสกุลลูกค้า"
                                                maxlength="255"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- เบอร์โทรศัพท์ -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cus_phone">
                                            เบอร์โทรศัพท์ <span style="color:#dc3545;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-phone" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="tel"
                                                class="form-control"
                                                id="cus_phone"
                                                name="cus_phone"
                                                placeholder="0xx-xxx-xxxx"
                                                maxlength="20"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- อีเมล -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cus_email">
                                            อีเมล <span style="color:#dc3545;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-envelope" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="email"
                                                class="form-control"
                                                id="cus_email"
                                                name="cus_email"
                                                placeholder="example@email.com"
                                                maxlength="100"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- วันที่ซื้อสินค้า -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cus_datepurchase">
                                            วันที่ซื้อสินค้า <span style="color:#dc3545;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-calendar-days" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="date"
                                                class="form-control"
                                                id="cus_datepurchase"
                                                name="cus_datepurchase"
                                                max="<?= date('Y-m-d') ?>"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cus_exp_datewarranty">
                                            วันที่หมดประกัน
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background:#f8f9fa; border-color:#ced4da;">
                                                    <i class="fa-solid fa-calendar-days" style="color:#6c757d;"></i>
                                                </span>
                                            </div>
                                            <input type="date"
                                                class="form-control"
                                                id="cus_exp_datewarranty"
                                                name="cus_exp_datewarranty"
                                                style="background-color:#e9ecef;"
                                                readonly
                                                tabindex="-1">
                                        </div>
                                    </div>

                                </div>

                                <!-- Divider -->
                                <div class="col-md-12">
                                    <hr style="border-color:#e9ecef; margin:16px 0;">
                                </div>

                                <!-- ปุ่มบันทึก -->
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center gap-2" style="gap:12px;">
                                        <button type="button"
                                            id="saveCustomerBtn"
                                            onclick="ConfirmSaveCustomer();"
                                            class="btn btn-outline-dark"
                                            style="min-width:150px; font-weight:600;"
                                            disabled>
                                            <i class="fa-solid fa-floppy-disk" style="margin-right:6px;"></i>
                                            บันทึก
                                        </button>
                                        <button type="reset"
                                            class="btn btn-outline-secondary"
                                            style="min-width:120px;"
                                            onclick="resetSerialStatus();">
                                            <i class="fa-solid fa-eraser" style="margin-right:6px;"></i>
                                            ล้างข้อมูล
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <!-- Hidden fields -->
                            <input type="hidden" id="action" name="action" value="I">
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>

            <div class="col-lg-2"></div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!--end::App Content-->


<!-- ==================== Confirm Modal ==================== -->
<div class="modal fade" id="modal-ConfirmSaveCustomer" aria-modal="true">
    <div class="modal-dialog" style="max-width:420px; margin-top:8%;">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center; padding:30px 24px;">
                <i class="fa-solid fa-circle-question" style="font-size:64px; color:#555; margin-bottom:16px;"></i>
                <h5 style="font-size:20px; font-weight:700; margin-bottom:6px;">ยืนยันการบันทึก</h5>
                <p style="color:#777; margin-bottom:24px;">คุณต้องการบันทึกข้อมูลลูกค้านี้ใช่หรือไม่?</p>

                <div class="row">
                    <div class="col-6" style="padding:0 8px 0 0;">
                        <button type="button"
                            id="btnConfirmSave"
                            onclick="SubmitRegisterCustomer();"
                            class="btn btn-outline-dark"
                            style="width:100%; font-weight:600;">
                            <i class="fa-solid fa-check" style="margin-right:4px;"></i>
                            ยืนยัน
                        </button>
                    </div>
                    <div class="col-6" style="padding:0 0 0 8px;">
                        <button type="button"
                            onclick="$('#modal-ConfirmSaveCustomer').modal('hide');"
                            class="btn btn-outline-secondary"
                            style="width:100%;">
                            <i class="fa-solid fa-xmark" style="margin-right:4px;"></i>
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== End Confirm Modal ==================== -->

<script src="views/js/master/tk_warrantymanual.js"></script>