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


//ประกาศ Modal object ครั้งเดียว
let Modelaction = new bootstrap.Modal(document.getElementById('Confirmaction'));
function hideModel() {
    Modelaction.hide();
}
function Confirmaction() {
    Modelaction.show();
}


//==========================================================
function EditIcon(i) {
    var IconData = $('#editshow-' + i).val();
    var IconParts = IconData.split('|');

    $('#IconDesc').val(IconParts[1]);
    $('#IconCode').val(IconParts[2]);
    document.getElementById('IconShow').innerHTML = IconParts[2];

    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#Iconedit').val(IconData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditIcon = new bootstrap.Modal(document.getElementById('modal-InsEditAuthIcon'));
    InsEditIcon.show()
}

function cleardata() {
    $('#action').val('I');
    $('#saveBtn').text('Save');
    $('#IconCode').val('');
    $('#IconDesc').val('');
    document.getElementById('IconShow').innerHTML = '';
    $('#textshow').text('');
}

function ChangeIconShow() {
    const IconCode=$('#IconCode').val();
    document.getElementById('IconShow').innerHTML = IconCode;
}
//==========================================================
function DeleteIcon(i) {
    // console.log(i);
    var IconData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#Iconedit').val(IconData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}


function ValidateIcon() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const IconCode = $("#IconCode").val().trim();
    const IconDesc = $("#IconDesc").val().trim();
    if (IconCode === '' || IconDesc === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction();
}