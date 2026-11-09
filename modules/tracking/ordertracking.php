<?php
/**
 * Order Tracking Module
 * แสดงหน้า Order Tracking Dashboard
 * 
 * Performance: JSON Data + DataTables deferRender (เร็วกว่า HTML 5-10x)
 * Security: CSRF token, input validation
 */

//require_once 'controller/ordertracking/ordertracking.controller.php';

// Parse and validate month/year from URL
$parsed = controller_ordertracking::parseMonthYear($_GET["my"] ?? null);
$year = $parsed['year'];
$moth = $parsed['month'];
$monthyearS = $year . '-' . str_pad($moth, 2, '0', STR_PAD_LEFT);

// หน้า page แสดงทันที ไม่ต้องรอ query - ข้อมูลตารางจะโหลดผ่าน AJAX (ดู ordertracking.js)
// loading overlay จะซ่อนก็ต่อเมื่อโหลดข้อมูล + สร้าง DataTable เสร็จแล้ว
?>

<!-- Full Page Loading Overlay (using project standard) -->
<div id="loading-overlay" style="display:flex;">
    <center>
        <div class="loader" style="margin-top:20%;"></div>
    </center>
</div>

<!-- Order Tracking Styles -->
<link rel="stylesheet" href="views/css/ordertracking.css?v=<?php echo filemtime(__DIR__ . '/../../css/ordertracking.css'); ?>">

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <h3 class="mb-0">Order Tracking</h3>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Report Buttons + Salesperson Row -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-8">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modal-printReport">
                                    <i class="fa-solid fa-file-lines"></i> Print Report
                                </button>
                            </div>
                            <div class="col-md-4 text-md-end">
                               <!-- <span class="text-muted"><b><?php //echo $SalseDisp; ?></b></span> -->
                            </div>
                        </div>
                        <!-- Filter Row -->
                        <div class="row mb-3">
                            <div class="col-md-8 col-12">
                                <div style="overflow-x:auto;">
                                    <!-- NOTE: All tab always active on load - client-side filtering handles the rest -->
                                    <ul class="nav nav-pills" id="orderStatusTabs" style="flex-wrap: nowrap;">
                                        <li class="nav-item">
                                            <a class="nav-link active text-nowrap" href="#" data-status="A">
                                                All (<span id="count-all">0</span>)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-nowrap" href="#" data-status="12">
                                                Ordered (<span id="count-ordered">0</span>)
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link text-nowrap" href="#" data-status="3">
                                                Invoiced (<span id="count-invoiced">0</span>)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-nowrap" href="#" data-status="4">
                                                Shipping (<span id="count-shipping">0</span>)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-nowrap" href="#" data-status="56">
                                                Completed (<span id="count-completed">0</span>)
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="d-flex align-items-center justify-content-end">
                                    <label class="col-form-label me-2 text-nowrap">เดือน - ปี :</label>
                                    <input type="month" id="monthSelector" class="form-control form-control-sm" style="width:auto;"
                                           min="<?php echo date("Y-m", strtotime("-2 month")); ?>"
                                           value="<?php echo $monthyearS; ?>">
                                </div>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="position-relative" style="min-height: 200px;">
                            <div class="table-responsive" id="tableContainer" data-sales-office="All">
                                <!-- Table structure - DataTables will populate from JSON -->
                                <table id="orderTrackingDataTable" class="table table-head-fixed table-bordered table-hover" style="width:100%!important;">
                                    <thead>
                                        <tr>
                                            <th style="min-width:50px;">No.</th>
                                            <th style="min-width:100px;">SO Num</th>
											<th style="min-width:70px;">Load Seq</th>
                                            <th style="min-width:90px;">Cust Group</th>
                                            <th style="min-width:100px;">Order Date</th>
                                            <th style="min-width:100px;">Due Date</th>
                                            <th style="min-width:100px;">Exp. Date</th>
                                            <th style="min-width:180px;">Customer</th>
                                            <th style="min-width:60px;">Type</th>
                                            <th style="min-width:100px;">Ship Type</th>
                                            <th style="min-width:100px;">Cust PO</th>
                                            <th style="min-width:130px;">Salesperson</th>
                                            <th style="min-width:100px;">Load No</th>
                                            <th style="min-width:100px;">Invoice No</th>
                                            <th style="min-width:100px;">Invoice Date</th>
                                            <th style="min-width:100px;">Trackage No</th>
                                            <th style="min-width:90px;">ทะเบียนรถ</th>
                                            <th style="min-width:90px;">DriverTel</th>
											<th style="min-width:90px;">SalesPCTel</th>
                                            <th style="min-width:110px;">Status</th>
                                            <th style="min-width:100px;">วันที่ว่าจ้าง</th>
                                            <th style="min-width:100px;">วันที่สำเร็จ</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<!-- Start :: Model -->
<div class="modal fade" id="modal-printReport" aria-modal="true">
    <div class="modal-dialog" style="max-width:700px;margin-top: 12%;">
        <div class="modal-content">
            <div class="modal-body">
                <font style="font-size:18px;"><b>Print Report</b></font>
                <hr>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="printReportType" id="printReportSummary" value="summary" checked>
                        <label class="form-check-label" for="printReportSummary">Summary</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="printReportType" id="printReportDetail" value="detail">
                        <label class="form-check-label" for="printReportDetail">Detail</label>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center mb-2">
                    <div class="col-12 col-sm-2"><label class="col-form-label" for="printMonthYear">เดือน-ปี:</label></div>
                    <div class="col-12 col-sm-4">
                        <input type="month" id="printMonthYear" class="form-control form-control-sm"
                               min="<?php echo date("Y-m", strtotime("-2 month")); ?>"
                               value="<?php echo $monthyearS; ?>">
                    </div>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-12 col-sm-2"><label class="col-form-label">Status:</label></div>
                    <div class="col-12 col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="printStatus[]" id="printStatusOrdered" value="12" checked> 
                            <label class="form-check-label" for="printStatusOrdered">Ordered</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="printStatus[]" id="printStatusInvoiced" value="3" checked>
                            <label class="form-check-label" for="printStatusInvoiced">Invoiced</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="printStatus[]" id="printStatusShipping" value="4" checked>
                            <label class="form-check-label" for="printStatusShipping">Shipping</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="printStatus[]" id="printStatusCompleted" value="56" checked>
                            <label class="form-check-label" for="printStatusCompleted">Completed</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center mb-2">
                    <div class="col-12 col-sm-4"><label class="col-form-label">Cust Group:</label></div>
                    <div class="col-6 col-sm-4">
                        <select class="form-control select2" onchange="SameValue('printCustGroupS','printCustGroupE');" name="printCustGroupS" id="printCustGroupS">
                            <option value="">-- จาก --</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4">
                        <select class="form-control select2" name="printCustGroupE" id="printCustGroupE">
                            <option value="">-- ถึง --</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-12 col-sm-4"><label class="col-form-label">Salesperson:</label></div>
                    <div class="col-12 col-sm-8">
                        <select class="form-control select2" name="printSalesperson" id="printSalesperson">
                            <option value="">-- ทั้งหมด --</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3" style="gap: 10%;">
                    <button type="button" style="width:45%" class="btn btn-outline-dark" id="printReportConfirmBtn">Print</button>
                    <button type="button" style="width:45%" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END :: Model -->

<!-- Order Tracking Script -->
<script>
    window.orderTrackingConfig = {
        year: <?php echo json_encode($year); ?>,
        month: <?php echo json_encode($moth); ?>,
        salesOffice: 'All',
        // yy/mm/salesoff ไม่ได้ฝังไว้ในนี้แล้ว - ordertracking.js จะต่อให้เองตอนกด Print
        // โดยอ่านเดือน-ปีจาก #printMonthYear ใน modal (ผู้ใช้เลือกคนละเดือนกับหน้าหลักได้)
        reportUrls: {
            summary: <?php echo json_encode('views/modules/report/rpt_ordertracking.php?report=WNV_LNOrderTracking'); ?>,
            detail: <?php echo json_encode('views/modules/report/rpt_ordertracking.php?report=WNV_LNOrderTrackingDetail'); ?>
        }
    };
</script>
<script src="views/js/tracking/ordertracking.js?v=<?php echo time(); ?>"></script>
