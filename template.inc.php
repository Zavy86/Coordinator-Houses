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
 // add nav to html
 $app->addContent($nav->render(false));
?>