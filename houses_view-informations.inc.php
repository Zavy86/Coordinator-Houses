<?php
/**
 * Houses - Houses View (Informations)
 *
 * @package Coordinator\Modules\Houses
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // build informations description list
 $informations_dl=new strDescriptionList("br","dl-horizontal");
 $informations_dl->addElement(api_text("houses_view-informations-dt-address"),$house_obj->getAddress());

 ?>