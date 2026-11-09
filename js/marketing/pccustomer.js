/**
 * PC & Customer - หน้าผูก PC (พนักงานขาย) กับ Customer (views/modules/marketing/pccustomer.php)
 * PC ID / Customer ดึงรายการจริงจาก SQL Server ผ่าน ajax/pccustomer.ajax.php แล้วเติมเป็น select2
 */
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- กรุณาเลือก --',
        allowClear: true
    });

    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // เก็บชื่อของแต่ละ PC/Customer ไว้ใช้เติมช่อง Name อัตโนมัติตอนเลือก (ไม่ต้องยิง ajax ซ้ำ)
    var pcNameMap = {};
    var pcPositionMap = {};
    var custNameMap = {};

    function fillSelect(functionname, selectEl, nameMap, positionMap) {
        fetch('ajax/pccustomer.ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ functionname: functionname, csrf_token: csrfToken })
        })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (!res.success || !Array.isArray(res.result)) {
                    toastr.error('โหลดข้อมูล ' + functionname + ' ไม่สำเร็จ');
                    return;
                }
                res.result.forEach(function (item) {
                    var opt = document.createElement('option');
                    opt.value = item.value;
                    opt.textContent = item.text;
                    selectEl.appendChild(opt);
                    nameMap[item.value] = item.name;
                    if (positionMap) {
                        positionMap[item.value] = item.position || '';
                    }
                });
            })
            .catch(function () {
                toastr.error('โหลดข้อมูล ' + functionname + ' ไม่สำเร็จ');
            });
    }

    fillSelect('getPCList', document.getElementById('PCID'), pcNameMap, pcPositionMap);
    fillSelect('getCustomerList', document.getElementById('BPID'), custNameMap);

    // เลือก PC แล้วเติมชื่อ-สกุล PC และตำแหน่งอัตโนมัติ (ช่องเดียว, read-only)
    $('#PCID').on('change', function () {
        var val = $(this).val();
        $('#PCName').val(val ? (pcNameMap[val] || '') : '');
        $('#PCPosition').val(val ? (pcPositionMap[val] || '') : '');
    });

    // เลือก Customer แล้วเติมชื่อ Customer อัตโนมัติ (read-only)
    $('#BPID').on('change', function () {
        var val = $(this).val();
        $('#BPName').val(val ? (custNameMap[val] || '') : '');
    });
});

// ประกาศ Modal ยืนยัน ครั้งเดียว
let Modelaction = new bootstrap.Modal(document.getElementById('Confirmaction'));

function hideModel() {
    Modelaction.hide();
}

function Confirmaction() {
    Modelaction.show();
}

function cleardata() {
    $('#action').val('I');
    $('#PCID').val(null).trigger('change');
    $('#PCName').val('');
    $('#BPID').val(null).trigger('change');
    $('#BPName').val('');
}

function ValidatePCCustomer() {
    var PCID = $('#PCID').val();
    var BPID = $('#BPID').val();
    if (!PCID || !BPID) {
        toastr.error('กรุณาเลือก PC และ Customer ให้ครบถ้วน');
        return;
    }
    $('#action').val('I');
    $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    Confirmaction();
}

function DeletePCCustomer(pcid, bpid) {
    $('#action').val('D');
    $('#pccustedit').val(pcid + '|' + bpid);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}
