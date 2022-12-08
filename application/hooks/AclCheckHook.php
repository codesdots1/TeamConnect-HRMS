<?php
/**
 * Created by PhpStorm.
 * User: vashi
 * Date: 12-01-2016
 * Time: 12:57 AM
 */
class AclCheckHook
{
    var $ci;

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function index()
    {
        $callingClass = $this->ci->router->fetch_class();
        $callingMethod  = $this->ci->router->fetch_method();

        $requiredPermission = $callingClass."|".$callingMethod;

//        echo $requiredPermission; die();

        $accessStatus = $this->ci->dt_ci_acl->checkAccess($requiredPermission);

        if(!$accessStatus){
            return show_error(NO_ACCESS_SECTION);
        }
//        die('hi');
    }
}

