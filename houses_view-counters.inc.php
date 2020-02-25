<?php
/**
 * Houses - Houses View (Counters)
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build counters table
 $counters_table=new strTable(api_text("houses_view-counters-tr-unvalued"));
 $counters_table->addHeader(api_text("cHousesHouseCounter-property-fkCounter"),"nowrap");
 $counters_table->addHeader(api_text("houses_view-counters-th-last_period"),"nowrap");
 $counters_table->addHeader(api_text("houses_view-counters-th-last_current"),"nowrap text-right");
 $counters_table->addHeader(api_text("houses_view-counters-th-last_previous"),"nowrap text-right");
 $counters_table->addHeader(api_text("houses_view-counters-th-last_value"),"nowrap text-right");
 $counters_table->addHeader(api_text("cHousesHouseCounter-property-competence"),"nowrap text-right");
 $counters_table->addHeader(api_text("houses_view-counters-th-last_competence"),"nowrap text-right");
 $counters_table->addHeader("&nbsp;",null,"100%");
 // cycle all counters
 foreach($house_obj->getCounters() as $counter_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"houses_view","tab"=>"counters","act"=>"counter_view","idHouse"=>$house_obj->id,"idCounter"=>$counter_fobj->id]),"fa-info-circle",api_text("table-td-view"));
  $ob->addElement(api_url(["scr"=>"houses_view","tab"=>"counters","act"=>"counter_edit","idHouse"=>$house_obj->id,"idCounter"=>$counter_fobj->id]),"fa-pencil",api_text("table-td-edit"),(api_checkAuthorization("houses-manage")));
  $ob->addElement(api_url(["scr"=>"controller","act"=>"remove","obj"=>"cHousesHouseCounter","idHouse"=>$house_obj->id,"idCounter"=>$counter_fobj->id,"return"=>["scr"=>"houses_view","tab"=>"counters","idHouse"=>$house_obj->id]]),"fa-trash",api_text("table-td-remove"),(api_checkAuthorization("houses-manage")),api_text("cHousesHouseCounter-confirm-remove"));
  // make table row class
  $tr_class_array=array();
  if($counter_fobj->id==$_REQUEST['idCounter']){$tr_class_array[]="currentrow";}
  if($counter_fobj->deleted){$tr_class_array[]="deleted";}
  // make row
  $counters_table->addRow(implode(" ",$tr_class_array));
  $counters_table->addRowField($counter_fobj->getCounter()->getLabel(),"nowrap");
  $counters_table->addRowField(api_period($counter_fobj->getLastMeasurement()->period),"nowrap");
  $counters_table->addRowField(api_number_format($counter_fobj->getLastMeasurement()->current,2,null,false,true),"nowrap text-right");
  $counters_table->addRowField(api_number_format($counter_fobj->getLastMeasurement()->previous,2,null,false,true),"nowrap text-right");
  $counters_table->addRowField(api_number_format($counter_fobj->getLastMeasurement()->value,2,null,false,true),"nowrap text-right");
  $counters_table->addRowField($counter_fobj->competence."%","nowrap text-right");
  $counters_table->addRowField(api_number_format($counter_fobj->getLastMeasurement()->competence,2,null,false,true),"nowrap text-right");
  $counters_table->addRowField($ob->render(),"nowrap text-right");
 }
 // check for view action
 if(ACTION=="counter_view"){
  // get selected counter
  $selected_counter_obj=new cHousesHouseCounter($_REQUEST['idCounter']);
  // build left description lists
  $dl=new strDescriptionList("br","dl-horizontal");
  $dl->addElement(api_text("cHousesHouseCounter-property-fkCounter"),api_tag("strong",$selected_counter_obj->getCounter()->getLabel()));
  $dl->addElement(api_text("cHousesHouseCounter-property-competence"),$selected_counter_obj->competence."%");
  // build measurements table
  $measurements_table=new strTable(api_text("houses_view-counters-measurements-tr-unvalued"));
  $measurements_table->addHeader(api_text("houses_view-counters-measurements-th-period"),null,"100%");
  $measurements_table->addHeader(api_text("houses_view-counters-measurements-th-current"),"nowrap text-right");
  $measurements_table->addHeader(api_text("houses_view-counters-measurements-th-previous"),"nowrap text-right");
  $measurements_table->addHeader(api_text("houses_view-counters-measurements-th-value"),"nowrap text-right");
  $measurements_table->addHeader(api_text("houses_view-counters-measurements-th-competence"),"nowrap text-right");
  // cycle all measurements
  foreach($selected_counter_obj->getCounter()->getMeasurements(12) as $measurement_fobj){ /** @todo aggiungere tasto archivio e limitare a 10 */
   // make counters row
   $measurements_table->addRow();
   $measurements_table->addRowField(api_period($measurement_fobj->period),"truncate-ellipsis");
   $measurements_table->addRowField(api_number_format($measurement_fobj->current,2,null,false,true),"nowrap text-right");
   $measurements_table->addRowField(api_number_format($measurement_fobj->previous,2,null,false,true),"nowrap text-right");
   $measurements_table->addRowField(api_number_format($measurement_fobj->getValue(),2,null,false,true),"nowrap text-right");
   $measurements_table->addRowField(api_number_format($selected_counter_obj->prcCompetence($measurement_fobj->getValue()),2,null,false,true),"nowrap text-right");
  }
  // build modal
  $modal=new strModal(api_text("houses_view-counters-modal-title-view",$house_obj->name),null,"houses_view-counter");
  $modal->setBody($dl->render(1).$measurements_table->render(5));
  // add modal to house
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-counter').modal({show:true});});");
 }
 // check for add or edit action
 if(in_array(ACTION,["counter_add","counter_edit"]) && api_checkAuthorization("houses-manage")){
  // get selected counter
  $selected_counter_obj=new cHousesHouseCounter($_REQUEST['idCounter']);
  // get form
  $form=$selected_counter_obj->form_edit(["return"=>["scr"=>"houses_view","tab"=>"counters","idHouse"=>$house_obj->id]]);
  // replace fkHouse
  $form->removeField("fkHouse");
  $form->addField("hidden","fkHouse",null,$house_obj->id);
  // additional controls
  $form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  if($selected_counter_obj->id){$form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"controller","act"=>"remove","obj"=>"cHousesHouseCounter","idHouse"=>$house_obj->id,"idCounter"=>$selected_counter_obj->id,"return"=>["scr"=>"houses_view","tab"=>"counters","idHouse"=>$house_obj->id]]),"btn-danger",api_text("cHousesHouseCounter-confirm-remove"));}
  // build modal
  $modal=new strModal(api_text("houses_view-counters-modal-title-".($selected_counter_obj->id?"edit":"add"),$house_obj->name),null,"houses_view-counter");
  $modal->setBody($form->render(1));
  // add modal to house
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_houses_view-counter').modal({show:true,backdrop:'static',keyboard:false});});");
 }
?>