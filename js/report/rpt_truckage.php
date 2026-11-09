<?php
mb_internal_encoding('UTF-8');
require '../../../dbconnect/Connect.php';

/**
 * Backend สำหรับ Truckage Report
 * เรียกผ่าน views/modules/report/truckage_report.php (form GET, target="_blank")
 *
 * รายชื่อ parameter (TruckNumF/T, DateF/T, VendNumF/T, LicenseF/T, TruckageType, Stat, Comp,
 * pConnectionString, BG_USERID, BG_SITEID, BG_COMPANYNAME, pSite) ยืนยันแล้วจาก parameter list
 * จริงของ report (ดูใน Report Builder) - BG_USERID/BG_SITEID/BG_COMPANYNAME/pSite ฟอร์มยังไม่มี
 * ช่องให้กรอก จึงส่ง null ไปก่อน ถ้า report ต้องการค่าจริงต้องเพิ่มช่องในฟอร์ม/หา source มาใส่
 *
 * วิธีเช็คซ้ำถ้ายังมี unknown/missing parameter: เปิด URL นี้เพิ่ม &debug=1 ต่อท้าย (เช่น
 * ...rpt_truckage.php?report=<ชื่อ report>&debug=1) ระบบจะไม่ส่ง parameter ใดๆ เลย
 * นอกจาก rs:Format ทำให้ SSRS ฟ้อง error กลับมาพร้อมชื่อ parameter ที่ report ต้องการจริง
 */

$ReportName = $_GET['report'] ?? '';

$L_report = $_ENV['L_report'];
$ssrs_url = $L_report . $ReportName;

# Report Server Login
$U_report = $_ENV['U_report'];
$P_report = $_ENV['P_report'];
$DB_HOST = $_ENV['DB_HOST'];
$DB_DATABASE = $_ENV['DB_DATABASE'];

// mode: preview = เปิดดูในแท็บ (inline) / print = ดาวน์โหลดเพื่อสั่งพิมพ์ (attachment)
$mode = ($_GET['mode'] ?? 'preview') === 'print' ? 'print' : 'preview';
$rsFormat = !empty($_GET['format']) ? $_GET['format'] : 'PDF';

if (!empty($_GET['debug'])) {
    // Debug mode: ไม่ส่ง parameter อะไรเลย เพื่อให้ SSRS ฟ้อง "missing required parameter"
    // กลับมาพร้อมชื่อ parameter ที่ report ต้องการจริง (ต่างจาก unknown-parameter exception ที่ไม่บอกชื่อ)
    $params = [
        'rs:Format' => $rsFormat,
    ];
} else {
    // เรียงตาม parameter list จริงของ report (เช็คจาก Report Builder) - ตัวไหนฟอร์มยังไม่มีช่องให้กรอกส่ง null ไปก่อน
    $params = [
        'rs:Format' => $rsFormat,
        'BG_USERID' => null,
        'BG_SITEID' => null,
        'BG_COMPANYNAME' => null,
        'pConnectionString' => "Data Source=$DB_HOST;Initial Catalog=$DB_DATABASE",
        'TruckNumF' => !empty($_GET['TrkNumS']) ? $_GET['TrkNumS'] : null,
        'TruckNumT' => !empty($_GET['TrkNumE']) ? $_GET['TrkNumE'] : null,
        'DateF' => !empty($_GET['DateS']) ? $_GET['DateS'] : null,
        'DateT' => !empty($_GET['DateE']) ? $_GET['DateE'] : null,
        'VendNumF' => !empty($_GET['VendorS']) ? $_GET['VendorS'] : null,
        'VendNumT' => !empty($_GET['VendorE']) ? $_GET['VendorE'] : null,
        'LicenseF' => !empty($_GET['PlateS']) ? $_GET['PlateS'] : null,
        'LicenseT' => !empty($_GET['PlateE']) ? $_GET['PlateE'] : null,
        'pSite' => null,
        'Comp' => !empty($_GET['company']) ? $_GET['company'] : '2000',
    ];
}

// สร้าง query string พารามิเตอร์แบบ scalar (ค่าเดียว) ก่อน
$query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

// TruckageType / Status เป็น multi-select (checkbox array) - SSRS multi-value parameter
// ต้องส่งแบบ key ซ้ำกันหลายครั้ง (เช่น TruckageType=A&TruckageType=B) ไม่ใช่ TruckageType[]=A
if (empty($_GET['debug'])) {
    foreach (['truckageType' => 'TruckageType', 'status' => 'Stat'] as $getKey => $ssrsKey) {
        if (!empty($_GET[$getKey]) && is_array($_GET[$getKey])) {
            foreach ($_GET[$getKey] as $val) {
                $query .= '&' . $ssrsKey . '=' . rawurlencode($val);
            }
        }
    }
}

// สร้าง URL สำหรับเรียกดูรายงาน
$url = $ssrs_url . '&' . $query;

// เริ่มต้น cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$U_report:$P_report");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC | CURLAUTH_NTLM);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$isPdf = strpos($response, '%PDF') === 0;
$isXlsx = strpos($response, 'PK') === 0;

if ($http_code == 200 && ($isPdf || $isXlsx)) {
    $disposition = $mode === 'preview' ? 'inline' : 'attachment';
    if ($isPdf) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="TruckageReport_' . date('Ymd') . '.pdf"');
    } else {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: ' . $disposition . '; filename="TruckageReport_' . date('Ymd') . '.xlsx"');
    }
    echo $response;
} else {
    if (strpos($response, 'rsCredentialsNotSpecified') !== false) {
        echo "เกิดข้อผิดพลาด: ระบบต้องการ Credential สำหรับ Data Source แต่ยังไม่ได้ตั้งค่าไว้ใน SSRS\n";
        echo "วิธีแก้:\n";
        echo "1. เข้าไปที่ Web Portal: /Reports แล้วเปิด Data Source ที่รายงานใช้\n";
        echo "2. เลือก Stored Credentials และกรอก SQL Login / Password ที่ถูกต้อง หรือใช้ Windows Integrated หาก IIS account มีสิทธิ์\n";
        echo "3. กด Test Connection ให้ผ่าน แล้ว Save\n";
        echo "4. กลับมาลองเรียก URL รายงานอีกครั้ง\n";
    } elseif (strpos($response, 'rsProcessingAborted') !== false) {
        echo "เกิดข้อผิดพลาด: การประมวลผลรายงานถูกยกเลิก (rsProcessingAborted)\n";
        echo "สาเหตุทั่วไป: พารามิเตอร์ไม่ถูกต้อง / Data Source ล้มเหลว / Timeout\n";
    } elseif (strpos($response, 'rsErrorOpeningConnection') !== false) {
        echo "เกิดข้อผิดพลาด: ไม่สามารถเชื่อมต่อ Data Source (rsErrorOpeningConnection)\n";
    } else {
        echo "ไม่สามารถดึงรายงานได้\n";
        echo "HTTP code: $http_code\n";

        // ดึงเฉพาะ meta tag ที่มีประโยชน์ (Message มักบอกชื่อ parameter ที่ผิด/ที่ต้องการตรง ๆ)
        if (preg_match_all('/<meta name="([^"]+)" content="([^"]*)"\s*\/>/', $response, $matches, PREG_SET_ORDER)) {
            echo "SSRS Error Detail:\n";
            foreach ($matches as $m) {
                echo "- {$m[1]}: {$m[2]}\n";
            }
        } else {
            echo "Response (full):\n" . htmlentities(substr($response, 0, 3000));
        }
    }
}
