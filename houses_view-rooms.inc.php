<?php
/**
 * Houses - Houses View (Rooms)
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build rooms table
 $rooms_table=new strTable(api_text("houses_view-rooms-tr-unvalued"));
 $rooms_table->addHeader(api_text("cHousesHouseRoom-property-name"),"nowrap");
 $rooms_table->addHeader(api_text("cHousesHouseRoom-property-description"),null,"100%");
 $rooms_table->addHeader("&nbsp;",null,16);
 // cycle all rooms
 foreach($house_obj->getRooms() as $room_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"houses_view","tab"=>"rooms","act"=>"room_view","idHouse"=>$house_obj->id,"idRoom"=>$room_fobj->id]),"fa-info-circle",api_text("table-td-view"));
  $ob->addElement(api_url(["scr"=>"houses_view","tab"=>"rooms","act"=>"room_edit","idHouse"=>$house_obj->id,"idRoom"=>$room_fobj->id]),"fa-pencil",api_text("table-td-edit"),(api_checkAuthorization("houses-houses_manage")));
  //$ob->addElement(api_url(["scr"=>"controller","act"=>"remove","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$selected_room_obj->id,"return"=>["scr"=>"houses_view","idHouse"=>$house_obj->id]]),"fa-trash",api_text("table-td-remove"),(api_checkAuthorization("houses-houses_manage")),api_text("cHousesHouseRoom-confirm-remove"));
  if($room_fobj->deleted){$ob->addElement(api_url(["scr"=>"controller","act"=>"undelete","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$room_fobj->id,"return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]),"fa-trash-o",api_text("table-td-undelete"),(api_checkAuthorization("houses-houses_manage")),api_text("cHousesHouseRoom-confirm-undelete"));}
  else{$ob->addElement(api_url(["scr"=>"controller","act"=>"delete","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$room_fobj->id,"return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]),"fa-trash",api_text("table-td-delete"),(api_checkAuthorization("houses-houses_manage")),api_text("cHousesHouseRoom-confirm-delete"));}
  // make table row class
  $tr_class_array=array();
  if($room_fobj->id==$_REQUEST['idRoom']){$tr_class_array[]="currentrow";}
  if($room_fobj->deleted){$tr_class_array[]="deleted";}
  // make row
  $rooms_table->addRow(implode(" ",$tr_class_array));
  $rooms_table->addRowField($room_fobj->name,"nowrap");
  $rooms_table->addRowField($room_fobj->description,"truncate-ellipsis");
  $rooms_table->addRowField($ob->render(),"nowrap text-right");
 }
 // check for view action
 if(ACTION=="room_view"){
  // get selected room
  $selected_room_obj=new cHousesHouseRoom($_REQUEST['idRoom']);
  // build left description lists
  $dl=new strDescriptionList("br","dl-horizontal");
  $dl->addElement(api_text("cHousesHouseRoom-property-name"),api_tag("strong",$selected_room_obj->name));
  if($selected_room_obj->description){$dl->addElement(api_text("cHousesHouseRoom-property-description"),nl2br($selected_room_obj->description));}
  // build modal
  $modal=new strModal(api_text("houses_view-rooms-modal-title-view",$house_obj->name),null,"houses_view-room");
  $modal->setBody($dl->render(1));
  // add modal to house
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-room').modal({show:true});});");
 }
 // check for add or edit action
 if(in_array(ACTION,["room_add","room_edit"]) && api_checkAuthorization("houses-houses_manage")){
  // get selected room
  $selected_room_obj=new cHousesHouseRoom($_REQUEST['idRoom']);
  // get form
  $form=$selected_room_obj->form_edit(["return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]);
  // replace fkHouse
  $form->removeField("fkHouse");
  $form->addField("hidden","fkHouse",null,$house_obj->id);
  // additional controls
  $form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  //if($selected_room_obj->id){$form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"controller","act"=>"remove","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$selected_room_obj->id,"return"=>["scr"=>"houses_view","idHouse"=>$house_obj->id]]),"btn-danger",api_text("cHousesHouseRoom-confirm-remove"));}
  if($selected_room_obj->id){
   if(!$selected_room_obj->deleted){
    $form->addControl("button",api_text("form-fc-delete"),api_url(["scr"=>"controller","act"=>"delete","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$selected_room_obj->id,"return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]),"btn-danger",api_text("cHousesHouseRoom-confirm-delete"));
   }else{
    $form->addControl("button",api_text("form-fc-undelete"),api_url(["scr"=>"controller","act"=>"undelete","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$selected_room_obj->id,"return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]),"btn-warning");
    $form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"controller","act"=>"remove","obj"=>"cHousesHouseRoom","idHouse"=>$house_obj->id,"idRoom"=>$selected_room_obj->id,"return"=>["scr"=>"houses_view","tab"=>"rooms","idHouse"=>$house_obj->id]]),"btn-danger",api_text("cHousesHouseRoom-confirm-remove"));
   }
  }
  // build modal
  $modal=new strModal(api_text("houses_view-rooms-modal-title-".($selected_room_obj->id?"edit":"add"),$house_obj->name),null,"houses_view-room");
  $modal->setBody($form->render(1));
  // add modal to house
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-room').modal({show:true,backdrop:'static',keyboard:false});});");
 }
?>