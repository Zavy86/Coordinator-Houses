<?php
/**
 * Houses - Template
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build application
 $app=new strApplication();
 // build nav object
 $nav=new strNav("nav-tabs");
 $nav->setTitle(api_text(MODULE));
 // dashboard
 $nav->addItem(api_icon("fa-th-large",null,"hidden-link"),api_url(["scr"=>"dashboard"]));
 // houses
 if(api_script_prefix()=="houses"){
  $nav->addItem(api_text("nav-houses-list"),api_url(["scr"=>"houses_list"]));
  // operations
  if($house_obj->id && in_array(SCRIPT,array("houses_view","houses_edit"))){
   $nav->addItem(api_text("nav-operations"),null,null,"active");
   $nav->addSubItem(api_text("nav-houses-operations-edit"),api_url(["scr"=>"houses_edit","idHouse"=>$house_obj->id]),(api_checkAuthorization("houses-manage")));
   $nav->addSubSeparator();
   $nav->addSubItem(api_text("nav-houses-operations-room_add"),api_url(["scr"=>"houses_view","tab"=>"rooms","act"=>"room_add","idHouse"=>$house_obj->id]),(api_checkAuthorization("houses-manage")));
   $nav->addSubItem(api_text("nav-houses-operations-counter_add"),api_url(["scr"=>"houses_view","tab"=>"counters","act"=>"counter_add","idHouse"=>$house_obj->id]),(api_checkAuthorization("houses-manage")));
   $nav->addSubItem(api_text("nav-houses-operations-user_add"),api_url(["scr"=>"houses_view","tab"=>"users","act"=>"user_add","idHouse"=>$house_obj->id]),(api_checkAuthorization("houses-manage")));
  }else{
   $nav->addItem(api_text("nav-houses-add"),api_url(["scr"=>"houses_edit"]),(api_checkAuthorization("houses-manage")));
  }
 }
 // add nav to html
 $app->addContent($nav->render(false));
?>