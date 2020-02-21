<?php
/**
 * Houses - House Room
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 /**
  * Houses House Room class
  */
 class cHousesHouseRoom extends cObject{

  /** Parameters */
  static protected $table="houses__houses__rooms";
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkHouse;
  protected $name;
  protected $description;

  /**
   * Get House
   *
   * @return object
   */
  public function getHouse(){return new cHousesHouse($this->fkHouse);}

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkHouse))){throw new Exception("Room house key is mandatory..");}
   if(!strlen(trim($this->name))){throw new Exception("Room name is mandatory..");}
   // return
   return true;
  }

  /**
   * Edit form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_edit(array $additional_parameters=null){
   // build form
   $form=new strForm(api_url(array_merge(["mod"=>"houses","scr"=>"controller","act"=>"store","obj"=>"cHousesHouseRoom","idRoom"=>$this->id],$additional_parameters)),"POST",null,null,"houses_room_edit_form");
   // fields
   $form->addField("select","fkHouse",api_text("cHousesHouseRoom-property-fkHouse"),$this->fkHouse,api_text("cHousesHouseRoom-placeholder-fkHouse"),null,null,null,"required");
   foreach(cHousesHouse::availables(true) as $house_fobj){$form->addFieldOption($house_fobj->id,$house_fobj->name);}
   $form->addField("text","name",api_text("cHousesHouseRoom-property-name"),$this->name,api_text("cHousesHouseRoom-placeholder-name"),null,null,null,"required");
   $form->addField("textarea","description",api_text("cHousesHouseRoom-property-description"),$this->description,api_text("cHousesHouseRoom-placeholder-description"),null,null,null,"rows='2'");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  // Disable remove function
  public function remove(){throw new Exception("Room remove function disabled by developer..");}

  // debug
  protected function event_triggered($event){
   //api_dump($event,static::class." event triggered");
   // skip trace events
   if($event->typology=="trace"){return;}
   // log event to house
   $this->getHouse()->event_log($event->typology,$event->action,array_merge(["_obj"=>"cHousesHouseRoom","_id"=>$this->id,"_name"=>$this->name],$event->properties));
  }

 }

?>