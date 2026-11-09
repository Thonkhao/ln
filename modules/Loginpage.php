<!doctype html>
<html lang="en">
<!--begin::Head-->
<?php include "theme/Header.php"; ?>
<link rel="stylesheet" type="text/css" href="theme/style.css">
<style>
.login-page {
    min-height: 90vh;
}
</style>
<!--end::Head-->
<!--begin::Body-->

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo">
            <img src="pic/hatari-icon.svg" alt="..." class="img-fluid">
        </div>
        <div class="text-center">
            <img src="pic/happiness.svg" alt="..." class="img-fluid">
        </div>
        <!-- /.login-logo -->
        <div class="">
            <div class="">
                <p class="login-box-msg"></p>
                <div class="social-auth-links text-center mb-3 d-grid">
                    <!-- <p>- OR -</p> -->
                    <?php
            //require 'dbconnect/vendor/autoload.php';
			require_once 'C:\\inetpub\\config\\vendor\\autoload.php';
	        $envPath = 'C:\\inetpub\\config\\ln_management';
            $dotenv = Dotenv\Dotenv::createImmutable($envPath);
            $dotenv->load();
            $TENANT_ID = $_ENV['MS_TENANT_ID'];
            $clientId = $_ENV['MS_CLIENT_ID'];
            $redirectUri = $_ENV['MS_REDIRECT_URI'];
            $authUrl = "https://login.microsoftonline.com/$TENANT_ID/oauth2/v2.0/authorize";
            $params = array(
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri,
                "response_type" => "code",
                "scope" => "openid profile User.Read email"
            );
            $authUrl .= "?" . http_build_query($params);
            ?>

                    <a href="<?= $authUrl;?>" class="btn btn-custom ">
                        <img src="./pic/outlook-logo-W.svg" class="brand-image me-2"
                            style="width: 20px; height: 20px;" />
                        Sign in using Outlook
                    </a>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <?php include "theme/script.php"; ?>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
</body>
<!--end::Body-->

</html>