var serialVerified = false;

// Set วันที่ปัจจุบันเป็น default
document.addEventListener('DOMContentLoaded', function () {
    var today = new Date().toISOString().split('T')[0];
    var el = document.getElementById('cus_datepurchase');
    if (el) { el.max = today; el.value = today; }
});

// ── Serial Number: blur → verify ──────────────────────── ──────────────────────
$('#cus_serialnumber').on('blur', function () {
    var serial = $(this).val().trim();
    if (!serial) {
        resetSerialStatus();
        return;
    }

    setSerialLoading(true);

    $.ajax({
        url: 'ajax/tk_warrantymanual.ajax.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            csrf_token:   $('meta[name="csrf-token"]').attr('content'),
            functionname: 'verify_serial',
            arguments:    [serial]
        }),
        success: function (res) {
            setSerialLoading(false);
            if (res.can_register) {
                clearCustomerForm();
                setSerialStatus('ok', '<i class="fa-solid fa-circle-check"></i> ' + res.message);
                $('#saveCustomerBtn').prop('disabled', false);
                serialVerified = true;
            } else if (res.already_registered && res.data) {
                // ลงทะเบียนไปแล้ว แต่ยังให้แก้ไข/บันทึกซ้ำ (อัปเดต) ได้
                fillCustomerForm(res.data);
                setSerialStatus('ok', '<i class="fa-solid fa-circle-check"></i> ' + res.message);
                $('#saveCustomerBtn').prop('disabled', false);
                serialVerified = true;
                toastralert('ตรวจสอบ Serial Number', 'พบข้อมูลการลงทะเบียนเดิม สามารถแก้ไขและบันทึกซ้ำได้', 'N');
            } else {
                setSerialStatus('error', '<i class="fa-solid fa-circle-xmark"></i> ' + res.message);
                $('#saveCustomerBtn').prop('disabled', true);
                serialVerified = false;
                clearCustomerForm();
                toastralert('ตรวจสอบ Serial Number', res.message, 'N');
            }
        },
        error: function () {
            setSerialLoading(false);
            var msg = 'เกิดข้อผิดพลาดในการตรวจสอบ กรุณาลองใหม่';
            setSerialStatus('error', '<i class="fa-solid fa-circle-xmark"></i> ' + msg);
            $('#saveCustomerBtn').prop('disabled', true);
            serialVerified = false;
            toastralert('ตรวจสอบ Serial Number', msg, 'N');
        }
    });
});

// เมื่อพิมพ์ใหม่ให้ reset สถานะและล้างข้อมูลเดิมทันที
$('#cus_serialnumber').on('input', function () {
    resetSerialStatus();
    clearCustomerForm();
});

// ── Helpers ───────────────────────────────────────────────────────────────────
function setSerialLoading(loading) {
    if (loading) {
        $('#serial-spinner').show();
        $('#serial-status').html('');
        $('#saveCustomerBtn').prop('disabled', true);
        serialVerified = false;
    } else {
        $('#serial-spinner').hide();
    }
}

function setSerialStatus(type, html) {
    var color = type === 'ok' ? '#28a745' : '#dc3545';
    $('#serial-status').html('<span style="color:' + color + ';">' + html + '</span>');
}

function resetSerialStatus() {
    serialVerified = false;
    $('#serial-status').html('');
    $('#serial-spinner').hide();
    $('#saveCustomerBtn').prop('disabled', true);
}

// รายชื่อ field ของฟอร์มลูกค้าที่ map กับ key ใน data ที่ API ส่งกลับมา
var CUSTOMER_FORM_FIELDS = {
    name:             '#cus_name',
    phone:            '#cus_phone',
    email:            '#cus_email',
    date_of_purchase: '#cus_datepurchase',
    exp_datewarranty: '#cus_exp_datewarranty',
    purchasechannel:  '#cus_purchasechannel',
    productusetype:   '#cus_productusetype',
    channelawareness: '#cus_channelawareness'
};

// เติมข้อมูลการลงทะเบียนเดิมลงในฟอร์ม แล้วล็อกฟิลด์ไว้เป็น view-only
// (เพราะ Serial Number นี้ลงทะเบียนไปแล้ว ไม่สามารถบันทึกซ้ำได้)
function fillCustomerForm(data) {
    $.each(CUSTOMER_FORM_FIELDS, function (key, selector) {
        var $el = $(selector);
        if ($el.length && data[key] !== undefined) {
            $el.val(data[key]);
        }
    });
    // setCustomerFormReadonly(true);
}

// ล้างข้อมูลในฟอร์มลูกค้า (ไม่รวม Serial Number) แล้วปลดล็อกฟิลด์
function clearCustomerForm() {
    $.each(CUSTOMER_FORM_FIELDS, function (key, selector) {
        $(selector).val('');
    });
    var today = new Date().toISOString().split('T')[0];
    $('#cus_datepurchase').val(today);
    // setCustomerFormReadonly(false);
}

//// เมื่อมีการลงทะเบียนแล้วไม่ให้แก้ไขข้อมูล
// function setCustomerFormReadonly(readonly) {
    // $.each(CUSTOMER_FORM_FIELDS, function (key, selector) {
    //     var $el = $(selector);
    //     if (!$el.length) return;
    //     if ($el.is('select')) {
    //         $el.prop('disabled', readonly);
    //     } else {
    //         $el.prop('readonly', readonly);
    //     }
    // });
// }

// ── Confirm Modal ─────────────────────────────────────────────────────────────
function ConfirmSaveCustomer() {
    if (!serialVerified) {
        toastralert('บันทึกข้อมูล', 'กรุณาตรวจสอบ Serial Number ก่อนบันทึก', 'W');
        $('#cus_serialnumber').focus();
        return;
    }
    var form = document.getElementById('CustomerForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    $('#modal-ConfirmSaveCustomer').modal('show');
}

// ── Submit via AJAX ───────────────────────────────────────────────────────────
function SubmitRegisterCustomer() {
    $('#modal-ConfirmSaveCustomer').modal('hide');

    var serial           = $('#cus_serialnumber').val().trim();
    var name             = $('#cus_name').val().trim();
    var phone            = $('#cus_phone').val().trim();
    var email            = $('#cus_email').val().trim();
    var dateOfPurchase   = $('#cus_datepurchase').val().trim();
    var purchaseChannel  = $('#cus_purchasechannel').val() || '';
    var productUseType   = $('#cus_productusetype').val() || '';
    var channelAwareness = $('#cus_channelawareness').val() || '';
    var csrfToken        = $('meta[name="csrf-token"]').attr('content');

    $('#loading-overlay').show();
    $('body').css('pointer-events', 'none');

    $.ajax({
        url: 'ajax/tk_warrantymanual.ajax.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            csrf_token:   csrfToken,
            functionname: 'register_customer',
            arguments: [
                serial, name, phone, email,
                dateOfPurchase, purchaseChannel, productUseType, channelAwareness
            ]
        }),
        success: function (res) {
            $('#loading-overlay').hide();
            $('body').css('pointer-events', '');

            if (res.success) {
                toastralert('ลงทะเบียน Warranty', res.message || 'ลงทะเบียน Warranty สำเร็จ', 'Y');
                setTimeout(function () {
                    $('#CustomerForm')[0].reset();
                    resetSerialStatus();
                    document.getElementById('cus_datepurchase').value = new Date().toISOString().split('T')[0];
                }, 1500);
            } else {
                toastralert('ลงทะเบียน Warranty', res.message || 'เกิดข้อผิดพลาด', 'N');
            }
        },
        error: function (xhr) {
            $('#loading-overlay').hide();
            $('body').css('pointer-events', '');
            var msg = 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
            try { var r = JSON.parse(xhr.responseText); if (r.message) msg = r.message; } catch (e) {}
            toastralert('ลงทะเบียน Warranty', msg, 'N');
        }
    });
}
