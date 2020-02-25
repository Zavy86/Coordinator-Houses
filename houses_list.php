<?php
/**
 * Houses - Houses List
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("houses-usage","dashboard");
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // definitions
 $users_array=array();
 // set application title
 $app->setTitle(api_text("houses_list"));
 // definitions
 $houses_array=array();
 // build filter
 $filter=new strFilter();
 $filter->addSearch(["name","description"]);
 // build query object
 $query=new cQuery("houses__houses",$filter->getQueryWhere());
 $query->addQueryOrderField("name");
 // build pagination object
 $pagination=new strPagination($query->getRecordsCount());
 // cycle all results
 foreach($query->getRecords($pagination->getQueryLimits()) as $result_f){$houses_array[$result_f->id]=new cHousesHouse($result_f);}
 // build table
 $table=new strTable(api_text("houses_list-tr-unvalued"));
 $table->addHeader($filter->link(api_icon("fa-filter",api_text("filters-modal-link"),"hidden-link")),"text-center",16);
 $table->addHeader(api_text("houses_list-th-name"),"nowrap");
 $table->addHeader(api_text("houses_list-th-address"),null,"100%");
 $table->addHeader("&nbsp;",null,16);
 // cycle all houses
 foreach($houses_array as $house_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"houses_edit","idHouse"=>$house_fobj->id,"return"=>["scr"=>"houses_list"]]),"fa-pencil",api_text("table-td-edit"),(api_checkAuthorization("houses-manage")));
  if($house_fobj->deleted){$ob->addElement(api_url(["scr"=>"controller","act"=>"undelete","obj"=>"cHousesHouse","idHouse"=>$house_fobj->id,"return"=>["scr"=>"houses_list"]]),"fa-trash-o",api_text("table-td-undelete"),(api_checkAuthorization("houses-manage")),api_text("cHousesHouse-confirm-undelete"));}
  else{$ob->addElement(api_url(["scr"=>"controller","act"=>"delete","obj"=>"cHousesHouse","idHouse"=>$house_fobj->id,"return"=>["scr"=>"houses_list"]]),"fa-trash",api_text("table-td-delete"),(api_checkAuthorization("houses-manage")),api_text("cHousesHouse-confirm-delete"));}
  // make table row class
  $tr_class_array=array();
  if($house_fobj->id==$_REQUEST['idHouse']){$tr_class_array[]="currentrow";}
  if($house_fobj->deleted){$tr_class_array[]="deleted";}
  // make house row
  $table->addRow(implode(" ",$tr_class_array));
  $table->addRowFieldAction(api_url(["scr"=>"houses_view","idHouse"=>$house_fobj->id]),"fa-search",api_text("table-td-view"));
  $table->addRowField($house_fobj->name,"nowrap");
  $table->addRowField($house_fobj->getAddress(),"truncate-ellipsis");
  $table->addRowField($ob->render(),"text-right");
 }
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($filter->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($table->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($pagination->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 if($selected_house_obj->id){api_dump($selected_house_obj,"selected house");}
 api_dump($query,"query");
?>