<?php
/**
 * Houses - Houses View (Users)
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build table
 $users_table=new strTable(api_text("houses_view-users-tr-unvalued"));
 $users_table->addHeader(api_text("houses_view-users-th-user"),null,"100%");
 $users_table->addHeader("&nbsp;",null,16);
 // cycle all users
 foreach($house_obj->getUsers() as $user_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"houses_view","tab"=>"users","act"=>"user_view","idHouse"=>$house_obj->id,"idUser"=>$user_fobj->id]),"fa-info-circle",api_text("table-td-view"));
  $ob->addElement(api_url(["scr"=>"controller","act"=>"user_remove","obj"=>"cHousesHouse","idHouse"=>$house_obj->id,"idUser"=>$user_fobj->id,"return"=>["scr"=>"houses_view","tab"=>"users"]]),"fa-trash",api_text("table-td-remove"),(api_checkAuthorization("houses-houses_manage")),api_text("cHousesHouse-confirm-user_remove"));
  // make table row class
  $tr_class_array=array();
  if($user_fobj->id==$_REQUEST['idUser']){$tr_class_array[]="currentrow";}
  // make project row
  $users_table->addRow(implode(" ",$tr_class_array));
  $users_table->addRowField($user_fobj->fullname,"truncate-ellipsis");
  $users_table->addRowField($ob->render(),"text-right");
 }
 // check view action
 if(ACTION=="user_view" && $_REQUEST['idUser']){
  // get selected user
  $selected_user_obj=new cUser($_REQUEST['idUser']);
  // build user description list
  $selected_user_dl=new strDescriptionList("br","dl-horizontal");
  $selected_user_dl->addElement(api_text("houses_view-users-modal-dt-fullname"),api_tag("strong",$selected_user_obj->fullname));
  // build user view modal window
  $user_modal=new strModal(api_text("houses_view-users-modal-title-view",$house_obj->name),null,"houses_view-user");
  $user_modal->setBody($selected_user_dl->render());
  // add modal to application
  $app->addModal($user_modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-user').modal('show');});");
 }
 // check add action
 if(ACTION=="user_add"){
  // get form
  $form=$house_obj->form_user(["return"=>["scr"=>"houses_view","tab"=>"users"]]);
  // additional controls
  $form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  // build modal
  $modal=new strModal(api_text("houses_view-users-modal-title-add",$house_obj->name),null,"houses_view-user");
  $modal->setBody($form->render());
  // add modal to application
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-user').modal({show:true,backdrop:'static',keyboard:false});});");
 }

?>