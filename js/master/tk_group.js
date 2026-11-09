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


//=============================select แบบ พิมพ์ได้==========================================================



//ประกาศ Modal object ครั้งเดียว
let Modelaction = new bootstrap.Modal(document.getElementById('Confirmaction'));

function hideModel() {
    Modelaction.hide();
}

function Confirmaction() {
    Modelaction.show();
}


//==========================================================
function EditGroup(i) {
    var GroupData = $('#editshow-' + i).val();
    var GroupParts = GroupData.split('|');
    $('#TK_Group').val(GroupParts[0])
    document.getElementById('TK_Group').disabled = true;
    $('#Group_Desc').val(GroupParts[1]);
    $('#Prefix').val(GroupParts[2]);
    if (GroupParts[3] === 'Y') {
        $('#Active').prop('checked', true);
    } else {
        $('#Active').prop('checked', false);
    }
    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#groupedit').val(GroupData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditGroup = new bootstrap.Modal(document.getElementById('modal-InsEditGroup'));
    InsEditGroup.show()
}


function cleardata() {
    $('#action').val('I');
    $('#TK_Group').val('');
    document.getElementById('TK_Group').disabled = false;
    $('#Group_Desc').val('');
    $('#Prefix').val('');
    $('#Active').prop('checked', true);
    $('#textshow').text('');
}
// });
//==========================================================
function DeletetGroup(i) {
    // console.log(i);
    var GroupData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#groupedit').val(GroupData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}


function ValidateGroup() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const TK_Group = $("#TK_Group").val().trim();
    const Group_Desc = $("#Group_Desc").val().trim();
    const Prefix = $("#Prefix").val().trim();

    if (TK_Group === '' || Group_Desc === '' || Prefix === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction();
}