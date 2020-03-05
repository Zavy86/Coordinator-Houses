<?php
/**
 * Houses - House Counter
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 /**
  * Houses House Counter class
  */
 class cHousesHouseCounter extends cObject{

  /** Parameters */
  static protected $table="houses__houses__counters";
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkHouse;
  protected $fkCounter;
  protected $competence;

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkHouse))){throw new Exception("Counter house key is mandatory..");}
   if(!strlen(trim($this->fkCounter))){throw new Exception("Counter counter key is mandatory..");}
   if(!strlen(trim($this->competence))){throw new Exception("Counter competence value is mandatory..");}
   // return
   return true;
  }

  /**
   * Process Competence
   *
   * @param double $value Counter measurement value
   * @return boolean|false Competence value or false
   */
  public function prcCompetence($value){
   // check parameters
   if(!$value){return false;}
   // return
   return $value*$this->competence/100;
  }

  /**
   * Get House
   *
   * @return object
   */
  public function getHouse(){return new cHousesHouse($this->fkHouse);}

  /**
   * Get Counter
   *
   * @return object
   */
  public function getCounter(){return new cCountersCounter($this->fkCounter);}

  /**
   * Get Last Measurement
   *
   * @return object[]|false Last measurements objects or false
   */
  public function getLastMeasurement(){
   // get last counter measurement
   $last_measurement=$this->getCounter()->getLastMeasurement();
   // make measurement
   $measurement=new stdClass();
   $measurement->period=$last_measurement->period;
   $measurement->current=$last_measurement->current;
   $measurement->previous=$last_measurement->previous;
   $measurement->value=($last_measurement->current?$last_measurement->current-$last_measurement->previous:null);
   $measurement->competence=$this->prcCompetence($measurement->value);
   // return
   return $measurement;
  }

  /**
   * Edit form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_edit(array $additional_parameters=null){
   // build form
   $form=new strForm(api_url(array_merge(["mod"=>"houses","scr"=>"controller","act"=>"store","obj"=>"cHousesHouseCounter","idCounter"=>$this->id],$additional_parameters)),"POST",null,null,"houses_house_counter_edit_form");
   // fields
   $form->addField("select","fkHouse",api_text("cHousesHouseCounter-property-fkHouse"),$this->fkHouse,api_text("cHousesHouseCounter-placeholder-fkHouse"),null,null,null,"required");
   foreach(cHousesHouse::availables(true) as $house_fobj){$form->addFieldOption($house_fobj->id,$house_fobj->name);}
   $form->addField("select","fkCounter",api_text("cHousesHouseCounter-property-fkCounter"),$this->fkCounter,api_text("cHousesHouseCounter-placeholder-fkCounter"),null,null,null,"required");
   foreach(cCountersCounter::availables(true) as $counter_fobj){$form->addFieldOption($counter_fobj->id,$counter_fobj->getLabel());}
   $form->addField("number","competence",api_text("cHousesHouseCounter-property-competence"),($this->competence?$this->competence:100),api_text("cHousesHouseCounter-placeholder-competence"),null,null,null,"required min=1 max=100");
   $form->addFieldAddon("%");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  // debug
  protected function event_triggered($event){
   //api_dump($event,static::class." event triggered");
   // skip trace events
   if($event->typology=="trace"){return;}
   // log event to house
   $this->getHouse()->event_log($event->typology,$event->action,array_merge(["_obj"=>"cHousesHouseCounter","_id"=>$this->id,"_name"=>$this->getCounter()->getLabel()],$event->properties));
  }

 }

?>