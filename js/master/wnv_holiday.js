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

function cleardata() {
    $('#action').val('I');
    $('#saveBtn').text('Save');
    $('#Hol_Date').val('');
    $('#Hol_Desc').val('');
}

//==========================================================
function DeleteHol_D(i) {
    // console.log(i);
    var IconData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#Hol_Date_edit').val(IconData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}


function ValidateHoliday() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const Hol_Date = $("#Hol_Date").val().trim();
    const Hol_Desc = $("#Hol_Desc").val().trim();
    if (Hol_Date === '' || Hol_Desc === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction();
}