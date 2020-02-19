<?php
/**
 * Houses - Controller
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // debug
 api_dump($_REQUEST,"_REQUEST");
 // check if object controller function exists
 if(function_exists($_REQUEST['obj']."_controller")){
  // call object controller function
  call_user_func($_REQUEST['obj']."_controller",$_REQUEST['act']);
 }else{
  api_alerts_add(api_text("alert_controllerObjectNotFound",[MODULE,$_REQUEST['obj']."_controller"]),"danger");
  api_redirect("?mod=".MODULE);
 }

 /**
  * House controller
  *
  * @param string $action Object action
  */
 function cHousesHouse_controller($action){
  // check authorizations
  api_checkAuthorization("houses-houses_manage","dashboard");
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

 /**
  * House Room controller
  *
  * @param string $action Object action
  */
 function cHousesHouseRoom_controller($action){
  // check authorizations
  api_checkAuthorization("houses-houses_manage","dashboard");
  // get object
  $room_obj=new cHousesHouseRoom($_REQUEST['idRoom']);
  api_dump($room_obj,"room object");
  // check object
  if($action!="store" && !$room_obj->id){api_alerts_add(api_text("cHousesHouseRoom-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=houses_list");}
  // execution
  try{
   switch($action){
    case "store":
     $room_obj->store($_REQUEST);
     api_alerts_add(api_text("cHousesHouseRoom-alert-stored"),"success");
     break;
    case "delete":
     $room_obj->delete();
     api_alerts_add(api_text("cHousesHouseRoom-alert-deleted"),"warning");
     break;
    case "undelete":
     $room_obj->undelete();
     api_alerts_add(api_text("cHousesHouseRoom-alert-undeleted"),"warning");
     break;
    case "remove":
     $room_obj->remove();
     api_alerts_add(api_text("cHousesHouseRoom-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Room action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"rooms_list","idRoom"=>$room_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"rooms_list","idRoom"=>$room_obj->id]),"cHousesHouseRoom-alert-error");
  }
 }

?>