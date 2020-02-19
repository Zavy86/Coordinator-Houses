<?php
/**
 * Houses - Houses Edit
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("houses-houses_manage","dashboard");
 // get objects
 $house_obj=new cHousesHouse($_REQUEST['idHouse']);
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(($house_obj->id?api_text("houses_edit",$house_obj->name):api_text("houses_edit-add")));
 // get form
 $form=$house_obj->form_edit(["return"=>api_return(["scr"=>"houses_view"])]);
 // additional controls
 if($house_obj->id){
  $form->addControl("button",api_text("form-fc-cancel"),api_return_url(["scr"=>"houses_view","idHouse"=>$house_obj->id]));
  if(!$house_obj->deleted){
   $form->addControl("button",api_text("form-fc-delete"),api_url(["scr"=>"submit","act"=>"house_delete","idHouse"=>$house_obj->id]),"btn-danger",api_text("cHousesHouse-confirm-delete"));
  }else{
   $form->addControl("button",api_text("form-fc-undelete"),api_url(["scr"=>"submit","act"=>"house_undelete","idHouse"=>$house_obj->id,"return"=>["scr"=>"houses_view"]]),"btn-warning");
   $form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"submit","act"=>"house_remove","idHouse"=>$house_obj->id]),"btn-danger",api_text("cHousesHouse-confirm-remove"));
  }
 }else{$form->addControl("button",api_text("form-fc-cancel"),api_url(["scr"=>"houses_list"]));}
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($form->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 api_dump($house_obj,"house");
?>