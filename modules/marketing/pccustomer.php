<?php
/**
 * PC & Customer
 * หน้าผูก PC (พนักงานขาย) กับ Customer
 * PC ID / Customer: ดึงรายการจริงจาก SQL Server (ajax/pccustomer.ajax.php) แล้วเติมเป็น select2
 * ตารางด้านล่าง: รายการ PC ที่ผูกกับ Customer ไว้แล้วทั้งหมด ดึงจาก SQL Server (Models/marketing/pccustomer.model.php)
 */
$PCCustomer_List = controller_pccustomer::GetPCCustomerList();
?>
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row all-center">
            <h3 class="mb-0">Customer Map PC</h3>
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
                        <form id="pcCustomerForm" method="post" action="?s=marketing&r=pccustomer">
                            <!-- PC Detail -->
                            <label class="d-block weight-700 border-bottom pb-1 mb-3">PC Detail</label>
                            <div class="row mb-3">
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="PCID">PC ID:</label>
                                        <select class="form-control select2" style="width:100%;" id="PCID" name="PCID" required>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="PCName">ชื่อ-สกุล:</label>
                                        <input type="text" class="form-control" id="PCName" name="PCName" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="PCPosition">ตำแหน่ง:</label>
                                        <input type="text" class="form-control" id="PCPosition" name="PCPosition" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Detail -->
                            <label class="d-block weight-700 border-bottom pb-1 mb-3">Customer Detail</label>
                            <div class="row mb-3">
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="BPID">Customer:</label>
                                        <select class="form-control select2" style="width:100%;" id="BPID" name="BPID" required>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-9">
                                    <div class="form-group">
                                        <label for="BPName">Name:</label>
                                        <input type="text" class="form-control" id="BPName" name="BPName" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="gap: 40%; padding: 0 15%;">
                                <button type="button" style="width:30%" class="btn btn-outline-dark" id="saveBtn" onclick="ValidatePCCustomer();">Save</button>
                                <button type="reset" style="width:30%" class="btn btn-outline-dark" id="resetBtn" onclick="cleardata();">Clear</button>
                            </div>

                            <input type="hidden" id="action" name="action" value="I">
                            <input type="hidden" name="pccustedit" id="pccustedit">
                        </form>
                    </div>
                </div>
                <!-- /.card -->

                <!-- ตาราง PC ที่ผูกกับ Customer แล้วทั้งหมด -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pccustomerTable" class="table table-head-fixed table-bordered" style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th style="width:6%;">No.</th>
                                        <th>PC ID</th>
                                        <th>ชื่อ-สกุล PC</th>
                                        <th>Customer</th>
                                        <th>Name</th>
                                        <th>ตำแหน่ง</th>
										<th>Email</th>
										<th>Phone</th>
                                        <th style="width:6%;">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($PCCustomer_List === false) {
                                        echo '<input type="text" style="display:none;" name="NumAlert" id="NumAlert" value="6">';
                                        echo '<a style="display:none;" data-toggle="modal" id="queryfalsemodel" data-target="#QueryFalse" class="btn btn-block btn-custom4 btn-sm a-logout">LoadFail</a>';
                                    } elseif ($PCCustomer_List != null && count($PCCustomer_List) > 0) {
                                        $i = 0;
                                        foreach ($PCCustomer_List as $resultPCCust) {
                                            $i++;
                                            $PCID = $resultPCCust['PCID'] ?? '';
                                            $PCName = $resultPCCust['PCName'] ?? '';
                                            $BPID = $resultPCCust['BPID'] ?? '';
                                            $BPName = $resultPCCust['BPName'] ?? '';
                                            $Position = $resultPCCust['Position'] ?? '';
											$Email = $resultPCCust['Email'] ?? '';
                                            $Phone = $resultPCCust['Phone'] ?? '';
                                    ?>
                                            <tr>
                                                <td style="text-align:center;"><?= $i; ?></td>
                                                <td style="text-align:center;"><?= $PCID; ?></td>
                                                <td><?= $PCName; ?></td>
                                                <td style="text-align:center;"><?= $BPID; ?></td>
                                                <td><?= $BPName; ?></td>
                                                <td style="text-align:center;"><?= $Position; ?></td>
												<td><?= $Email;?></td>
												<td><?= $Phone;?></td>
                                                <td style="text-align:center;">
                                                    <a class="deletePCCust" title="ลบ" onclick="DeletePCCustomer('<?= $PCID; ?>','<?= $BPID; ?>');"><i class="fa fa-trash text-danger"></i></a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<!-- Start :: Modal ยืนยัน -->
<div class="modal fade" id="Confirmaction" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <font style="font-size:18px;"><b><span id="textshow"></span></b></font>
                <br>
                <div class="row">
                    <div class="container-fluid col-md-8">
                        <div class="row" style="padding-top:5px;">
                            <div class="row">
                                <div class="col-6" style="padding-top:5px;">
                                    <button id="Subnitbtn" type="button" onclick="handleSubmitViaClick('#pcCustomerForm');"
                                        class="btn btn-outline-dark" style="width:100%;">Confirm</button>
                                </div>
                                <div class="col-6" style="padding-top:5px;">
                                    <button id="cancelledbtn" style="border-radius: 0.3rem;width:100%;"
                                        type="button" class="btn btn-outline-dark"
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
<!-- END :: Modal -->

<script src="views/js/marketing/pccustomer.js?v=<?php echo time(); ?>"></script>
<script>
    $(document).ready(function () {
        $('#pccustomerTable').DataTable({
            order: [],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            searching: true,
            autoWidth: true,
            scrollX: true
        });
    });
</script>
<?php
controller_pccustomer::Action_PCCustomer();
?>
