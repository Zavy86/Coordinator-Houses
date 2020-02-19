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
  * Edit form
  *
  * @param string[] $additional_parameters Array of url additional parameters
  * @return object Form structure
  */
 public function form_edit(array $additional_parameters=null){
  // build form
  $form=new strForm("?mod=houses&scr=submit&act=house_store&idHouse=".$this->id."&".http_build_query($additional_parameters),"POST",null,null,"houses_houses_form_edit");
  // fields
  $form->addField("text","name",api_text("cHousesHouse-ff-name"),$this->name,api_text("cHousesHouse-ff-name-placeholder"),null,null,null,"required");
  $form->addField("textarea","description",api_text("cHousesHouse-ff-description"),$this->description,api_text("cHousesHouse-ff-description-placeholder"),null,null,null,"rows='2'");
  $form->addField("separator");
  $form->addField("text","street",api_text("cHousesHouse-ff-street"),$this->street,api_text("cHousesHouse-ff-street-placeholder"),null,null,null,"required");
  $form->addField("text","number",api_text("cHousesHouse-ff-number"),$this->number,api_text("cHousesHouse-ff-number-placeholder"));
  $form->addField("text","internal",api_text("cHousesHouse-ff-internal"),$this->internal,api_text("cHousesHouse-ff-internal-placeholder"));
  $form->addField("text","zip",api_text("cHousesHouse-ff-zip"),$this->zip,api_text("cHousesHouse-ff-zip-placeholder",null,null,null,"required"));
  $form->addField("text","town",api_text("cHousesHouse-ff-town"),$this->town,api_text("cHousesHouse-ff-town-placeholder",null,null,null,"required"));
  $form->addField("text","district",api_text("cHousesHouse-ff-district"),$this->district,api_text("cHousesHouse-ff-district-placeholder",null,null,null,"required"));
  $form->addField("text","country",api_text("cHousesHouse-ff-country"),$this->country,api_text("cHousesHouse-ff-country-placeholder",null,null,null,"required"));  /** @todo valutare select */
  // controls
  $form->addControl("submit",api_text("form-fc-submit"));
  // return
  return $form;
 }

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