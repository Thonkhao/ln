<?php

/**
 * On-hand Report (2 Companies) - หน้าเลือกเงื่อนไขสำหรับพิมพ์/ดูรายงาน On-hand
 * เงื่อนไขเหลือแค่ Company (เลือกได้ทั้งสองบริษัทพร้อมกัน) และวันที่ (ณ วันที่เดียว)
 *
 * TODO ก่อนใช้งานจริง:
 * 1. สร้าง backend (rpt_onhand2comp.php) สำหรับเรียก SSRS report จริง แล้วแก้ form action ด้านล่างให้ตรง
 * 2. เช็คชื่อ parameter จริงที่ SSRS report ต้องการ (Comp/Date/...) ให้ตรงกับ input name ด้านล่าง
 */
?>
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <h3 class="mb-0">Item Inventory Report</h3>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<!-- <div class="app-content"> -->
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="onhand2CompReportForm" method="GET" action="views/modules/report/rpt_onhand2comp.php" target="_blank">
                            <input type="hidden" name="report" value="WNV_LNWhseOnhand2Comp">
                            <input type="hidden" name="mode" id="reportMode" value="preview">

                            <!-- Company (disabled - เลือกทั้งสองบริษัทเสมอ ใช้ hidden input คู่กันเพื่อให้ยังส่งค่าไปได้ทั้งที่ checkbox ถูก disable) -->
                            <input type="hidden" name="company[]" value="1000">
                            <input type="hidden" name="company[]" value="2000">
                            <div class="row justify-content-center text-center g-4 mb-3">
                                <div class="col-auto">
                                    <label class="d-block weight-700 mb-2" style="text-align:left">Company</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="checkbox" id="comp1000" checked disabled>
                                        <label class="form-check-label" for="comp1000">1000 WNV</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="checkbox" id="comp2000" checked disabled>
                                        <label class="form-check-label" for="comp2000">2000 HTR</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="Date" class="d-block weight-700 mb-2" style="text-align:left">วันที่ <span class="text-danger">*</span></label>
                                    <input type="hidden" name="Date" value="<?php echo date('Y-m-d'); ?>">
                                    <input type="date" class="form-control" id="Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" style="max-width:170px;" disabled required>
                                </div>
                            </div>

                            <hr>

                            <!-- Submit Buttons -->
                            <div class="d-flex row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" id="btnPrint">
                                        <i class="bi bi-printer me-1"></i>Print
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
<!-- </div> -->
<!--end::App Content-->
