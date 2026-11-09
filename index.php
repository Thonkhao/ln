<?php

##### Import Controller Class from .. 
require_once "controller/template.controller.php";
## Controller User
##require_once "controller/user/tkuser_requested.controller.php";
##require_once "controller/user/tkuser_dashboard.controller.php";
##require_once "controller/user/tkuser_dtl.controller.php";

## Controller IT
##require_once "controller/it/tkit_dashboard.controller.php";
##require_once "controller/it/tkit_dtl.controller.php";
##require_once "controller/it/tkit_chatmonitor.controller.php";
## Controller Master
require_once "controller/master/tk_reason.controller.php";
require_once "controller/master/tk_type.controller.php";
require_once "controller/master/tk_group.controller.php";
require_once "controller/master/tk_manageuseremail.controller.php";
require_once "controller/master/wnv_authicon.controller.php";
require_once "controller/master/wnv_authmenu.controller.php";
require_once "controller/master/wnv_authgroup.controller.php";
require_once "controller/master/wnv_authgroupmenu.controller.php";
require_once "controller/master/wnv_authuseremailgroup.controller.php";
## Controller tracking
require_once "controller/tracking/ordertracking.controller.php";
## Controller Marketing
require_once "controller/marketing/pccustomer.controller.php";

##### Import Model Class from .. 
require_once "Models/minio.model.php";
## Model Master
require_once "Models/master/tk_reason.model.php";
require_once "Models/master/tk_type.model.php";
require_once "Models/master/tk_group.model.php";
require_once "Models/master/tk_manageuseremail.model.php";
require_once "Models/master/wnv_authicon.model.php";
require_once "Models/master/wnv_authmenu.model.php";
require_once "Models/master/wnv_authgroup.model.php";
require_once "Models/master/wnv_authgroupmenu.model.php";
require_once "Models/master/wnv_authuseremailgroup.model.php";
## Model tracking
require_once "models/tracking/ordertracking.model.php";
## Model Marketing
require_once "Models/marketing/pccustomer.model.php";

##### Import Model Class from ..    
require_once __DIR__ . '/helpers/ui_helpers.php';



require 'dbconnect/Connect.php';

##### Create Class instance Template
$template = new TemplateController();   // Create Template instance
$template->ctrTemplate(); // Call method ctrTemplate() in TemplateController class
