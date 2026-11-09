<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <h3 class="mb-0">Actual Delivery Daily Report</h3>
            </div>
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
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="reportForm" method="GET" action="views/modules/report/rpt_actdelidaily.php" target="_blank">
                            <!-- Hidden report name -->
                            <input type="hidden" name="report" value="WNV_LNActDeliDaily">
                            
                            <div class="d-flex row">
                                <!-- Service Order Number Range -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="TrkDS">TruckageDate (From)<span class="requiredRed">*</span></label>
                                        <input type="date" class="form-control" name="TrkDS" id="TrkDS" 
                                               placeholder="Date"  autocomplete="off"
                                               onchange="SameValue('TrkDS','TrkDE');"  required>
                                        <span id="SroSError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="TrkDE">TruckageDate (To)<span class="requiredRed">*</span></label>
                                        <input type="date" class="form-control" name="TrkDE" id="TrkDE" 
                                               placeholder="Date" autocomplete="off"
                                             required>
                                        <span id="SroEError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Submit Buttons -->
                            <div class="d-flex row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary me-2" id="btnSubmit">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>Export Excel
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="btnClear">
                                        <i class="bi bi-x-circle me-1"></i>ล้างข้อมูล
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
</div>
<!--end::App Content-->

<script>


document.addEventListener('DOMContentLoaded', function() {
    // Clear form
    document.getElementById('btnClear').addEventListener('click', function() {
        document.getElementById('TrkDS').value = '';
        document.getElementById('TrkDE').value = '';
    });



    // Validate form
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        const docnumFrom = document.getElementById('TrkDS').value.trim();
        const docnumTo = document.getElementById('TrkDE').value.trim();

        // เช็คห้ามเป็นค่าว่าง
        if (!docnumFrom) {
            e.preventDefault();
            toastr.error('กรุณาระบุ Truckage Date เริ่มต้น');
            document.getElementById('TrkDS').focus();
            return false;
        }

        if (!docnumTo) {
            e.preventDefault();
            toastr.error('กรุณาระบุ Truckage Date สิ้นสุด');
            document.getElementById('TrkDE').focus();
            return false;
        }

        // เช็คค่าเริ่มต้นต้องไม่มากกว่าสิ้นสุด
        if (docnumFrom > docnumTo) {
            e.preventDefault();
            toastr.error('Service Order Number เริ่มต้นต้องไม่มากกว่าสิ้นสุด');
            return false;
        }
    });
});
</script>
