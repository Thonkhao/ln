<?php
mb_internal_encoding('UTF-8');
require '../../../dbconnect/Connect.php';
$ReportName = $_GET['report'] ?? '';
// กำหนดข้อมูลการเชื่อมต่อ

$L_report = $_ENV['L_report'];
$ssrs_url = $L_report.$ReportName;
//	/WNV_LNGRDelivery
# Report Server Login
$U_report = $_ENV['U_report'];
$P_report = $_ENV['P_report'];
$DB_HOST = $_ENV['DB_HOST'];
$DB_DATABASE = $_ENV['DB_DATABASE'];
// รับค่าพารามิเตอร์จาก URL หรือกำหนดเอง

    $params = [

        'rs:Format' => 'EXCELOPENXML',
        'Comp' =>  '2000',
        // 'Comp' => !empty($_GET['company']) ? $_GET['company'] : '2000',
        'Date' =>  !empty($_GET['Date']) ? $_GET['Date'] : date('Ymd'),
    ];

// สร้าง query string สำหรับพารามิเตอร์
$query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

// สร้าง URL สำหรับเรียกดูรายงาน
$url = $ssrs_url . '&' . $query;

// เริ่มต้น cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$U_report:$P_report");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC | CURLAUTH_NTLM);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
// ดึงข้อมูล
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && strpos($response, 'PK') === 0) {
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="OnHand2Comp_' . date('Ymd_His') . '.xlsx"');
    echo $response;
}
else {

    if (strpos($response, 'rsCredentialsNotSpecified') !== false) {
        echo "เกิดข้อผิดพลาด: ระบบต้องการ Credential สำหรับ Data Source แต่ยังไม่ได้ตั้งค่าไว้ใน SSRS\n";
        echo "วิธีแก้:\n";
        echo "1. เข้าไปที่ Web Portal: /Reports แล้วเปิด Data Source ที่รายงานใช้\n";
        echo "2. เลือก Stored Credentials และกรอก SQL Login / Password ที่ถูกต้อง หรือใช้ Windows Integrated หาก IIS account มีสิทธิ์\n";
        echo "3. กด Test Connection ให้ผ่าน แล้ว Save\n";
        echo "4. กลับมาลองเรียก URL รายงานอีกครั้ง\n";
        echo "หมายเหตุ: ไม่สามารถส่ง Database Credential ผ่าน query string หรือ cURL โดยตรงให้ SSRS ถ้า Data Source ถูกตั้งให้ Prompt\n";
    } elseif (strpos($response, 'rsProcessingAborted') !== false) {
        echo "เกิดข้อผิดพลาด: การประมวลผลรายงานถูกยกเลิก (rsProcessingAborted)\n";
        echo "สาเหตุทั่วไป: พารามิเตอร์ไม่ถูกต้อง / Data Source ล้มเหลว / Timeout\n";
        echo "ตรวจสอบ:\n";
        echo "- พารามิเตอร์ที่ส่งว่าถูกต้องตามรายงาน\n";
        echo "- ลองเปิดรายงานใน Web Portal เพื่อดู error detail\n";
        echo "- ตรวจดู LogFiles ของ SSRS\n";
    } elseif (strpos($response, 'rsErrorOpeningConnection') !== false) {
        echo "เกิดข้อผิดพลาด: ไม่สามารถเชื่อมต่อ Data Source (rsErrorOpeningConnection)\n";
        echo "ตรวจสอบ:\n";
        echo "- Data Source ตั้ง Stored Credentials แล้วหรือยัง และรหัสผ่านถูกต้อง\n";
        echo "- สิทธิ์ของ account ในฐานข้อมูล (เช่น SELECT)\n";
        echo "- ชื่อเซิร์ฟเวอร์/ฐานข้อมูลตรงตามจริง\n";
        echo "- Firewall/Network เชื่อมต่อได้\n";
    } else {
        echo "ไม่สามารถดึงรายงาน PDF ได้\n";
        echo "HTTP code: $http_code\n";

        // ดึงเฉพาะ meta tag ที่มีประโยชน์ (Message มักบอกชื่อ parameter ที่ผิดตรง ๆ)
        if (preg_match_all('/<meta name="([^"]+)" content="([^"]*)"\s*\/>/', $response, $matches, PREG_SET_ORDER)) {
            echo "SSRS Error Detail:\n";
            foreach ($matches as $m) {
                echo "- {$m[1]}: {$m[2]}\n";
            }
        } else {
            echo "Response (full):\n" . htmlentities($response);
        }
    }
}
