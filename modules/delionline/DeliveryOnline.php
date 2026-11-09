 <!--begin::App Content Header-->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <style>
     .note-editor.note-airframe,
     .note-editor.note-frame {
         border: 1px solid #00000032 !important;
     }
     /* ซ่อน input จริงของ flatpickr (altInput จะแสดงแทน) */
     #ReportDate { display: none !important; }
 </style>
 <div class="app-content-header">
     <!--begin::Container-->
     <div class="container-fluid">
         <!--begin::Row-->
         <div class="row all-center">
             <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                 <h3 class="mb-0">Delivery Online</h3>
             </div>
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
             <div class="col-lg-12">
                 <!-- Input Card -->
                 <div class="card mb-4">
                     <div class="card-body">
                         <form id="myForm">
                             <input type="hidden" id="deliveryMode" value="insert">
                             <input type="hidden" id="editShipDate" value="">
                             <div class="d-flex row g-2 align-items-end">
                                 <div id="loading-overlay"
                                     style="display:none;color:#fff;border:1px solid #fff;position: absolute;top:0;width:100%;background-color:#fff;height:100%;">
                                     <center>
                                         <div class="loader" style="margin-top:20%;"></div>
                                     </center>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="ReportDate">วันที่<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="ReportDate" name="ReportDate"
                                             autocomplete="off" value="<?= date('Y-m-d'); ?>" required readonly>
                                         <span id="ReportDateError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Lazada_Qty">Lazada Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Lazada_Qty" name="Lazada_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Lazada_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Shopee_Qty">Shopee Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Shopee_Qty" name="Shopee_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Shopee_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Tiktok_Qty">Tiktok Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Tiktok_Qty" name="Tiktok_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Tiktok_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Line_Qty">Line Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Line_Qty" name="Line_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Line_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Web_Qty">Web Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Web_Qty" name="Web_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Web_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label for="Other_Qty">Other Qty<span class="requiredRed">*</span></label>
                                         <input type="text" class="form-control" id="Other_Qty" name="Other_Qty" maxlength="10"
                                             autocomplete="off" value="0" placeholder="0" onkeypress="return OnlyInt(event)" required>
                                         <span id="Other_QtyError" style="color:red;" class="mb-2 text-error mb-2"></span>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <label>&nbsp;</label>
                                         <div class="d-flex gap-2">
                                             <button type="button" id="btn-save-delivery" onclick="saveDeliveryOnline();"
                                                 class="btn btn-outline-dark form-control" style="flex:1;">
                                                 <i class="bi bi-save" id="btn-save-icon"></i>
                                                 <span id="btn-save-label">Save</span>
                                             </button>
                                             <button type="button" id="btn-cancel-edit" onclick="clearDeliveryForm();"
                                                 class="btn btn-outline-secondary form-control" style="display:none;flex:1;">
                                                 <i class="bi bi-x-circle"></i> Cancel
                                             </button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>
                 <!-- /Input Card -->

                 <!-- Table Card -->
                 <div class="card mb-4">
                     <div class="card-body">
                         <div class="table-responsive">
                             <table id="tableDelivery" class="table table-head-fixed" style="width:100%!important">
                                 <thead>
                                     <tr>
                                         <!-- <th class="" style="width:1%!important;"></th> -->
                                         <th class="min-width40" style="text-align:center;width:3%!important;"></th>
                                         <th class="min-width70" style="text-align:center;width:10%!important;">Ship Date</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Lazada</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Shopee</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Tiktok</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Line</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Web</th>
                                         <th class="min-width70 " style="text-align:center;width:10%!important;">Other</th>
                                         <!-- <th class="min-width70" style="width:12%!important;">Create Date</th> -->
                                         <th class="" style="width:1%!important;"></th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
                 <!-- /Table Card -->

             </div>
         </div>
         <!--end::Row-->
     </div>
     <!--end::Container-->
 </div>
 <!--end::App Content-->

 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 <script src="views/js/delionline/DeliveryOnline.js?v=<?php echo time(); ?>"></script>
