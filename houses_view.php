<?php
/**
 * Houses - Houses View
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("houses-usage","dashboard");
 // get objects
 $house_obj=new cHousesHouse($_REQUEST['idHouse']);
 // check objects
 if(!$house_obj->id){api_alerts_add(api_text("cHousesHouse-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=houses_list");}
 // deleted alert
 if($house_obj->deleted){api_alerts_add(api_text("cHousesHouse-warning-deleted"),"warning");}
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(api_text("houses_view",$house_obj->name));
 // check for tab
 if(!defined(TAB)){define("TAB","informations");}
 // build left description lists
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement(api_text("cHousesHouse-property-name"),api_tag("strong",$house_obj->name));
 if($house_obj->description){$dl->addElement(api_text("cHousesHouse-property-description"),nl2br($house_obj->description));}
 // include tabs
 require_once(MODULE_PATH."houses_view-informations.inc.php");
 require_once(MODULE_PATH."houses_view-rooms.inc.php");
 require_once(MODULE_PATH."houses_view-counters.inc.php");
 require_once(MODULE_PATH."houses_view-users.inc.php");
 $tab=new strTab();
 $tab->addItem(api_icon("fa-flag-o")." ".api_text("houses_view-tab-informations"),$informations_dl->render(),("informations"==TAB?"active":null));
 $tab->addItem(api_icon("fa-clone")." ".api_text("houses_view-tab-rooms"),$rooms_table->render(),("rooms"==TAB?"active":null));
 $tab->addItem(api_icon("fa-line-chart")." ".api_text("houses_view-tab-counters"),$counters_table->render(),("counters"==TAB?"active":null));
 $tab->addItem(api_icon("fa-user-o")." ".api_text("houses_view-tab-users"),$users_table->render(),("users"==TAB?"active":null));
 $tab->addItem(api_icon("fa-file-text-o")." ".api_text("houses_view-tab-logs"),api_logs_table($house_obj->getLogs((!$_REQUEST['all_logs']?10:null)))->render(),("logs"==TAB?"active":null));
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($dl->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($tab->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 api_dump($house_obj,"house object");
 if($selected_development_obj->id){api_dump($selected_room_obj,"selected room object");}
 if($selected_counter_obj->id){api_dump($selected_counter_obj,"selected counter object");}
 if($selected_user_obj->id){api_dump($selected_user_obj,"selected user object");}
?>