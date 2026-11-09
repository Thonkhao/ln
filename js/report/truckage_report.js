/**
 * Truckage Report - หน้าเลือกเงื่อนไข (views/modules/report/truckage_report.php)
 * ยิงไปที่ ajax/report_truckage.ajax.php เพื่อดึง option ของ dropdown Truckage Num / Vendor / License Plate
 */
document.addEventListener('DOMContentLoaded', function() {
    // แปลง select ธรรมดา (TrkNum/Vendor/Plate ทั้ง Starting/Ending) ให้เป็น select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Please select --',
        allowClear: true
    });

    // CSRF token ฝังไว้ใน <meta name="csrf-token"> โดย views/template.php ต้องแนบไปกับทุก request ที่ยิง ajax/*.ajax.php
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /**
     * ล้าง option เดิม (เหลือแค่ตัวเลือกว่างตัวแรก) แล้วสั่ง select2 ให้เคลียร์ค่าที่เลือกไว้
     * ใช้ตอนเปลี่ยน company เพราะ option ชุดเก่าอาจไม่มีอยู่ใน company ใหม่แล้ว
     */
    function resetSelect(sel) {
        while (sel.options.length > 1) {
            sel.remove(1);
        }
        $(sel).val('').trigger('change');
    }

    /**
     * โหลด option ของ dropdown "Starting/Ending" คู่หนึ่งๆ จาก ajax/report_truckage.ajax.php
     * แล้วเติมชุด option เดียวกันให้ทั้งสองช่อง (เช่น TrkNumS กับ TrkNumE ใช้ list เดียวกัน)
     *
     * @param {string} functionname ชื่อ function ฝั่ง ajax (ต้องอยู่ใน allowedFunctions ของ report_truckage.ajax.php)
     * @param {string} selectIdS    id ของ select ช่อง Starting
     * @param {string} selectIdE    id ของ select ช่อง Ending
     * @param {string} company      รหัสบริษัทที่กำลังเลือกอยู่ (1000/2000) ใช้กรอง list ฝั่ง ajax
     */
    function loadOptionsForPair(functionname, selectIdS, selectIdE, company) {
        var selS = document.getElementById(selectIdS);
        var selE = document.getElementById(selectIdE);

        fetch('ajax/report_truckage.ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    functionname: functionname,
                    arguments: {
                        company: company
                    },
                    csrf_token: csrfToken
                })
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(res) {
                if (!res.success || !Array.isArray(res.result)) {
                    toastr.error('โหลดข้อมูล ' + functionname + ' ไม่สำเร็จ');
                    return;
                }
                // เติม option เดียวกันให้ทั้งสอง select (Starting และ Ending)
                // select2 อ่าน option จาก DOM ของ <select> สดๆ ทุกครั้งที่เปิด dropdown อยู่แล้ว ไม่ต้อง re-init ซ้ำ
                res.result.forEach(function(item) {
                    [selS, selE].forEach(function(sel) {
                        var opt = document.createElement('option');
                        opt.value = item.value;
                        opt.textContent = item.text;
                        sel.appendChild(opt);
                    });
                });
            })
            .catch(function() {
                toastr.error('โหลดข้อมูล ' + functionname + ' ไม่สำเร็จ');
            });
    }

    // โหลด/โหลดซ้ำ option ให้ทั้ง 3 คู่ dropdown ตาม company ที่เลือกอยู่ตอนนี้
    function reloadAllDropdowns() {
        var company = document.querySelector('input[name="company"]:checked')?.value || '2000';

        [
            ['TrkNumS', 'TrkNumE'],
            ['VendorS', 'VendorE'],
            ['PlateS', 'PlateE']
        ].forEach(function(pair) {
            resetSelect(document.getElementById(pair[0]));
            resetSelect(document.getElementById(pair[1]));
        });

        loadOptionsForPair('getTruckageNoList', 'TrkNumS', 'TrkNumE', company);
        loadOptionsForPair('getVendorList', 'VendorS', 'VendorE', company);
        loadOptionsForPair('getLicensePlateList', 'PlateS', 'PlateE', company);
    }

    // โหลด option ให้ทั้ง 3 คู่ dropdown ตอนเปิดหน้า
    reloadAllDropdowns();

    // เปลี่ยน company (radio 1000/2000) แล้วโหลด dropdown ทั้ง 3 คู่ใหม่ตามบริษัทที่เลือก
    document.querySelectorAll('input[name="company"]').forEach(function(radio) {
        radio.addEventListener('change', reloadAllDropdowns);
    });

    var form = document.getElementById('truckageReportForm');
    var reportModeInput = document.getElementById('reportMode');
    var reportNameInput = document.getElementById('reportName');

    // เปลี่ยน Report Type แล้วสลับชื่อ SSRS report (ช่อง hidden "report") ตาม data-report ของ radio ที่เลือก
    // แต่ละ Report Type เรียกคนละ report กัน ไม่ใช่ report เดียวส่ง reportType ต่างกันเหมือนเดิม
    document.querySelectorAll('input[name="reportType"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.dataset.report) {
                reportNameInput.value = this.dataset.report;
            }
        });
    });

    // ปุ่ม Print: ตั้งค่า mode เป็น print ก่อน submit ฟอร์ม (rpt_truckage.php ใช้ตัดสินใจ inline/attachment)
    document.getElementById('btnPrint').addEventListener('click', function() {
        reportModeInput.value = 'print';
    });

    /**
     * รวมค่า value ของ checkbox ที่ติ๊กไว้ในกลุ่ม checkboxName[] เป็น string เดียว
     * (เช่น truckageType[] ติ๊กครบ 3 อัน -> "123") แล้วยัดใส่ hidden input hiddenId
     * จากนั้นปิด (disabled) checkbox กลุ่มนั้นทั้งหมด เพื่อไม่ให้ browser ส่ง checkboxName[]=... ซ้ำไปกับ hidden input
     */
    function combineCheckboxGroup(checkboxName, hiddenId) {
        var boxes = form.querySelectorAll('input[name="' + checkboxName + '[]"]');
        var combined = Array.prototype.filter.call(boxes, function(box) {
                return box.checked;
            })
            .map(function(box) {
                return box.value;
            })
            .join('');
        document.getElementById(hiddenId).value = combined;
        boxes.forEach(function(box) {
            box.disabled = true;
        });

        // ฟอร์มเปิด target="_blank" หน้านี้เลยไม่ reload หลัง submit - ต้อง enable checkbox กลับ
        // (setTimeout 0 = รอให้ browser อ่านค่าฟอร์มไปส่งก่อน แล้วค่อยเปิดใช้งานกลับ)
        setTimeout(function() {
            boxes.forEach(function(box) {
                box.disabled = false;
            });
        }, 0);
    }

    // ก่อน submit: เช็คว่ากรอก Truckage Date ครบ (required) และ Ending ต้องไม่น้อยกว่า Starting
    form.addEventListener('submit', function(e) {
        var dateS = document.getElementById('DateS').value;
        var dateE = document.getElementById('DateE').value;
        if (!dateS || !dateE) {
            e.preventDefault();
            toastr.error('กรุณาระบุ Truckage Date ทั้ง Starting และ Ending');
            return false;
        }
        if (dateE < dateS) {
            e.preventDefault();
            toastr.error('วันที่สิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้น');
            return false;
        }

        // รวมค่า truckageType[]/status[] ที่ติ๊กไว้เป็น string เดียวก่อนส่งฟอร์มจริง
        combineCheckboxGroup('truckageType', 'truckageTypeCombined');
        combineCheckboxGroup('status', 'statusCombined');
    });
});
