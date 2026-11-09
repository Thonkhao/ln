$(document).ready(function () {
    $('#example').DataTable();
});

var table = $("#example").DataTable({
    // ordering: false,
    order: [],
    // "order": [[ 1, 'desc' ]], // เรียงลำดับตามคอลัมน์ที่ 0 (คอลัมน์แรก) จากน้อยไปมาก (ascending)
    // paging: false,
    // lengthChange: true,
    lengthGroup: [
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

// ShowIconInSelect('GroupIcon');
$('#GroupIcon').select2({
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
function EditGroup(i) {
    var GroupData = $('#editshow-' + i).val();
    var GroupParts = GroupData.split('|');
    document.getElementById('GroupName').disabled = true;
    $('#GroupName').val(GroupParts[0]);
    $('#GroupDisp').val(GroupParts[1]);
    $('#GroupDesc').val(GroupParts[2]);
    $('#GroupSeq').val(GroupParts[3]);
    $('#GroupIcon').val(GroupParts[4]).trigger('change');
    if (GroupParts[5] === 'Y') {
        $('#Active').prop('checked', true);
    } else {
        $('#Active').prop('checked', false);
    }
     if (GroupParts[6] === 'Y') {
        $('#Primary_Group').prop('checked', true);
    } else {
        $('#Primary_Group').prop('checked', false);
    }
    
    $('#action').val('E');
    $('#saveBtn').text('Save');
    $('#Groupedit').val(GroupData);
    $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
    let InsEditGroup = new bootstrap.Modal(document.getElementById('modal-InsEditAuthGroup'));
    InsEditGroup.show()
}

function cleardata() {
    $('#action').val('I');
    document.getElementById('GroupName').disabled = false;
    $('#saveBtn').text('Save');
    $('#GroupName').val('');
    $('#GroupDisp').val('');
    $('#GroupDesc').val('');
    $('#GroupSeq').val('');
    $('#GroupIcon').val(null).trigger('change');
    $('#Active').prop('checked', true);
    $('#Primary_Group').prop('checked', true);
    
    $('#textshow').text('');
}

// function ChangeGroupShow() {
//     const GroupCode = $('#GroupCode').val();
//     document.getElementById('GroupShow').innerHTML = GroupCode;
// }
//==========================================================
function DeleteGroup(i) {
    var GroupData = $('#editshow-' + i).val();
    $('#action').val('D');
    $('#Groupedit').val(GroupData);
    $('#textshow').text('ยืนยันการลบข้อมูล');
    Confirmaction('Confirmaction');
}


function ValidateGroup() {
    const action = $('#action').val();
    if (action === 'I') {
        $('#textshow').text('ยืนยันการบันทึกข้อมูล');
    }
    const GroupName = $("#GroupName").val().trim();
    const GroupDisp = $("#GroupDisp").val().trim();
    const GroupSeq = $("#GroupSeq").val().trim();
    if (GroupName === '' || GroupDisp === '' ||  GroupSeq === '') {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }
    Confirmaction('Confirmaction');
}