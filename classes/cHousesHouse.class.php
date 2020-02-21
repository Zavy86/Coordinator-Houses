<?php
/**
 * Houses - House
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 /**
  * Houses House class
  */
 class cHousesHouse extends cObject{

  /** Parameters */
  static protected $table="houses__houses";
  static protected $logs=true;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $name;
  protected $description;
  protected $street;
  protected $number;
  protected $internal;
  protected $zip;
  protected $town;
  protected $district;
  protected $country;

  /**
   * Decode log properties
   *
   * {@inheritdoc}
   */
  public static function log_decode($event,$properties){
   // make return array
   $return_array=array();
   // rooms events
   if($properties['_obj']=="cHousesHouseRoom"){$return_array[]=api_text($properties['_obj'])." ".$properties['_name'];}
   // counters events
   if($properties['_obj']=="cHousesHouseCounter"){
    $return_array[]=api_text($properties['_obj'])." ".$properties['_name'];
    if($properties['competence']){$return_array[]=api_text("cHousesHouseCounter-property-competence").": ".$properties['competence']['previous']."% &rarr; ".$properties['competence']['current']."%";}
   }

   // users events
   if($properties['class']=="cUser"){$return_array[]=(new cUser($properties['id']))->fullname;}

   /** @todo valutare se usare sempre class o _obj e correggere di conseguenza */

   // return
   return implode(" | ",$return_array);
  }

  /**
   * Get Label
   *
   * @return string House label
   */
  public function getLabel(){
   // make label
   $label=$this->name;
   if($this->description){$label.=" (".$this->description.")";}
   // return
   return $label;
  }

  /**
   * Get Address
   *
   * @param string $format Address format [s street | n number | z zip | t town | d district | c country ]
   * @return string House address
   */
  public function getAddress($format="s, n - z t (d) c"){
   // make address array
   $address_array=array(
    "s"=>$this->street,
    "n"=>$this->number.($this->internal?"/".$this->internal:null),
    "z"=>$this->zip,
    "t"=>$this->town,
    "d"=>$this->district,
    "c"=>$this->country
   );
   // format address
   foreach(str_split($format) as $char){
    if(array_key_exists($char,$address_array)){$address.=$address_array[$char];}
    else{$address.=$char;}
   }
   // return
   return $address;
  }

  /**
   * Get Users
   *
   * @return objects[] Users array sorted by fullname
   */
  public function getUsers(){return api_sortObjectsArray($this->joined_select("houses__houses__join__users","fkHouse","cUser","fkUser"),"fullname");}

  /**
   * Get Rooms
   *
   * @return objects[] Rooms array
   */
  public function getRooms(){return cHousesHouseRoom::availables("`fkHouse`='".$this->id."'");}

  /**
   * Get Counters
   *
   * @return cHousesHouseCounter[] Counters array
   */
  public function getCounters(){return cHousesHouseCounter::availables("`fkHouse`='".$this->id."'");}

  /**
   * Check
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->name))){throw new Exception("House name is mandatory..");}
   if(!strlen(trim($this->street))){throw new Exception("House street is mandatory..");}
   if(!strlen(trim($this->number))){throw new Exception("House number is mandatory..");}
   if(!strlen(trim($this->zip))){throw new Exception("House zip is mandatory..");}
   if(!strlen(trim($this->town))){throw new Exception("House town is mandatory..");}
   if(!strlen(trim($this->district))){throw new Exception("House district is mandatory..");}
   if(!strlen(trim($this->country))){throw new Exception("House country is mandatory..");}
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
   $form=new strForm(api_url(array_merge(["mod"=>"houses","scr"=>"controller","act"=>"store","obj"=>"cHousesHouse","idHouse"=>$this->id],$additional_parameters)),"POST",null,null,"houses_house_edit_form");
   // fields
   $form->addField("text","name",api_text("cHousesHouse-property-name"),$this->name,api_text("cHousesHouse-placeholder-name"),null,null,null,"required");
   $form->addField("textarea","description",api_text("cHousesHouse-property-description"),$this->description,api_text("cHousesHouse-placeholder-description"),null,null,null,"rows='2'");
   $form->addField("separator");
   $form->addField("text","street",api_text("cHousesHouse-property-street"),$this->street,api_text("cHousesHouse-placeholder-street"),null,null,null,"required");
   $form->addField("text","number",api_text("cHousesHouse-property-number"),$this->number,api_text("cHousesHouse-placeholder-number"),null,null,null,"required");
   $form->addField("text","internal",api_text("cHousesHouse-property-internal"),$this->internal,api_text("cHousesHouse-placeholder-internal"));
   $form->addField("text","zip",api_text("cHousesHouse-property-zip"),$this->zip,api_text("cHousesHouse-placeholder-zip"),null,null,null,"required");
   $form->addField("text","town",api_text("cHousesHouse-property-town"),$this->town,api_text("cHousesHouse-placeholder-town"),null,null,null,"required");
   $form->addField("text","district",api_text("cHousesHouse-property-district"),$this->district,api_text("cHousesHouse-placeholder-district"),null,null,null,"required");
   $form->addField("text","country",api_text("cHousesHouse-property-country"),$this->country,api_text("cHousesHouse-placeholder-country"),null,null,null,"required");  /** @todo valutare select */
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * User form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_user(array $additional_parameters=null){
   // build form
   $form=new strForm(api_url(array_merge(["mod"=>"houses","scr"=>"controller","act"=>"user_add","obj"=>"cHousesHouse","idHouse"=>$this->id],$additional_parameters)),"POST",null,null,"houses_house_user_form");
   // fields
   $form->addField("select","idUser",api_text("cHousesHouse-property-idUser"),null,api_text("cHousesHouse-property-idUser-select"),null,null,null,"required");
   foreach(cUser::availables() as $user_fobj){$form->addFieldOption($user_fobj->id,$user_fobj->fullname);}
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * User Add
   *
   * @return boolean
   */
  public function user_add($object){return $this->joined_add("houses__houses__join__users","fkHouse","cUser","fkUser",$object,"user_added");}

  /**
   * User Remove
   *
   * @return boolean
   */
  public function user_remove($object){return $this->joined_remove("houses__houses__join__users","fkHouse","cUser","fkUser",$object,"user_removed");}

  /**
   * Disable remove function
   */
  public function remove(){
    /** @todo check per vedere se è vuoto e permetterne l'eliminazione */
   throw new Exception("Remove function disabled by developer..");
  }

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>