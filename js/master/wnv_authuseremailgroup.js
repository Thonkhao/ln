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

function getGroupDetail(val) {
    console.log(val);
    jQuery.ajax({
        type: "POST",
        url: 'ajax/ticket.ajax.php',
        dataType: 'json',
        data: {
            functionname: 'Get_GroupDetail',
            arguments: [val]
        },
        success: function (res, textstatus) {
            if (!('error' in res)) {
                if (!$.isEmptyObject(res)) {
                    i = 0;
                    $.each(res.result, function (key, value) {
                        // value["Dept_Code"]
                        $('#GroupName').val(value["GroupName"]).trigger('change');
                        $('#GroupDisp').val(value["GroupDisp"]);
                        $('#GroupDesc').val(value["GroupDesc"]);
                        $('#GroupSeq').val(value["seq"]);
                        $('#GroupIcon').val(value["IconDesc"]);
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
    $('#GroupName').val(null).trigger('change');
    $('#GroupDisp').val('');
    $('#GroupDesc').val('');
    $('#GroupSeq').val('');
    $('#GroupIcon').val('');
    $('#Active').prop('checked', true);
    $('#textshow').text('');
}
//==========================================================
function DeleteGroup(i) {
    var GroupData = $('#editshow-' + i).val();
    $('#Groupedit').val(GroupData);
    $('#action').val('D');
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction('Confirmaction');
}


function ValidateGroup() {

    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const GroupName = $("#GroupName").val().trim();
    if (GroupName === '') {
        alert('กรุณาเลือก Group ที่ต้องการเพิ่ม');
        return;
    }
    Confirmaction('Confirmaction');
}