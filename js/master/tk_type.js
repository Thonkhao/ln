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
function EditType(i) {
    var TypeData = $('#editshow-' + i).val();
    // console.log(TypeData);
    var typeParts = TypeData.split('|');
    $('#Type_num').val(typeParts[0]);
    $('#Group').val(typeParts[1]).trigger('change');
    $('#Type_DescTH').val(typeParts[2]);
    $('#Type_DescEN').val(typeParts[3]);
    $('#Seq').val(typeParts[4]);

    if (typeParts[5] === 'Y') {
        $('#Approve_Req').prop('checked', true);
    } else {
        $('#Approve_Req').prop('checked', false);
    }
    if (typeParts[6] === 'Y') {
        $('#Active').prop('checked', true);
    } else {
        $('#Active').prop('checked', false);
    }
    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#typeedit').val(TypeData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditType = new bootstrap.Modal(document.getElementById('modal-InsEditType'));
    InsEditType.show()
}

// const resetBtn = document.getElementById('resetBtn');
// resetBtn.addEventListener('click', (event) => {
//     event.preventDefault(); // กัน Refresh หน้าจอ
    function cleardata(){
    $('#action').val('I');
    $('#Type_num').val('');
    $('#Group').val(null).trigger('change');
    $('#Type_DescTH').val('');
    $('#Type_DescEN').val('');
    $('#Seq').val('');

    $('#Approve_Req').prop('checked', true);
    $('#Active').prop('checked', true);
    $('#textshow').text('');
    }
// });
//==========================================================
function DeletetType(i) {
    // console.log(i);
    var TypeData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#typeedit').val(TypeData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction();
}


function ValidateType() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const Type_num = $("#Type_num").val().trim();
    const Group = $("#Group").val().trim();
    const Type_DescTH = $("#Type_DescTH").val().trim();
    const Seq = $("#Seq").val().trim();

    if (Type_num === '' || Group === '' || Type_DescTH === '' || Seq === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction();
}