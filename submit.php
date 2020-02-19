<?php
/**
 * Houses - Submit
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // debug
 api_dump($_REQUEST,"_REQUEST");
 // switch action
 switch(ACTION){
  // houses
  case "house_store":house("store");break;
  case "house_delete":house("delete");break;
  case "house_undelete":house("undelete");break;
  case "house_remove":house("remove");break;
  // default
  default:
   api_alerts_add(api_text("alert_submitFunctionNotFound",array(MODULE,SCRIPT,ACTION)),"danger");
   api_redirect("?mod=".MODULE);
 }

 /**
  * House
  *
  * @param string $action Object action
  */
 function house($action){
  // check authorizations
  api_checkAuthorization("houses-manage","dashboard");
  // get object
  $house_obj=new cHousesHouse($_REQUEST['idHouse']);
  api_dump($house_obj,"house object");
  // check object
  if($action!="store" && !$house_obj->id){api_alerts_add(api_text("cHousesHouse-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=houses_list");}
  // execution
  try{
   // switch action
   switch($action){
    case "store":
     $house_obj->store($_REQUEST);
     api_alerts_add(api_text("cHousesHouse-alert-stored"),"success");
     break;
    case "delete":
     $house_obj->delete();
     api_alerts_add(api_text("cHousesHouse-alert-deleted"),"warning");
     break;
    case "undelete":
     $house_obj->undelete();
     api_alerts_add(api_text("cHousesHouse-alert-undeleted"),"warning");
     break;
    case "remove":
     $house_obj->remove();
     api_alerts_add(api_text("cHousesHouse-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(array("scr"=>"houses_list","idHouse"=>$house_obj->id)));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"houses_list","idHouse"=>$house_obj->id]),"cHousesHouse-alert-error");
  }
 }

?>