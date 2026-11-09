// // กำหนดค่าคงที่สำหรับ CSS Selector ของ sidebar เพื่อให้โค้ดจัดการได้ง่ายขึ้น
// const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
// // Object ที่เก็บค่าการตั้งค่าเริ่มต้น (Default) สำหรับ Scrollbar
// const Default = {
//     // กำหนดธีมของ Scrollbar
//     scrollbarTheme: 'os-theme-light',
//     // กำหนดให้ Scrollbar ซ่อนอัตโนมัติเมื่อเมาส์ออกจากพื้นที่
//     scrollbarAutoHide: 'leave',
//     // อนุญาตให้คลิกที่ Scrollbar เพื่อเลื่อนหน้าได้
//     scrollbarClickScroll: true,
// };

// // สั่งให้โค้ดทำงานหลังจากที่หน้าเว็บโหลด HTML เสร็จสมบูรณ์แล้ว
// document.addEventListener('DOMContentLoaded', function () {
//     // ค้นหา Element ในหน้าเว็บที่มีคลาสตรงกับ SELECTOR_SIDEBAR_WRAPPER
//     const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
//     // ตรวจสอบว่าพบ Sidebar และ Library OverlayScrollbars ได้ถูกโหลดเข้ามาแล้ว
//     if (sidebarWrapper && typeof OverlayScrollbarsGlobal ?.OverlayScrollbars !== 'undefined') {
//         // เริ่มต้นใช้งาน OverlayScrollbars กับ Sidebar โดยใช้การตั้งค่าจาก Default
//         OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
//             scrollbars: {
//                 theme: Default.scrollbarTheme,
//                 autoHide: Default.scrollbarAutoHide,
//                 clickScroll: Default.scrollbarClickScroll,
//             },
//         });
//     }

//     // ดึงค่า query string ของ URL ปัจจุบัน (เช่น "?r=it_dtl")
//     const currentPath = window.location.search;
//     // ค้นหาลิงก์ทั้งหมดที่มีคลาส 'nav-link'
//     const navLinks = document.querySelectorAll('.nav-link');
//     // วนลูปทำงานกับลิงก์แต่ละอันที่หามาได้
//     navLinks.forEach(link => {
//         // ดึงค่า href ของลิงก์ปัจจุบัน
//         const linkHref = link.getAttribute('href');
//         // nav-item menu-open
//         // --- โค้ดสำหรับ Debug เพื่อแสดงค่าใน Console ---
//         //         console.log('----------------------');
//         //         console.log('linkHref--'+linkHref);
//         //         console.log('currentPath--'+currentPath);
//         //         console.log('link--++'+link);
//         // ---------------------------------------------------------

//         // ตรวจสอบว่าลิงก์คือ '?r=user_dashboard' และ URL ปัจจุบันเริ่มต้นด้วย '?r=user_dtl'
//         if (linkHref === '?r=user_dashboard' && currentPath.startsWith('?r=user_dtl')) {
//             link.classList.add('active'); // เพิ่มคลาส 'active' เพื่อไฮไลท์ลิงก์
//             // ตรวจสอบว่าลิงก์คือ '?r=it_dashboard' และ URL ปัจจุบันเริ่มต้นด้วย '?r=it_dtl'
//         } else if (linkHref === '?r=it_dashboard' && currentPath.startsWith('?r=it_dtl')) {
//             link.classList.add('active'); // เพิ่มคลาส 'active' เพื่อไฮไลท์ลิงก์
//         }
//     });
// });




// // Click Menu Add Class Active And Remove Active Befor
// document.addEventListener("DOMContentLoaded", function () {
//     // เลือกทุกลิงก์ใน sidebar
//     let menuLinks = document.querySelectorAll(".sidebar-menu .nav-link");

//     // วนลูปเพื่อตรวจจับการคลิก
//     menuLinks.forEach(link => {
//         link.addEventListener("click", function () {
//             // ลบ class "active" ออกจากทุกเมนู
//             menuLinks.forEach(item => item.classList.remove("active"));

//             // เพิ่ม class "active" ให้กับเมนูที่ถูกคลิก
//             this.classList.add("active");

//             // ตรวจสอบว่ามี parent menu หรือไม่
//             let parentMenu = this.closest(".nav-treeview");
//             if (parentMenu) {
//                 let parentLink = parentMenu.previousElementSibling;
//                 if (parentLink) {
//                     parentLink.classList.add("active");
//                 }
//             }
//         });
//     });

//     // ทำให้เมนู active ตาม URL ที่เปิดอยู่
//     let currentUrl = window.location.href;
//     menuLinks.forEach(link => {
//         if (link.href === currentUrl) {
//             link.classList.add("active");

//             // เปิดเมนู parent หากอยู่ใน sub-menu
//             let parentMenu = link.closest(".nav-treeview");
//             if (parentMenu) {
//                 let parentLink = parentMenu.previousElementSibling;
//                 if (parentLink) {
//                     parentLink.classList.add("active");
//                 }
//             }
//         }
//     });
// });




//Multi Select
$('.select2bs4').select2({
    theme: 'bootstrap4',
    placeholder: "-- Please select --",

})
//Multi Select Max 1
$('.select2bs4-m1').select2({
    theme: 'bootstrap4',
    placeholder: "-- Please select --",
    maximumSelectionLength: 1 // กำหนดจำนวนสูงสุดที่เลือกได้เป็น 3
})

//เช็คค่าว่าง
function isNotEmpty(value) {
    return value !== undefined && value !== null && String(value).trim() !== "";
}

function checkWeekend(date) {
    const dayOfWeek = date.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
    return dayOfWeek === 0 || dayOfWeek === 6;
}

function showerror(idC, idS, text, action) {
    // console.log(idS);
    if (action === 'A') {
        document.getElementById(idC).classList.add("error");
        document.getElementById(idS).innerText = text;
    }
    if (action === 'R') {
        document.getElementById(idC).classList.remove("error");
        document.getElementById(idS).innerText = text;
    }
}

function isDotNumber(event) {
    const charCode = event.which || event.keyCode;
    const char = String.fromCharCode(charCode);
    // อนุญาตเฉพาะตัวเลขและจุด
    if (/[0-9.]/.test(char)) {
        // ถ้าเป็นจุด ให้เช็คว่ามีจุดอยู่แล้วหรือยัง
        if (char === '.') {
            const input = event.target;
            if (input.value.includes('.')) {
                event.preventDefault();
                return false;
            }
        }
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}

function isNumber(event) {
    const charCode = event.which || event.keyCode;
    const char = String.fromCharCode(charCode);
    if (/[0-9,-]/.test(char)) {
        return true; // อนุญาตให้ป้อนตัวเลขและเครื่องหมายจุลภาค
    } else {
        event.preventDefault(); // ป้องกันการป้อนอักขระอื่นๆ
        return false;
    }
}

function OnlyInt(event) {
    const charCode = event.which || event.keyCode;
    const char = String.fromCharCode(charCode);
    if (/[0-9]/.test(char)) {
        return true; // อนุญาตให้ป้อนตัวเลขและเครื่องหมายจุลภาค
    } else {
        event.preventDefault(); // ป้องกันการป้อนอักขระอื่นๆ
        return false;
    }
}

function toastralert(title, msg, stat) {
    if (stat == 'Y') {
        var showalert = 'success';
    } else if (stat == 'I') {
        var showalert = 'info';
    } else if (stat == 'W') {
        var showalert = 'warning';
    } else {
        var showalert = 'error';
    }

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[showalert](msg, title)
}

function SameValue(from, to) {
    var from = $('#' + from).val();
    $('#' + to).val(from).trigger('change');
}

// Debounce utility to prevent rapid duplicate actions
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}



function handleSubmitViaClick(formSelector, loadingOverlayId = 'loading-overlay') {
    const form = document.querySelector(formSelector);
    if (!form) return;

    // Disable ปุ่ม submit
    const submitButton = form.querySelector("button[type='submit']");
    if (submitButton) {
        submitButton.disabled = true;
    }

    const buttonButton = form.querySelector("button[type='button']");
    if (buttonButton) {
        buttonButton.disabled = true;
    }

    // ป้องกันการ refresh
    window.addEventListener("keydown", function (e) {
        if (e.key === "F5" || (e.ctrlKey && e.key.toLowerCase() === "r")) {
            e.preventDefault();
            alert("กำลังดำเนินการ กรุณารอ...");
        }
    });

    // ป้องกันปุ่ม back
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
        alert("ไม่สามารถย้อนกลับได้ระหว่างดำเนินการ");
    };
    // แสดง overlay
    const loadingOverlay = document.getElementById(loadingOverlayId);
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
    document.body.style.pointerEvents = 'none';
    form.submit();
}
// เริ่มต้นการตั้งค่า Summernote สำหรับ element ที่มี ID เป็น 'ChatDesc'
$('#ChatDesc').summernote({
    toolbar: [
        // ตั้งค่า toolbar ที่จะแสดง
        ['style', ['bold', 'underline', 'clear']], // กลุ่มปุ่มสำหรับสไตล์ข้อความ: ตัวหนา, ขีดเส้นใต้, ลบสไตล์
        ['fontsize', ['fontsize']], // กลุ่มปุ่มสำหรับขนาดตัวอักษร
        ['color', ['color']], // กลุ่มปุ่มสำหรับสีตัวอักษร
    ],
    disableResizeEditor: true, // ปิดการใช้งานการปรับขนาดความสูงของ editor ด้วยเมาส์
    minHeight: null, // กำหนดความสูงขั้นต่ำของ editor (null คือไม่กำหนด)
    height: null, // กำหนดความสูงเริ่มต้นของ editor (null คือไม่กำหนด)
    // focus: true, // ตัวเลือกนี้ถูก comment ไว้: จะทำให้ cursor ไปอยู่ที่ editor ทันทีที่หน้าโหลดเสร็จ
    placeholder: 'Leave a comment', // แสดงข้อความตัวอย่างใน editor เมื่อยังไม่มีข้อความ
    // airMode: true, // ตัวเลือกนี้ถูก comment ไว้: จะเปลี่ยน editor เป็นโหมด air mode ที่ toolbar จะปรากฏเมื่อเลือกข้อความเท่านั้น
    callbacks: { // ส่วนที่รวมฟังก์ชันที่จะทำงานเมื่อเกิดเหตุการณ์ต่างๆ
        onInit: function () {
            // เมื่อ Summernote เริ่มทำงาน:
            // ดักจับเหตุการณ์การคลิกบนแท็ก <a> (ลิงก์) ภายใน editor
            $(this).on('click', 'a', function (e) {
                e.preventDefault(); // ป้องกันการทำงานเริ่มต้นของการคลิกลิงก์ (เช่น การเปิดหน้าใหม่)
                $('#ChatDesc').summernote('disable'); // ปิดการใช้งาน editor ชั่วคราว เพื่อไม่ให้แก้ไขลิงก์ได้
            });
        },
        onBlur: function () {
            // เมื่อผู้ใช้คลิกออกนอก editor
            $('#ChatDesc').summernote('enable'); // เปิดใช้งาน editor กลับมาเป็นปกติ
        },
        onDrop: function (e) {
            e.preventDefault(); // ป้องกันการวางไฟล์ (เช่น รูปภาพ) ด้วยการลากแล้วปล่อย
        },
        onImageUpload: function (files) {
            // เมื่อมีการพยายามอัปโหลดรูปภาพ
            return false; // ป้องกันการอัปโหลดรูปภาพทั้งหมด
        },
        onPaste: function (e) {
            // เมื่อผู้ใช้ทำการวางข้อมูล (paste)
            var clipboardData = (e.originalEvent || e).clipboardData; // ดึงข้อมูลจาก clipboard
            var pastedData = clipboardData.getData('text'); // ดึงข้อมูลในรูปแบบข้อความ

            // ตรวจสอบว่าข้อมูลที่วางขึ้นต้นด้วย 'https://' หรือ 'http://' หรือไม่
            if (pastedData.startsWith('https://') || pastedData.startsWith('http://')) {
                e.preventDefault(); // ป้องกันการวางข้อมูลแบบปกติของ Summernote
                // สร้าง element <a> (link) ใหม่
                var link = $('<a></a>').attr('href', pastedData).text(pastedData);
                // แทรก element <a> ที่สร้างขึ้นใหม่ลงใน editor
                $('#ChatDesc').summernote('insertNode', link[0]);
            }
        }
    }
});

// ดักจับ event 'dragover', 'dragenter', และ 'drop' ทั่วทั้งเอกสาร (document)
$('#ChatDesc').on('drop dragover', function (e) {
    e.preventDefault(); // ป้องกันการทำงานเริ่มต้นของ event เหล่านี้
    e.stopPropagation(); // หยุดการแพร่กระจายของ event ไปยัง element อื่น
});
//----------------------------------------------------------------------------------------------------
// กำหนดฟังก์ชันชื่อ ShowSummerNote ที่รับ 2 พารามิเตอร์: idf (ID ของ element) และ disa (สถานะการปิดใช้งาน)
function ShowSummerNote(idf, disa) {
    // เริ่มการตั้งค่า Summernote สำหรับ element ที่มี ID ตามค่า idf ที่ส่งเข้ามา
    $('#' + idf).summernote({
        toolbar: [
            ['style', ['bold', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
        ],
        disableResizeEditor: true,
        minHeight: null,
        height: null,
        placeholder: 'Enter...',
        callbacks: {
            onInit: function () {
                $(this).on('click', 'a', function (e) {
                    e.preventDefault();
                    $('#' + idf).summernote('disable');
                });
            },
            onBlur: function () {
                $('#' + idf).summernote('enable');
            },
            onDrop: function (e) {
                e.preventDefault();
            },
            onImageUpload: function (files) {
                return false;
            },
            onPaste: function (e) {
                var clipboardData = (e.originalEvent || e).clipboardData;
                var pastedData = clipboardData.getData('text');

                if (pastedData.startsWith('https://') || pastedData.startsWith('http://')) {
                    e.preventDefault();

                    var link = $('<a></a>').attr('href', pastedData).text(pastedData);
                    $('#' + idf).summernote('insertNode', link[0]);
                }
            }
        }
    });
    // ตรวจสอบค่า disa ที่ส่งเข้ามา
    if (disa == 'Y') {
        // ถ้าค่า disa เป็น 'Y'
        $('#' + idf).summernote('disable'); // ปิดการใช้งาน editor
    }
    // ดักจับ event ที่ editor โดยตรงเพื่อป้องกันการลากและวาง
    $('#' + idf).on('drop dragover', function (e) {
        e.preventDefault(); // ป้องกันการกระทำนี้
    });
}

// ดักจับ event 'dragover', 'dragenter', และ 'drop' ทั่วทั้งเอกสาร (document)
$(document).on('dragover dragenter drop', function (e) {
    e.preventDefault(); // ป้องกันการทำงานเริ่มต้นของ event เหล่านี้
    e.stopPropagation(); // หยุดการแพร่กระจายของ event ไปยัง element อื่น
});

// ดักจับทั้งหน้าจอ ป้องกัน overlay ของ browser
$(document).on('dragover dragenter drop', function (e) {
    e.preventDefault();
    e.stopPropagation();
});

// ฟังก์ชันสำหรับเคลียร์ไฟล์ที่แสดงผลบนหน้าจอทั้งหมด
function clearAllUploadedFiles() {
    // เลือก div ทั้งหมดที่ใช้แสดงไฟล์ที่เลือกแล้ว
    const fileAreas = fileUploadContainer.querySelectorAll('.fileUploadArea:not(.addFileArea)');
    // วนลูปและลบแต่ละ div ออกจาก DOM
    fileAreas.forEach(area => area.remove());
    // อัปเดตการแสดงผลปุ่ม "+"
    updateAddButtonVisibility();
}

// ให้check true false
function setCheckboxStates(states) {
    for (const [name, state] of Object.entries(states)) {
        const checkbox = document.getElementsByName(name)[0];
        if (checkbox) {
            checkbox.checked = state;
        }
    }
}

//======Preview File=========================================================================
$(document).ready(function () {
    // ดักจับการคลิกที่ .preview-trigger
    $(document).on('click', '.preview-trigger', function (e) {
        e.preventDefault(); // ป้องกันการทำงานของลิงก์ # เสมอ เพื่อไม่ให้หน้าจอเลื่อน

        // ตรวจสอบว่า element ที่ถูกคลิกจริงๆ คือปุ่มลบ (หรือมี parent เป็นปุ่มลบ) หรือไม่
        if ($(e.target).closest('.removeFileButton').length > 0) {
            // ถ้าใช่ ให้หยุดการทำงานของ preview ทันที
            // และปล่อยให้ event bubbling ทำงานต่อไปเพื่อให้ listener อื่น (ถ้ามี) จัดการลบไฟล์
            return;
        }

        // --- โค้ดส่วนที่เหลือจะทำงานเฉพาะเมื่อไม่ได้คลิกปุ่มลบ ---

        var fileType = $(this).data('file-type');
        var fileContent = $(this).data('file-content');
        var fileName = $(this).data('file-name');
        var dataUri = 'data:' + fileType + ';base64,' + fileContent;

        if (fileType.startsWith('image/') || fileType.includes('pdf')) {
            // สำหรับรูปภาพและ PDF: เปิดแท็บใหม่เพื่อพรีวิว
            var newTab = window.open();
            newTab.document.write('<!DOCTYPE html><html><head><title>Preview - ' + fileName + '</title></head><body style="margin:0; padding:0;">');
            if (fileType.startsWith('image/')) {
                newTab.document.write('<img src="' + dataUri + '" style="max-width:100%; display:block; margin:auto;">');
            } else {
                newTab.document.write('<iframe src="' + dataUri + '" style="width:100%; height:100vh; border:none;"></iframe>');
            }
            newTab.document.write('<a href="' + dataUri + '" download="' + fileName + '" style="position:fixed; bottom:20px; right:20px; padding:10px 20px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px;">Download File</a>');
            newTab.document.write('</body></html>');
        } else {
            // สำหรับไฟล์ประเภทอื่นๆ ที่ไม่สามารถพรีวิวได้: ดาวน์โหลดทันที
            var downloadLink = document.createElement('a');
            downloadLink.href = dataUri;
            downloadLink.download = fileName;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    });
});



//Start Update Image Profile
const self_profile_image = document.getElementById('self_profile_image');
const self_preview = document.getElementById('self_preview');
const self_ShowUserImg = document.getElementById('self_ShowUserImg');
const self_ShowShortName = document.getElementById('self_ShowShortName');
let self_UpdateProfile = new bootstrap.Modal(document.getElementById('modal-UserUpProfile'));
// modal-UserUpProfile
function EditUserProfile(val) {
    self_profile_image.value = '';
    self_preview.src = '';
    self_preview.classList.add('hidden');
    $('#self_changeImage').val('N');
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
                        $('#self_TK_UserEmail').val(value["User_Email"]);
                        $('#self_TK_UserID').val(value["UserID"]);
                        $('#self_UserEmail_Desc').val(value["F_Name"]);
                        $('#self_action').val('E');
                        $('#self_saveBtn').text('Save');
                        const self_short_name = value["short_name"];
                        const self_Color_Code = value["Color_Code"];
                        const self_Image_Profile = value["Image_Profile"];
                        const CircelShortname = `<div class='circle-container image-preview ' style='background-color: rgba( ${self_Color_Code},0.2); color:rgba(${self_Color_Code}, 1) '>
                                                    ${self_short_name}
                                                 </div>`;
                        self_ShowShortName.innerHTML = CircelShortname;
                        if (self_Image_Profile && self_Image_Profile !== '') {
                            self_preview.classList.add('hidden');
                            const html_string = `<img class='image-preview' src="data:image/jpg;base64,${self_Image_Profile}"  alt="" width="100%" class="card-img"> `;
                            self_ShowUserImg.innerHTML = html_string;
                            self_ShowUserImg.style.display = '';
                            self_ShowShortName.style.display = 'none';
                        } else {
                            self_ShowShortName.style.display = '';
                            self_ShowUserImg.style.display = 'none';
                        }
                        $('#textshow').text('ยืนยันการแก้ไขข้อมูล');
                        self_UpdateProfile.show();
                    });
                }
            } else {
                console.log(res.error);
            }
        }
    });
}

// Preview รูปใหม่เมื่อเลือกไฟล์
if (self_profile_image) {
    self_profile_image.addEventListener('change', function (event) {
        const self_file = event.target.files[0];
        if (self_file) {
            // ตรวจสอบประเภทไฟล์
            const self_fileType = self_file.type;
            const self_validImageTypes = ['image/jpeg', 'image/png'];

            if (self_validImageTypes.includes(self_fileType)) {
                // ถ้าเป็น JPG หรือ PNG ให้ดำเนินการต่อ
                self_preview.src = URL.createObjectURL(self_file);
                self_preview.classList.remove('hidden');
                $('#self_changeImage').val('Y');
                self_ShowUserImg.style.display = 'none';
                self_ShowShortName.style.display = 'none';
            } else {
                // ถ้าไม่ใช่ประเภทที่ถูกต้อง ให้แจ้งเตือนและเคลียร์ input
                alert('กรุณาเลือกไฟล์รูปภาพประเภท JPG หรือ PNG เท่านั้น');
                self_profile_image.value = '';
                self_preview.src = '';
                self_preview.classList.add('hidden');
            }
        }
    });
}

function UserUploadProfile(val) {
    const self_TK_UserID = $('#self_TK_UserID').val();
    const self_fileInput = document.getElementById('self_profile_image');
    const self_file = self_fileInput.files[0];
    const self_changeImage = $('#self_changeImage').val();
    if (self_file) {
        if (!self_file || !self_file.type || !['image/jpeg', 'image/png'].includes(self_file.type)) {
            toastralert('กรุณาเลือกไฟล์รูปภาพประเภท JPG หรือ PNG เท่านั้น', '', 'W');
            return;
        }
    }

    if (self_changeImage === 'N') {
        toastralert('No changes were made to the profile picture.', '', 'W');
        return;
    }

    let formData = new FormData();
    formData.append("functionname", "UserUpProfileImg");
    formData.append("profile_image", self_file);
    formData.append("arguments", self_TK_UserID);

    jQuery.ajax({
        type: "POST",
        url: 'ajax/ticket.ajax.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res, status) {
            if (res.stat === 'Y' || res.stat === 'N') {
                document.getElementById('DisplayProfileImg').innerHTML = res.result;
            }
            toastralert('Your profile was successfully updated', '', res.stat);

        },
        error: function (xhr, status, error) {
            toastralert('Your profile was false updated', '', 'N');
            console.error('เกิดข้อผิดพลาดในการส่งข้อมูลฟอร์ม:', error);
        }
    });
}

function Removeprofile() {
    $('#self_changeImage').val('Y');
    self_preview.classList.add('hidden');
    self_ShowUserImg.style.display = 'none';
    self_ShowShortName.style.display = '';
    self_profile_image.value = '';
}


function rgbaToOpaqueHex(r, g, b, alpha) {
    // กำหนดสีพื้นหลัง (Back Color) เป็นสีขาว (255, 255, 255)
    const backR = 255;
    const backG = 255;
    const backB = 255;
    // คำนวณสีผลลัพธ์ (Result Color)
    const newR = Math.round((r * alpha) + (backR * (1 - alpha)));
    const newG = Math.round((g * alpha) + (backG * (1 - alpha)));
    const newB = Math.round((b * alpha) + (backB * (1 - alpha)));
    // Helper function เพื่อแปลงเลขฐานสิบเป็น Hex 2 หลัก (พร้อมเติม 0 ข้างหน้าถ้าจำเป็น)
    const toHex = (c) => {
        const hex = c.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    };
    // รวมและแปลงเป็นโค้ด Hex
    return `#${toHex(newR)}${toHex(newG)}${toHex(newB)}`.toUpperCase();

}

function formatDateTo_dd_mm_yyyy_hh_mm(dateValue) {
    // 1. ตรวจสอบและจัดการค่าพิเศษ
    if (!dateValue ||
        dateValue === '' ||
        dateValue.toString().trim() === '1900-01-01 00:00:00.000') {
        return '';
    }
    // 2. สร้างวัตถุ Date จากค่าที่รับเข้ามา
    const date = new Date(dateValue);
    // 3. ตรวจสอบความถูกต้องของ Date object
    if (isNaN(date.getTime())) {
        return '';
    }
    // 4. เตรียมส่วนประกอบของวันที่และเวลา
    // วันที่ (dd-mm-yyyy)
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() คืนค่า 0-11
    const year = date.getFullYear();
    // เวลา (hh:mm)
    const hours = String(date.getHours()).padStart(2, '0');
    // *** เปลี่ยนจาก getSeconds() เป็น getMinutes() ***
    const minutes = String(date.getMinutes()).padStart(2, '0');
    // 5. รวมเป็นรูปแบบ 'dd-mm-yyyy hh:mm'
    return `${day}-${month}-${year} ${hours}:${minutes}`;
}
// ฟังก์ชันแสดง modal alert พร้อมข้อความ
function showAlertModal(msg, flag) {
    document.getElementById('textshow').textContent = msg;
    const timesCircle = document.getElementById('times-circle');
    const checkCircle = document.getElementById('check-circle');
    if (flag === 'error') {
        if (timesCircle) timesCircle.style.display = 'block';
        if (checkCircle) checkCircle.style.display = 'none';
    } else if (flag === 'success') {
        if (timesCircle) timesCircle.style.display = 'none';
        if (checkCircle) checkCircle.style.display = 'block';
    } else {
        if (timesCircle) timesCircle.style.display = 'none';
        if (checkCircle) checkCircle.style.display = 'none';
    }
    if (typeof $ !== 'undefined' && $('#alert-model').length) {
        $('#alert-model').modal('show');
    } else {
        document.getElementById('alert-model').style.display = 'block';
    }
}