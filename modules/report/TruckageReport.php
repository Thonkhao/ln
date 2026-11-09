<?php

/**
 * Truckage Report - หน้าเลือกเงื่อนไขสำหรับพิมพ์/ดูรายงานใบว่าจ้างรถบรรทุก
 * เรียก SSRS report เดียว (wnv-report-truckage) โดยส่ง reportType เป็น parameter
 * ดู backend ที่ views/modules/report/rpt_truckage.php
 *
 * TODO ก่อนใช้งานจริง:
 * 1. เช็คชื่อ parameter จริงที่ rpt_truckage.php ส่งให้ SSRS (ReportType/TruckageType/Status/...)
 *    ให้ตรงกับที่ report กำหนดไว้จริง - เปิด ...&debug=1 เพื่อให้ SSRS แจ้งชื่อ parameter ที่ต้องการ
 * 2. เติม option ให้ dropdown Truckage Num / Vendor / License Plate (Starting/Ending)
 *    เช่น ผูก select2 กับ AJAX endpoint ที่ query จากตารางที่เกี่ยวข้อง
 */
?>
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <h3 class="mb-0">Truckage Report</h3>
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
                        <form id="truckageReportForm" method="GET" action="views/modules/report/rpt_truckage.php" target="_blank">
                            <!-- report ต่างกันตาม Report Type ที่เลือก - ค่าเริ่มต้นตรงกับตัวที่ checked อยู่ (DAILY_SUMMARY), เปลี่ยนตาม data-report ของ radio ที่เลือกด้วย JS -->
                            <!-- TODO: ยืนยันชื่อ SSRS report จริงของแต่ละ Report Type แล้วแก้ data-report / value ด้านล่างให้ตรง -->
                            <input type="hidden" name="report" id="reportName" value="WNV_LNTruckageDetail">
                            <input type="hidden" name="mode" id="reportMode" value="preview">
                            <!-- ค่ารวมของ checkbox truckageType[]/status[] ที่ติ๊กไว้ (เช่น ติ๊กครบ 3 อัน -> "123") ยิงตอน submit โดย truckage_report.js -->
                            <input type="hidden" name="truckageType" id="truckageTypeCombined" value="">
                            <input type="hidden" name="status" id="statusCombined" value="">

                            <!-- Report Type / Truckage Type / Status -->
                            <div class="row mb-3">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="d-block weight-700 border-bottom pb-1 mb-2">Report Type</label>
                                    <!-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportType" id="rtHire" value="HIRE" data-report="wnv-report-truckage-hire">
                                        <label class="form-check-label" for="rtHire">ใบว่าจ้าง</label>
                                    </div> -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportType" id="rtDailySummary" value="DAILY_SUMMARY" data-report="WNV_LNTruckageDetail" checked>
                                        <label class="form-check-label" for="rtDailySummary">สรุปใบว่าจ้างประจำวัน</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportType" id="rtVendorSummary" value="VENDOR_SUMMARY" data-report="WNV_LNTruckageDetailByVend">
                                        <label class="form-check-label" for="rtVendorSummary">สรุปใบว่าจ้างราย Vendors</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportType" id="rtshiptosReport" value="Shiptos_REPORT" data-report="WNV_LNTruckageShipTo">
                                        <label class="form-check-label" for="rtshiptosReport">สรุปใบว่าจ้าง By Ship-tos</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="d-block weight-700 border-bottom pb-1 mb-2">Truckage Type</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="truckageType[]" id="ttShipment" value="1" checked>
                                        <label class="form-check-label" for="ttShipment">Load</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="truckageType[]" id="ttTransfer" value="2" checked>
                                        <label class="form-check-label" for="ttTransfer">Transfer</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="truckageType[]" id="ttManual" value="3" checked>
                                        <label class="form-check-label" for="ttManual">Manual</label>
                                    </div>

                                    <!-- <label class="d-block weight-700 border-bottom pb-1 mb-2 mt-3">Display</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="display[]" id="dispAmountCub" value="AMOUNT_CUB" checked>
                                        <label class="form-check-label" for="dispAmountCub">Amount and CUB</label>
                                    </div> -->
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="d-block weight-700 border-bottom pb-1 mb-2">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" id="stOpened" value="Printed" checked>
                                        <label class="form-check-label" for="stOpened">Printed/ตรวจสอบบิล</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" id="stInvoiced" value="Invoiced" checked>
                                        <label class="form-check-label" for="stInvoiced">ส่งออก LN</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" id="stCanceled" value="Cancel" >
                                        <label class="form-check-label" for="stCanceled">Cancel</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Company / Format -->
                            <div class="row mb-3">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="d-block weight-700 border-bottom pb-1 mb-2">Company</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="company" id="comp1000" value="1000">
                                        <label class="form-check-label" for="comp1000">1000 WNV</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="company" id="comp2000" value="2000" checked>
                                        <label class="form-check-label" for="comp2000">2000 HTR</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="d-block weight-700 border-bottom pb-1 mb-2">Format</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="fmtPdf" value="PDF" checked>
                                        <label class="form-check-label" for="fmtPdf">PDF</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="fmtExcel" value="EXCELOPENXML">
                                        <label class="form-check-label" for="fmtExcel">Excel</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Filter Range: Starting / Ending -->
                            <div class="row mb-1">
                                <div class="d-none d-sm-block col-sm-3"></div>
                                <div class="col-6 col-sm-4"><label class="weight-700">Starting:</label></div>
                                <div class="col-6 col-sm-4"><label class="weight-700">Ending:</label></div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-3" style="text-align:right;"><label for="DateS" class="col-form-label">Truckage Date: <span class="text-danger">*</span></label></div>
                                <div class="col-6 col-sm-4">
                                    <input  onchange="SameValue('DateS','DateE');" type="date" class="form-control" name="DateS" id="DateS" autocomplete="off" required>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <input type="date" class="form-control" name="DateE" id="DateE" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-3" style="text-align:right;"><label for="TrkNumS" class="col-form-label">Truckage Num:</label></div>
                                <div class="col-6 col-sm-4">
                                    <select class="form-control select2"  onchange="SameValue('TrkNumS','TrkNumE');" name="TrkNumS" id="TrkNumS">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (Truckage Num) -->
                                    </select>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <select class="form-control select2" name="TrkNumE" id="TrkNumE">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (Truckage Num) -->
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-3" style="text-align:right;"><label for="VendorS" class="col-form-label">Vendor:</label></div>
                                <div class="col-6 col-sm-4">
                                    <select class="form-control select2"  onchange="SameValue('VendorS','VendorE');" name="VendorS" id="VendorS">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (Vendor) -->
                                    </select>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <select class="form-control select2" name="VendorE" id="VendorE">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (Vendor) -->
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-3" style="text-align:right;"><label for="PlateS" class="col-form-label">License Plate:</label></div>
                                <div class="col-6 col-sm-4">    
                                    <select class="form-control select2" name="PlateS" id="PlateS">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (License Plate) -->
                                    </select>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <select class="form-control select2" name="PlateE" id="PlateE">
                                        <option value="">-- Please select --</option>
                                        <!-- TODO: เติม option จาก AJAX/DB (License Plate) -->
                                    </select>
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

<script src="views/js/report/truckage_report.js?v=<?php echo time(); ?>"></script>