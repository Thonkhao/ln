const profile_image = document.getElementById('profile_image');
const preview = document.getElementById('preview');
const ShowUserImg = document.getElementById('ShowUserImg');
const ShowShortName = document.getElementById('ShowShortName');


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
let ModelInsEditUserEmail = new bootstrap.Modal(document.getElementById('modal-InsEditUserEmail'));

function hideModel() {
    Modelaction.hide();
}

function Confirmaction() {
    Modelaction.show();
}

//==========================================================
function EditUserEmail(val) {
    profile_image.value = '';
    preview.src = '';
    preview.classList.add('hidden');
    $('#changeImage').val('N');
    jQuery.ajax({
        type: "POST",
        url: 'ajax/ticket.ajax.php',
        dataType: 'json',
        data: {
            functionname: 'Get_UserEmailDtl',
            arguments: [val]
        },
        success: function (res, textstatus) {
            if (!('error' in res)) {
                if (!$.isEmptyObject(res)) {
                    i = 0;
                    $.each(res.result, function (key, value) {
                        DeptChange(value["Dept_Code"], value["Org_Seq"]);
                        $('#TK_UserEmail').val(value["User_Email"]);
                        $('#TK_UserID').val(value["UserID"]);
                        $('#UserEmail_Desc').val(value["F_Name"]);
                        $('#User_Shortname').val(value["short_name"]);
                        $('#UserDept').val(value["Dept_Code"]).trigger('change');
                        if (value["Is_Active"] === 'Y') {
                            $('#Active').prop('checked', true);
                        } else {
                            $('#Active').prop('checked', false);
                        }
                        $('#action').val('E');
                        $('#saveBtn').text('Save');
                        const short_name = value["short_name"];
                        const Color_Code = value["Color_Code"];
                        const Image_Profile = value["Image_Profile"];
                        const CircelShortname = `<div class='circle-container image-preview ' style='background-color: rgba( ${Color_Code},0.2); color:rgba(${Color_Code}, 1) '>
                                                    ${short_name}
                                                 </div>`;
                        ShowShortName.innerHTML = CircelShortname;
                        if (Image_Profile && Image_Profile !== '') {
                            preview.classList.add('hidden');
                            const html_string = `<img class='image-preview' src="data:image/jpg;base64,${Image_Profile}"  alt="" width="100%" class="card-img"> `;
                            ShowUserImg.innerHTML = html_string;
                            ShowUserImg.style.display = '';
                            ShowShortName.style.display = 'none';
                        } else {
                            ShowShortName.style.display = '';
                            ShowUserImg.style.display = 'none';
                        }
                        $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
                        ModelInsEditUserEmail.show();
                    });
                }
            } else {
                console.log(res.error);
            }
        }
    });
}

// function ValidateUserEmail() {
//     // const UserEmail_Desc = $("#UserEmail_Desc ").val().trim();
//     // if (UserEmail_Desc === '') {
//     //     alert('กรุณากรอกข้อมูลช่อง Name');
//     //     return;
//     // }
//     Confirmaction();
// }

function DeptChange(val, Org_Seq) {
    if (Org_Seq !== '999999') {
        $('#TKUSer_Org').val('').trigger('change');
        // $('#ReasonType').empty();
        jQuery.ajax({
            type: "POST",
            url: 'ajax/ticket.ajax.php',
            dataType: 'json',
            data: {
                functionname: 'Get_Organization',
                arguments: [val]
            },
            success: function (res, textstatus) {
                if (!('error' in res)) {
                    if (!$.isEmptyObject(res)) {
                        var options = '';
                        $.each(res.result, function (key, value) {
                            options += '<option value="' + value['Org_Seq'] + '">' + value['Org_DescEh'] + '</option>';
                        });
                    }
                    $('#TKUSer_Org').append(options);
                    $('#TKUSer_Org').val(Org_Seq).trigger('change');
                } else {
                    console.log(res.error);
                }
            }
        });
    }
}

// Preview รูปใหม่เมื่อเลือกไฟล์
if (profile_image) {
    profile_image.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            // ตรวจสอบประเภทไฟล์
            const fileType = file.type;
            const validImageTypes = ['image/jpeg', 'image/png'];

            if (validImageTypes.includes(fileType)) {
                // ถ้าเป็น JPG หรือ PNG ให้ดำเนินการต่อ
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                $('#changeImage').val('Y');
                ShowUserImg.style.display = 'none';
                ShowShortName.style.display = 'none';
            } else {
                // ถ้าไม่ใช่ประเภทที่ถูกต้อง ให้แจ้งเตือนและเคลียร์ input
                alert('กรุณาเลือกไฟล์รูปภาพประเภท JPG หรือ PNG เท่านั้น');
                profile_image.value = '';
                preview.src = '';
                preview.classList.add('hidden');
            }
        }
    });
}