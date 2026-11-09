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

function getMenuDetail(val) {
    console.log(val);
    jQuery.ajax({
        type: "POST",
        url: 'ajax/ticket.ajax.php',
        dataType: 'json',
        data: {
            functionname: 'Get_MenuDetail',
            arguments: [val]
        },
        success: function (res, textstatus) {
            if (!('error' in res)) {
                if (!$.isEmptyObject(res)) {
                    i = 0;
                    $.each(res.result, function (key, value) {
                        // value["Dept_Code"]
                        $('#MenuName').val(value["MenuName"]).trigger('change');
                        $('#MenuDisp').val(value["MenuDisp"]);
                        $('#MenuDesc').val(value["MenuDesc"]);
                        $('#MenuLink').val(value["MenuLink"]);
                        $('#MenuSeq').val(value["seq"]);
                        $('#MenuIcon').val(value["IconDesc"]);
                        document.getElementById('IconShow').innerHTML = value["IconCode"];
                        // i.IconCode,i.IconDesc
                        $('#Active').prop('checked', true);
                        if (value["Is_Active"] === 'Y') {
                            $('#Active').prop('checked', true);
                        } else {
                            $('#Active').prop('checked', false);
                        }
                    });
                }
            } else {
                console.log(res.error);
            }
        }
    });
}


function cleardata() {
    $('#action').val('I');
    $('#saveBtn').text('Save');
    $('#MenuName').val(null).trigger('change');
    $('#MenuDisp').val('');
    $('#MenuDesc').val('');
    $('#MenuLink').val('');
    $('#MenuSeq').val('');
    $('#MenuIcon').val('');
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
    if (MenuName === '') {
        alert('กรุณาเลือก Menu ที่ต้องการเพิ่ม');
        return;
    }
    Confirmaction('Confirmaction');
}