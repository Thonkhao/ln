/**
 * Order Tracking DataTable Module
 *
 * ใช้สำหรับ ordertrackingMOT.php, ordertrackingOVS.php, ordertrackingTRT.php
 *
 * Required:
 * - window.orderTrackingConfig = { year, month, salesOffice } (set by PHP)
 * - data-sales-office attribute บน #tableContainer
 *
 * Performance: หน้า page render ทันที ไม่ต้องรอ query จาก server
 * ข้อมูลตารางโหลดผ่าน AJAX หลังจากนั้น - loading overlay จะซ่อนก็ต่อเมื่อ
 * ทั้งข้อมูลและ DataTable init เสร็จสมบูรณ์
 */
// IIFE (Immediately Invoked Function Expression) - กัน variable ในนี้ไปชนกับ global scope
// ของ script อื่นบนหน้าเดียวกัน (ไฟล์นี้ถูก include ซ้ำใน 4 หน้า: All/MOT/OVS/TRT)
(function() {
    'use strict';

    // config มาจาก <script> ที่ PHP echo ไว้ก่อนโหลดไฟล์นี้ (window.orderTrackingConfig)
    // ประกอบด้วย year, month, salesOffice, reportUrls (URL รายงาน Summary/Detail ที่ PHP สร้างไว้แล้ว)
    const config = window.orderTrackingConfig || {};
    // ต้องแนบ CSRF token ไปกับทุก request POST ไปยัง ajax/tracking.ajax.php เพื่อกัน CSRF attack
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ลำดับการหาค่า salesOffice: อ่านจาก data-sales-office ของ #tableContainer ก่อน (ค่าจริงจาก HTML)
    // ถ้าไม่มีค่อย fallback ไปที่ config.salesOffice แล้วสุดท้ายเป็น '' (กันพังถ้าทั้งคู่ไม่มี)
    const salesOffice = document.getElementById('tableContainer')?.dataset.salesOffice || config.salesOffice || '';

    let allData = [];      // ข้อมูล order ทั้งหมดที่โหลดมาจาก AJAX (ยังไม่ได้ filter ตาม status)
    let dataTable = null;  // instance ของ DataTables ปัจจุบัน (เก็บไว้เพื่อ destroy() ก่อนสร้างใหม่)
    let currentStatus = 'A'; // สถานะ tab ที่กำลังเลือกอยู่ ('A' = All)

    // Column definitions (reusable) - แมป field ใน JSON ที่ได้จาก server เข้ากับแต่ละคอลัมน์ของตาราง
    // ตามลำดับให้ตรงกับ <thead><th> ใน PHP view เป๊ะๆ เพราะ DataTables จับคู่ตามตำแหน่ง (index)
    const columnDefs = [
        { data: 'seq', className: 'text-center', width: '50px' },
        { data: 'SONo', className: 'text-center', width: '100px' },
        { data: 'LoadSeq', className: 'text-center', width: '70px', defaultContent: '-' },
        { data: 'CustGroup', className: 'text-center', width: '90px' },
        { data: 'SOOrderDateDisp', className: 'text-center', width: '100px' },
        { data: 'SODueDateDisp', className: 'text-center', width: '100px' },
        { data: 'SOExpDateDisp', className: 'text-center', width: '100px' },
        { data: 'Customer', width: '180px' },
        {
            data: 'TruckType',
            className: 'text-center',
            width: '60px',
            // render แทนที่จะโชว์ text ตรงๆ - วาดเป็นวงกลมสีตาม typeColor ที่ server ส่งมาต่อแถว
            render: function(data, type, row) {
                if (!data) return '';
                return '<span class="type-circle" style="border-color:' + row.typeColor + ';">' + data + '</span>';
            }
        },
        { data: 'ShipTypeDisp', className: 'text-center', width: '100px' },
        { data: 'CustPO', className: 'text-center', width: '100px' },
        { data: 'Salesperson', width: '130px' },
        { data: 'LoadNo', className: 'text-center', width: '100px' },
        { data: 'InvNum', className: 'text-center', width: '100px' },
        { data: 'InvDate', className: 'text-center', width: '100px' },
        { data: 'TruckageNo', className: 'text-center', width: '100px' },
        { data: 'LicensePlate', className: 'text-center', width: '90px' },
        { data: 'DriverTel', className: 'text-center', width: '90px' },
		{ data: 'SalesPCTel', className: 'text-center', width: '90px' },
        {
            data: 'StatDesc',
            width: '110px',
            // เช่นกัน - วาด status เป็น badge สี (border/background/text) ตามค่าที่ server คำนวณมาให้แล้ว
            // (สีถูกกำหนดฝั่ง server ไม่ใช่ hardcode ใน JS เพื่อให้แก้ผังสีได้จุดเดียว)
            render: function(data, type, row) {
                return '<div class="all-center"><span class="status-badge" style="border-color:' + row.statusBorder +
                       ';background-color:' + row.statusBg + ';color:' + row.statusColor +
                       ';"><i class="fa-solid fa-circle" style="font-size:6px;"></i> ' + data + '</span></div>';
            }
        },
        { data: 'TruckageDate', className: 'text-center', width: '100px' },
        { data: 'DriverCompletedDisp', className: 'text-center', width: '100px' }
    ];

    // Filter data BEFORE passing to DataTables (เร็วกว่า custom search filter!)
    // เหตุผล: DataTables built-in search/filter ต้อง loop ผ่านทุก row + ทุก column ทุกครั้งที่ filter
    // แต่ในเมื่อ allData อยู่ใน memory (client-side) อยู่แล้ว การกรองด้วย .filter() ธรรมดา
    // แล้วค่อยส่ง array ที่กรองแล้วเข้า DataTables ใหม่ทั้งก้อน เร็วกว่ามาก
    function getFilteredData(status) {
        if (status === 'A') return allData; // 'A' = All -> ไม่ต้องกรอง คืนทั้งหมด
        // data-status รองรับหลายค่ารวมกันในสตริงเดียว เช่น "12" = สถานะ 1 หรือ 2, "56" = สถานะ 5 หรือ 6
        // แยกเป็นตัวอักษรทีละตัวแล้วเช็คว่า statquery ของแถวนั้นตรงกับตัวใดตัวหนึ่งหรือไม่ (OR)
        const statusList = status.split('');
        return allData.filter(row => statusList.includes(row.statquery)); // statquery คือ status code จาก server
    }

    // สร้าง (หรือสร้างใหม่) DataTable instance จาก data ที่ส่งเข้ามา
    // ฟังก์ชันนี้ถูกเรียกทั้งตอนโหลดหน้าครั้งแรก และทุกครั้งที่ผู้ใช้เปลี่ยน tab สถานะ
    function initDataTable(data, hideLoading) {
        // ต้อง destroy() instance เก่าก่อนเสมอ ไม่งั้น DataTables จะ error
        // ("Cannot reinitialise DataTable") เพราะ table เดิมยังผูก event/state ค้างอยู่
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }

        dataTable = $('#orderTrackingDataTable').DataTable({
            data: data,
            deferRender: true,       // สร้าง DOM element เฉพาะแถวที่แสดงอยู่บนหน้าปัจจุบัน (เร็วกว่ามากเมื่อข้อมูลเยอะ)
            order: [],                // ไม่ sort อัตโนมัติ - ใช้ลำดับที่ server ส่งมา (เรียงตาม seq)
            lengthMenu: [[15, 25, 50, 100], [15, 25, 50, 100]],
            pageLength: 25,
            searching: true,
            autoWidth: false,
            scrollX: true,             // ตารางกว้าง scroll แนวนอนได้ (คอลัมน์เยอะ)
            columns: columnDefs,
            initComplete: function() {
                // ซ่อน loading overlay ก็ต่อเมื่อ DataTable สร้างเสร็จสมบูรณ์แล้วเท่านั้น (ไม่ใช่แค่ data โหลดเสร็จ)
                // เพื่อกันผู้ใช้เห็นตารางเปล่าโผล่มาก่อนที่ DataTables จะ render แถวเสร็จ
                if (hideLoading) {
                    $('#loading-overlay').hide();
                }
            }
        });
    }

    // เรียกตอนผู้ใช้กด tab สถานะ (All/Ordered/Invoiced/Shipping/Completed)
    // จำสถานะปัจจุบันไว้ใน currentStatus ด้วย เผื่อต้อง initDataTable() ซ้ำจากที่อื่น (เช่นหลัง fetch ข้อมูลใหม่)
    function filterOrderList(status) {
        currentStatus = status;
        const filteredData = getFilteredData(status);
        initDataTable(filteredData, false); // false = ไม่ต้องซ่อน loading overlay (โหลดข้อมูลเสร็จไปแล้วตั้งแต่แรก)
    }

    // เติม option ใน dropdown ของ CustGroup (จาก-ถึง) และ Salesperson (เดี่ยว) ใน modal Print Report
    // โดยดึงค่าที่ไม่ซ้ำ (unique) จาก allData ที่โหลดมาแสดงตารางอยู่แล้ว - ไม่ต้องยิง AJAX เพิ่ม
    // เรียกทุกครั้งหลังโหลดข้อมูลใหม่ (เปลี่ยนเดือน/ปี) เพื่อให้ตัวเลือกตรงกับข้อมูลเดือนนั้นๆ
    function populatePrintFilterOptions() {
        // new Set(...) ใช้ตัดค่าซ้ำ, filter(Boolean) ตัดค่าว่าง/null/undefined ออก, sort() เรียง A-Z
        var custGroups = [...new Set(allData.map(row => row.CustGroup).filter(Boolean))].sort();

        // Salesperson จาก server เป็น "รหัส-ชื่อ" รวมกัน เช่น "016-สมชาย ใจดี" (ดู controller: $salesperson = ID.'-'.Name)
        // แต่ query param ที่ส่งให้ report ต้องการแค่ "รหัส" เท่านั้น จึงต้องแยก value (ที่จะถูกส่งไป)
        // ออกจาก label (ข้อความที่โชว์ในตัวเลือก ยังคงเป็น "รหัส-ชื่อ" เต็มเพื่อให้เลือกถูกคน)
        // ตัดค่าซ้ำด้วยรหัส (ไม่ใช่ label เต็ม) เผื่อ format ชื่อไม่ตรงกันเป๊ะระหว่างแถว
        var salespersonMap = new Map();
        allData.forEach(row => {
            if (!row.Salesperson) return;
            var id = row.Salesperson.split('-')[0];
            if (!salespersonMap.has(id)) salespersonMap.set(id, row.Salesperson);
        });
        var salespersons = [...salespersonMap.entries()]
            .sort((a, b) => a[0].localeCompare(b[0]))
            .map(([id, label]) => ({ value: id, label: label }));

        // เติม <option> ให้ select ตาม id ที่ระบุ โดยคงค่าที่เลือกไว้เดิม (ถ้ายังมีอยู่ใน values ชุดใหม่)
        // values แต่ละตัวเป็นได้ทั้ง string ธรรมดา (value กับ label ค่าเดียวกัน เช่น CustGroup)
        // หรือ {value, label} เมื่อ value ที่ต้องส่งจริงไม่ตรงกับข้อความที่อยากโชว์ (เช่น Salesperson)
        function fillSelect(id, values, placeholder) {
            var select = document.getElementById(id);
            if (!select) return;
            var currentValue = select.value;
            select.innerHTML = '<option value="">' + placeholder + '</option>';
            values.forEach(item => {
                var option = document.createElement('option');
                if (item && typeof item === 'object') {
                    option.value = item.value;
                    option.textContent = item.label;
                } else {
                    option.value = item;
                    option.textContent = item;
                }
                select.appendChild(option);
            });
            select.value = currentValue; // ถ้า currentValue ไม่มีใน options ใหม่ browser จะ reset เป็นค่าว่างให้เอง
        }

        fillSelect('printCustGroupS', custGroups, '-- จาก --');
        fillSelect('printCustGroupE', custGroups, '-- ถึง --');
        fillSelect('printSalesperson', salespersons, '-- ทั้งหมด --'); // dropdown เดียว ไม่มีจาก-ถึง
    }

    // อัปเดตตัวเลขจำนวนออเดอร์ในวงเล็บของแต่ละ tab เช่น "Ordered (12)"
    // summary คำนวณมาจาก server แล้ว (นับรวมทุก record ไม่ใช่แค่หน้าที่แสดงอยู่)
    function updateSummaryCounts(summary) {
        summary = summary || {};
        const map = {
            'count-all': summary.Stat_All,
            'count-ordered': summary.Stat_Ordered,
            'count-invoiced': summary.Stat_Invoiced,
            'count-shipping': summary.Stat_Shipping,
            'count-completed': summary.Stat_Completed
        };
        Object.keys(map).forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = map[id] || 0; // ไม่มีค่า -> โชว์ 0 แทน undefined/NaN
        });
    }

    // Fetch order data via AJAX - เรียกทันทีตอนสคริปต์รัน (ไม่ต้องรอ DOMContentLoaded)
    // เพื่อให้ request เริ่มยิงเร็วที่สุด ขนานไปกับตอนที่ browser render หน้าที่เหลือ
    function fetchOrderData() {
        fetch('ajax/tracking.ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'GetOrderDataJSON',
                    year: config.year,
                    month: config.month,
                    salesoffice: salesOffice,
                    csrf_token: csrfToken
                })
            })
            // อ่าน response เป็น text ก่อนเสมอ (ไม่ใช้ res.json() ตรงๆ) เพราะถ้า server error แล้วตอบ HTML
            // (เช่น PHP fatal error page) res.json() จะ throw error ที่ไม่บอกอะไรเลยว่า response จริงคืออะไร
            // การอ่านเป็น text แล้ว JSON.parse() เอง ทำให้ error message ด้านล่างใส่ raw response กลับมาช่วย debug ได้
            .then(res => res.text().then(text => {
                let result;
                try {
                    result = JSON.parse(text);
                } catch (parseErr) {
                    throw new Error('Server ตอบกลับไม่ใช่ JSON (HTTP ' + res.status + '): ' + text.substring(0, 500));
                }
                // ฝั่ง PHP จะ return { success: true/false, ... } เสมอ - เช็ค success flag ไม่ใช่แค่ HTTP status
                if (!result || result.success !== true) {
                    throw new Error((result && result.error) || 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุจาก server');
                }
                return result;
            }))
            .then(result => {
                allData = Array.isArray(result.data) ? result.data : [];
                updateSummaryCounts(result.summary);
                initDataTable(getFilteredData(currentStatus), true); // true = ซ่อน loading overlay เมื่อสร้างตารางเสร็จ
                populatePrintFilterOptions();
            })
            .catch(err => {
                // ถ้าโหลดข้อมูลไม่สำเร็จ (network error, server error, parse error) - แสดงตารางว่างแทนการค้าง loading ไปตลอด
                console.error('Order Tracking: failed to load data', err);
                allData = [];
                initDataTable([], true);
            });
    }

    // เริ่มโหลดข้อมูลทันที ไม่ต้องรอ DOM/JS อื่นเสร็จ
    fetchOrderData();

    // Initialize UI bindings - ต้องรอ DOMContentLoaded เพราะ element เหล่านี้ (tab, input, button)
    // อยู่ใน HTML ที่ script tag import ไฟล์นี้มา ต้องแน่ใจว่า DOM สร้างเสร็จก่อนไป querySelector
    // (fetchOrderData() ด้านบนไม่ต้องรอ เพราะแค่ยิง network request ไม่ได้แตะ DOM)
    document.addEventListener('DOMContentLoaded', function() {
        // แปลง select ธรรมดา (CustGroup/Salesperson จาก-ถึง ใน modal Print Report) ให้เป็น select2
        // theme เดียวกับที่ใช้ใน TruckageReport.php (views/js/report/truckage_report.js)
        // ต้องระบุ dropdownParent เป็น modal เอง ไม่งั้น dropdown ของ select2 จะโผล่ไปอยู่หลัง backdrop
        // ของ Bootstrap modal (ปัญหาคลาสสิกเวลาใช้ select2 ในหน้า modal)
        $('#printCustGroupS, #printCustGroupE, #printSalesperson').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#modal-printReport')
        });

        // Tab click events - คลิก tab สถานะ (All/Ordered/...) แล้ว filter ตารางใหม่โดยไม่โหลดหน้าใหม่
        document.querySelectorAll('#orderStatusTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault(); // กัน href="#" เลื่อนหน้าขึ้นบนสุด
                // เอา active class ออกจากทุก tab ก่อน แล้วค่อยใส่ให้ tab ที่เพิ่งกด (ให้ active มีแค่อันเดียวเสมอ)
                document.querySelectorAll('#orderStatusTabs .nav-link').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                filterOrderList(this.dataset.status);
            });
        });

        // Month selector - เปลี่ยนเดือน/ปีแล้ว redirect ไปหน้าเดิมพร้อม query string ใหม่ (โหลดหน้าใหม่ทั้งหมด)
        // ไม่ได้ทำเป็น AJAX เพราะต้องการให้ PHP คำนวณ $year/$moth ใหม่ (ใช้สร้าง reportUrls ด้วย)
        document.getElementById('monthSelector').addEventListener('change', function() {
            $('#loading-overlay').show();
            // salesOffice 'All' ไม่มี suffix ต่อท้าย route (r=ordertracking) ส่วน MOT/OVS/TRT จะได้
            // r=ordertrackingMOT / ordertrackingOVS / ordertrackingTRT ตามลำดับ
            var routeSuffix = (salesOffice === 'All') ? '' : salesOffice;
            window.location = 'index.php?s=tracking&r=ordertracking' + routeSuffix + '&my=' + this.value;
        });

        // ปุ่ม "Print" ใน modal Print Report - ประกอบ URL รายงานจากตัวเลือกทั้งหมดในโมดัลแล้วเปิดแท็บใหม่
        document.getElementById('printReportConfirmBtn')?.addEventListener('click', function() {
            // อ่านว่าเลือก radio Summary หรือ Detail (default 'summary' ถ้าหาไม่เจอด้วยเหตุผลใดก็ตาม)
            var reportType = document.querySelector('input[name="printReportType"]:checked')?.value || 'summary';
            // reportUrls ตอนนี้มีแค่ "?report=..." เปล่าๆ (ไม่มี yy/mm/salesoff ฝังมาจาก PHP แล้ว)
            // เพราะ modal ให้เลือกเดือน-ปีเองได้ (อาจคนละเดือนกับหน้าหลัก) จึงต้องต่อ query string ทั้งหมดที่นี่
            var url = (config.reportUrls || {})[reportType];
            if (url) {
                // เดือน-ปีที่เลือกใน modal (#printMonthYear ค่าเป็น "YYYY-MM")
                // ถ้าผู้ใช้ยังไม่ได้แตะ (หรือ browser ไม่รองรับ input month) ใช้ปี/เดือนของหน้าปัจจุบันแทน
                var printMonthYear = document.getElementById('printMonthYear')?.value || '';
                var yy = config.year;
                var mm = config.month;
                if (printMonthYear) {
                    var parts = printMonthYear.split('-');
                    yy = parts[0];
                    mm = parts[1];
                }

                // เช็คว่าติ๊ก checkbox สถานะไหนไว้บ้าง (Ordered/Invoiced/Shipping/Completed)
                // แต่ละ checkbox อาจมีค่าหลายตัวอักษรรวมกันอยู่แล้ว (เช่น "12", "56") เหมือนที่ใช้กับ tab บนหน้า
                // เอามารวมกันเป็นสตริงเดียว (ตัดค่าซ้ำด้วย Set) ก่อนส่งเป็น param 'stat'
                var checkedStatuses = Array.from(document.querySelectorAll('input[name="printStatus[]"]:checked')).map(cb => cb.value);
                var statParam = [...new Set(checkedStatuses.join('').split(''))].join('');

                // Salesperson เป็น dropdown เดียว (ไม่มีจาก-ถึงแล้ว) - ส่งค่าเดียวกันเป็นทั้ง spersons/spersone
                // ให้ rpt_ordertracking.php มองเป็น "ช่วงที่จุดเดียว" (start == end) เท่ากับกรองเฉพาะคนนี้คนเดียว
                var salesperson = document.getElementById('printSalesperson')?.value || '';

                var extraParams = {
                    yy: yy,
                    mm: mm,
                    salesoff: salesOffice,
                    // อ่านค่าจาก dropdown "จาก-ถึง" ของ CustGroup ที่ populatePrintFilterOptions() เติมตัวเลือกไว้ให้
                    custgroups: document.getElementById('printCustGroupS')?.value || '',
                    custgroupe: document.getElementById('printCustGroupE')?.value || '',
                    spersons: salesperson,
                    //spersone: salesperson,
                    // ไม่ติ๊กเลย = ไม่กรอง (ปล่อยให้ rpt_ordertracking.php ใช้ default 'A' = ทั้งหมด)
                    stat: statParam
                };
                // ต่อเฉพาะ param ที่มีค่าจริง (ไม่ส่ง key ที่เป็นค่าว่าง) ให้ rpt_ordertracking.php
                // ฝั่ง PHP เช็ค !empty($_GET[...]) อยู่แล้ว จึงไม่ส่งก็ได้ผลเหมือนส่งค่าว่าง แต่สะอาดกว่า
                Object.keys(extraParams).forEach(key => {
                    if (extraParams[key]) {
                        url += '&' + key + '=' + encodeURIComponent(extraParams[key]);
                    }
                });
                window.open(url, '_blank'); // เปิดแท็บใหม่แทนการ redirect หน้าเดิม เพื่อให้ตารางบนหน้าไม่หาย
            }
            $('#modal-printReport').modal('hide'); // ปิด modal ไม่ว่าจะเปิดรายงานสำเร็จหรือไม่ (url ไม่มีค่าก็ปิดเฉยๆ)
        });
    });
})();
