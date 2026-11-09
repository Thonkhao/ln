$(document).ready(function () {
    $('#example').DataTable();
});

var table = $("#example").DataTable({
    // ordering: false,
    order: [],
    // "order": [[ 1, 'desc' ]], // เรียงลำดับตามคอลัมน์ที่ 0 (คอลัมน์แรก) จากน้อยไปมาก (ascending)
    // paging: false,
    // lengthChange: true,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
    ],
    searching: true,
    // info: false,
    autoWidth: true,
    scrollX: true,
    //  orderCellsTop: false,
});


function hideModel(ids) {
    let Modelaction = new bootstrap.Modal(document.getElementById(ids));
    Modelaction.hide();
}

function Confirmaction(ids) {
    let Modelaction = new bootstrap.Modal(document.getElementById(ids));
    Modelaction.show();
}

$('#MenuIcon').select2({
    theme: 'bootstrap4',
    placeholder: "เลือก Icon",
    templateResult: function (data) {
        if (!data.id) return data.text;
        var iconHtml = $(data.element).data('icon');

        // ถ้าเก็บเป็น class เช่น fa-regular fa-file-pen → ครอบ <i>
        if (iconHtml && !iconHtml.includes('<i')) {
            iconHtml = '<i class="' + iconHtml + '" style="min-width:1.75rem;display:inline-block;"></i>';
        }
        return $('<span>' + data.text + ' ' + (iconHtml || '') + '</span>');
    },
    templateSelection: function (data) {
        if (!data.id) return data.text;
        var iconHtml = $(data.element).data('icon');
        if (iconHtml && !iconHtml.includes('<i')) {
            iconHtml = '<i class="' + iconHtml + '" style="min-width:1.75rem;display:inline-block;"></i>';
        }
        return $('<span>' + data.text + ' ' + (iconHtml || '') + '</span>');
    }
});
//==========================================================
function EditMenu(i) {
    var MenuData = $('#editshow-' + i).val();
    var MenuParts = MenuData.split('|');
    document.getElementById('MenuName').disabled = true;
    $('#MenuName').val(MenuParts[0]);
    $('#MenuDisp').val(MenuParts[1]);
    $('#MenuDesc').val(MenuParts[2]);
    $('#MenuLink').val(MenuParts[3]);
    $('#MenuSeq').val(MenuParts[4]);
    $('#MenuIcon').val(MenuParts[5]).trigger('change');
    if (MenuParts[6] === 'Y') {
        $('#Active').prop('checked', true);
    } else {
        $('#Active').prop('checked', false);
    }
    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#Menuedit').val(MenuData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditMenu = new bootstrap.Modal(document.getElementById('modal-InsEditAuthMenu'));
    InsEditMenu.show()
}

function cleardata() {
    $('#action').val('I');
    document.getElementById('MenuName').disabled = false;
    $('#saveBtn').text('Save');
    $('#MenuName').val('');
    $('#MenuDisp').val('');
    $('#MenuDesc').val('');
    $('#MenuLink').val('');
    $('#MenuSeq').val('');
    $('#MenuIcon').val(null).trigger('change');
    $('#Active').prop('checked', true);
    $('#textshow').text('');
}
//==========================================================
function DeleteMenu(i) {
    var MenuData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#Menuedit').val(MenuData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction('Confirmaction');
}


function ValidateMenu() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const MenuName = $("#MenuName").val().trim();
    const MenuDisp = $("#MenuDisp").val().trim();
    const MenuLink = $("#MenuLink").val().trim();
    const MenuSeq = $("#MenuSeq").val().trim();
    if (MenuName === '' || MenuDisp === '' || MenuLink === '' || MenuSeq === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction('Confirmaction');
}