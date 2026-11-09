const csrfToken = document.querySelector('meta[name="csrf-token"]') ?.content || window.csrf_token || '';

const fmtDate_dmy = (dateStr) => {
    if (!dateStr) return '';
    const [y, m, d] = dateStr.split('-');
    return `${d}-${m}-${y}`;
};

const fmtQty = (v) =>
    v != null && v !== '' ?
    Number(v).toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }) :
    '';

var tableDelivery;
var reportDatePicker;

$(document).ready(function () {
    reportDatePicker = flatpickr('#ReportDate', {
        dateFormat:  'Y-m-d',       // value ที่ส่งไป server (YYYY-MM-DD)
        altInput:    true,           // แสดง input แยกต่างหาก
        altFormat:   'd/m/Y',        // display format DD/MM/YYYY ทุกเครื่อง
        defaultDate: 'today',
        allowInput:  true,
        locale: { firstDayOfWeek: 1 }
    });

    tableDelivery = $('#tableDelivery').DataTable({
        order: [],
        lengthMenu: [
            [15, 25, 50, -1],
            [15, 25, 50, 'All']
        ],
        searching: true,
        autoWidth: false,
        scrollX: true,
 
        columnDefs: [
            { orderable: false, targets: [0, 8] },
            { width: '3%',  targets: [0] },
            { width: '10%', targets: [1, 2, 3, 4, 5, 6, 7] },
            { width: '1%',  targets: [8] },
            {
                targets: [0, 1],
                createdCell: (td) => { td.style.textAlign = 'center'; }
            },
            {
                targets: [2, 3, 4, 5, 6, 7],
                createdCell: (td) => { td.style.textAlign = 'right'; }
            }
        ]
    });

    loadDeliveryMonth();
});

function loadDeliveryMonth() {
    const payload = {
        functionname: 'getDeliveryMonth',
        arguments: {},
        csrf_token: csrfToken
    };

    fetch('ajax/report_delivery.ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.error('loadDeliveryMonth error:', data.error ?? data.result);
                return;
            }

            tableDelivery.clear();

            if (Array.isArray(data.result) && data.result.length > 0) {
                data.result.forEach((row, i) => {
                    const shipDate = row.ShipDate ? row.ShipDate.substring(0, 10) : '';
                    const editBtn = `<button type="button" class="btn btn-sm btn-outline-secondary"
                        onclick="editDeliveryRow(this)"
                        data-shipdate="${shipDate}"
                        data-lazada="${row.Lazada_Qty ?? 0}"
                        data-shopee="${row.Shopee_Qty ?? 0}"
                        data-tiktok="${row.Tiktok_Qty ?? 0}"
                        data-line="${row.Line_Qty ?? 0}"
                        data-web="${row.Web_Qty ?? 0}"
                        data-other="${row.Other_Qty ?? 0}">
                        <i class="bi bi-pencil"></i>
                    </button>`;

                    tableDelivery.row.add([
                        // '',
                        i + 1,
                        fmtDate_dmy(shipDate),
                        fmtQty(row.Lazada_Qty),
                        fmtQty(row.Shopee_Qty),
                        fmtQty(row.Tiktok_Qty),
                        fmtQty(row.Line_Qty),
                        fmtQty(row.Web_Qty),
                        fmtQty(row.Other_Qty),
                        editBtn
                    ]);
                });
            }

            tableDelivery.draw();
        })
        .catch(err => console.error('fetch error:', err));
}

function editDeliveryRow(btn) {
    reportDatePicker.setDate(btn.dataset.shipdate);
    reportDatePicker.set('clickOpens', false);   // ล็อควันที่ในโหมด edit

    document.getElementById('Lazada_Qty').value = btn.dataset.lazada;
    document.getElementById('Shopee_Qty').value = btn.dataset.shopee;
    document.getElementById('Tiktok_Qty').value = btn.dataset.tiktok;
    document.getElementById('Line_Qty').value   = btn.dataset.line;
    document.getElementById('Web_Qty').value    = btn.dataset.web;
    document.getElementById('Other_Qty').value  = btn.dataset.other;

    document.getElementById('deliveryMode').value  = 'edit';
    document.getElementById('editShipDate').value  = btn.dataset.shipdate;

    // ล็อก altInput (display input) ไม่ให้แก้ได้
    const altInput = document.querySelector('#ReportDate + input');
    if (altInput) altInput.readOnly = true;

    document.getElementById('btn-save-label').textContent = 'Update';
    document.getElementById('btn-save-icon').className    = 'bi bi-pencil-square';
    document.getElementById('btn-cancel-edit').style.display = '';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function saveDeliveryOnline() {
    const mode = document.getElementById('deliveryMode').value;
    const ReportDate = document.getElementById('ReportDate').value.trim();
    const Lazada_Qty = document.getElementById('Lazada_Qty').value.trim();
    const Shopee_Qty = document.getElementById('Shopee_Qty').value.trim();
    const Tiktok_Qty = document.getElementById('Tiktok_Qty').value.trim();
    const Line_Qty = document.getElementById('Line_Qty').value.trim();
    const Web_Qty = document.getElementById('Web_Qty').value.trim();
    const Other_Qty = document.getElementById('Other_Qty').value.trim();

    let valid = true;

    if (!ReportDate) {
        showerror('ReportDate', 'ReportDateError', 'This field is required', 'A');
        valid = false;
    } else {
        showerror('ReportDate', 'ReportDateError', '', 'R');
    }

    const qtyFields = [
        ['Lazada_Qty', Lazada_Qty],
        ['Shopee_Qty', Shopee_Qty],
        ['Tiktok_Qty', Tiktok_Qty],
        ['Line_Qty', Line_Qty],
        ['Web_Qty', Web_Qty],
        ['Other_Qty', Other_Qty],
    ];

    for (const [field, val] of qtyFields) {
        if (val === '') {
            showerror(field, field + 'Error', 'This field is required', 'A');
            valid = false;
        } else {
            showerror(field, field + 'Error', '', 'R');
        }
    }

    if (!valid) return;

    const functionname = mode === 'edit' ? 'update_DeliveryOnline' : 'procress_DeliveryOnline';
    const payload = {
        functionname,
        arguments: {
            ShipDate: ReportDate,
            Lazada_Qty: parseInt(Lazada_Qty),
            Shopee_Qty: parseInt(Shopee_Qty),
            Tiktok_Qty: parseInt(Tiktok_Qty),
            Line_Qty: parseInt(Line_Qty),
            Web_Qty: parseInt(Web_Qty),
            Other_Qty: parseInt(Other_Qty)
        },
        csrf_token: csrfToken
    };

    const saveBtn = document.getElementById('btn-save-delivery');
    if (saveBtn) saveBtn.disabled = true;

    fetch('ajax/report_delivery.ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const actionLabel = mode === 'edit' ? 'แก้ไข' : 'บันทึก';
                toastralert('Delivery Online', actionLabel + 'สำเร็จ', 'Y');
                clearDeliveryForm();
                loadDeliveryMonth();
            } else {
                toastralert('Delivery Online', 'บันทึกไม่สำเร็จ: ' + (data.error ?? data.result), 'N');
            }
        })
        .catch(err => {
            console.error('save error:', err);
            toastralert('Delivery Online', 'เกิดข้อผิดพลาดในการบันทึก', 'N');
        })
        .finally(() => {
            if (saveBtn) saveBtn.disabled = false;
        });
}

function clearDeliveryForm() {
    reportDatePicker.setDate(new Date());        // reset เป็นวันนี้
    reportDatePicker.set('clickOpens', true);    // ปลดล็อก

    const altInput = document.querySelector('#ReportDate + input');
    if (altInput) altInput.readOnly = false;

    document.getElementById('deliveryMode').value = 'insert';
    document.getElementById('editShipDate').value = '';

    ['Lazada_Qty', 'Shopee_Qty', 'Tiktok_Qty', 'Line_Qty', 'Web_Qty', 'Other_Qty'].forEach(id => {
        document.getElementById(id).value = '';
        showerror(id, id + 'Error', '', 'R');
    });
    showerror('ReportDate', 'ReportDateError', '', 'R');

    document.getElementById('btn-save-label').textContent = 'Save';
    document.getElementById('btn-save-icon').className    = 'bi bi-save';
    document.getElementById('btn-cancel-edit').style.display = 'none';
}