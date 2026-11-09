<?php
require '../../dbconnect/Connect.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$envPath = 'C:\\inetpub\\config\\ln_management';
$dotenv = Dotenv\Dotenv::createImmutable($envPath);
$dotenv->load();

session_start();

$TENANT_ID = $_ENV['MS_TENANT_ID'];

$clientId = $_ENV['MS_CLIENT_ID'];
$clientSecret = $_ENV['MS_CLIENT_SECRET']; //สร้าง Client Secret ใน Azure AD
$redirectUri = $_ENV['MS_REDIRECT_URI'];
$tokenUrl = "https://login.microsoftonline.com/$TENANT_ID/oauth2/v2.0/token";
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $params = array(
        "client_id" => $clientId,
        "client_secret" => $clientSecret,
        "code" => $code,
        "redirect_uri" => $redirectUri,
        "grant_type" => "authorization_code"
    );
    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $token = json_decode($response, true);
    if (isset($token['access_token'])) {
        $accessToken = $token['access_token'];
        $graphUrl = "https://graph.microsoft.com/v1.0/me";
        $ch = curl_init($graphUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($response, true);
        $mail = $user['mail'];
        $_SESSION['mail'] = $user['mail'];
        $_SESSION['user'] = $user['displayName'];
        try {
            $conn = DBConnection::getConnection(); // เรียกใช้จาก class DBConnection
            $sql = "SELECT TOP 1 [UserID],[User_Email],[EmpID],[short_name],[Color_Code],[Dept_Code],[Image_Profile]
            FROM [dbo].[WNV_UserEmail] 
            where [User_Email]=:User_Email and [Is_Active]='Y' and [Type_Code]='M' and [Flag_Del]='N' 
            order by [UserID] DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':User_Email', $mail, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] [IP: " . $_SERVER['REMOTE_ADDR'] . "] " . $e->getMessage());
            http_response_code(500);
            return [];
        }
        $_SESSION['uid'] = $result['UserID'];
        $_SESSION['short_name'] = $result['short_name'];
        $_SESSION['Color_Code'] = $result['Color_Code'];
        $_SESSION['Dept_Code'] = $result['Dept_Code'];

        // Ensure session started for CSRF
        if (session_status() === PHP_SESSION_NONE) session_start();
        $csrfExpire = 1800; // 30 นาที
        if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_expire']) || time() > $_SESSION['csrf_token_expire']) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_expire'] = time() + $csrfExpire;
        }

        if ($result['Image_Profile'] === '' || $result['Image_Profile'] === null) {
            $_SESSION['Img_profile'] =  '';
        } else {
            $_SESSION['Img_profile'] =  base64_encode($result['Image_Profile']);
        }
       // if ($result['Dept_Code'] == '25') {
       //     header("location:../../?s=it&r=tkit_monitoring");
       // } else {
            header("location:../../?r=Home");
       // }
    } else {
        echo "Error: " . $token['error_description'];
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
