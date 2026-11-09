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

$(document).ready(function () {
    $('#ReasonType').select2({
        theme: 'bootstrap4',
        tags: true,
        placeholder: "-- Please select --",
    });


    // ป้องกันการพิมพ์เกินในช่อง input ของ select2
    $(document).on('input', '.select2-search__field', function () {
        if ($(this).val().length > 20) {
            $(this).val($(this).val().substring(0, 20));
        }
    });
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
function EditReason(i) {
    var reasonData = $('#editshow-' + i).val();
    var reasonParts = reasonData.split('|');
    $('#ReasonType').val(reasonParts[0]).trigger('change');
    document.getElementById('ReasonType').disabled = true;
    $('#ReasonCode').val(reasonParts[1]);
    document.getElementById('ReasonCode').disabled = true;
    $('#ReasonDesc').val(reasonParts[2]);
    // console.log(reasonParts[3]);
    if (reasonParts[3] === 'Y') {
        $('#Active').prop('checked', true);
    } else {
        $('#Active').prop('checked', false);
    }
    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#reasonedit').val(reasonData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditReason = new bootstrap.Modal(document.getElementById('modal-InsEditReason'));
    InsEditReason.show()
}

// const resetBtn = document.getElementById('resetBtn');
// resetBtn.addEventListener('click', (event) => {
//     event.preventDefault(); // กัน Refresh หน้าจอ
 function cleardata(){
    $('#action').val('I');
    $('#saveBtn').text('Save');
    $('#ReasonType').val(null).trigger('change'); // รีเซ็ต select2
    document.getElementById('ReasonType').disabled = false;
    $('#ReasonCode').val('');
    document.getElementById('ReasonCode').disabled = false;
    $('#ReasonDesc').val('');
    $('#Active').prop('checked', true); // ตั้งค่าเริ่มต้นเป็น "Yes"
    $('#textshow').text('');
}
    // $('#acreasonedittion').val('');
// });
//==========================================================
function DeleteReason(i) {
    // console.log(i);
    var reasonData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#reasonedit').val(reasonData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}


function ValidateReason() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const ReasonType = $("#ReasonType").val().trim();
    const ReasonCode = $("#ReasonCode").val().trim();
    const ReasonDesc = $("#ReasonDesc").val().trim();
    if (ReasonType === '' || ReasonCode === '' || ReasonDesc === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction();
}