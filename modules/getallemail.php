<?php
require '../../dbconnect/vendor/autoload.php';
require '../../dbconnect/Connect.php';

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
$envPath = 'C:\\inetpub\\config\\ln_management';
$dotenv = Dotenv\Dotenv::createImmutable($envPath);
$dotenv->load();

session_start();

$TENANT_ID = $_ENV['MS_TENANT_ID'];
$clientId = $_ENV['MS_CLIENT_ID'];
$clientSecret = $_ENV['MS_CLIENT_SECRET']; //สร้าง Client Secret ใน Azure AD


// สร้าง Graph client
$guzzle = new \GuzzleHttp\Client();
$tokenUrl = "https://login.microsoftonline.com/$TENANT_ID/oauth2/v2.0/token";
$response = $guzzle->post($tokenUrl, [
    'form_params' => [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'scope' => 'https://graph.microsoft.com/.default',
        'grant_type' => 'client_credentials',
    ],
]);

$token = json_decode($response->getBody()->getContents());
$accessToken = $token->access_token;

$graph = new Graph();
$graph->setAccessToken($accessToken);

$nextLink = '/users?$select=displayName,givenName,id,mail,surname,userPrincipalName,accountEnabled,userType';
$conn = DBConnection::getConnection(); // เรียกใช้จาก class DBConnection
$quert = 'DELETE FROM WNV_UserEmail_Temp';
$stmt = $conn->prepare($quert);
$stmt->execute();

while ($nextLink) {
    $response = $graph->createRequest('GET', $nextLink)
        ->execute();

    $users = $response->getBody();
    // print_r($users);
    if (isset($users['value']) && is_array($users['value'])) {
        foreach ($users['value'] as $user) {
            $U_Email = htmlspecialchars($user['mail']);
            $Enableds = ($user['accountEnabled'] ? 'Y' : 'N');
            $UserType = htmlspecialchars($user['userType']);
            $FirstName = htmlspecialchars($user['givenName'] ?? '');
            if ($UserType == 'Member') {
                $UserType = 'M';
            } else {
                $UserType = 'G';
            }
            if ($U_Email != '') {
                if ($UserType == 'M') {
                    echo $U_Email . '--------------' . $FirstName . '--------------' . $Enableds . '--------------' . $UserType . '<br>';
                    $quert = "INSERT INTO WNV_UserEmail_Temp (User_Email,F_Name,Is_Active,Type_Code,Flag_Load)
                              VALUES (:U_Email,:FirstName,:Enableds,:UserType,'N')";
                    $stmt = $conn->prepare($quert);
                    $stmt->bindParam(':U_Email', $U_Email, PDO::PARAM_STR);
                    $stmt->bindParam(':FirstName', $FirstName, PDO::PARAM_STR);
                    $stmt->bindParam(':Enableds', $Enableds, PDO::PARAM_STR);
                    $stmt->bindParam(':UserType', $UserType, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }

        $nextLink = $users['@odata.nextLink'] ?? null;
    } else {
        $nextLink = null;
    }
}


$SyncEmail = 'EXEC [dbo].[WNV_UserEmailScheSp]';
$stmtSyncEmail = $conn->prepare($SyncEmail);
$stmtSyncEmail->execute();


echo "<script>window.close();</script>";
