<?php
/* sample menu 
$config["side_menu"] = array(
                array("menu_title"=>"Dashboard","link"=>"","menu_icon"=>"fa fa-dashboard","badge"=>""),
                array("menu_title"=>"Layout Option","link"=>"","menu_icon"=>"fa fa-files-o","badge"=>"4","sub_menu"=>
                    array(array("menu_title"=>"Dashboard","link"=>"","menu_icon"=>"fa fa-dashboard","badge"=>""),
                    array("menu_title"=>"Dashboard","link"=>"","menu_icon"=>"fa fa-dashboard","badge"=>""))
                ),
);
*/

$config["days"] = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
$config["side_menu"] = array();
$config["starting_year"] = 2019;
$config["gender"] = array("male","female");
$config["yes_no_opt"] = array("no","yes");
$config["mother_toungue"] = array("Gujarati","Hindi","English");
$config["caste"] = array("General","OBC","ST","SC");
$config["blood_group"] = array("AB+","AB-","A+","A-","B+","B-","O+","O-");
$config["religion"] = array("hindu","shikh","muslim","buddh","jain","christian");
$config["parent_type"] = array("father","mother","gardian");

$config["fee_type"] = array("yearly","half yearly","quarterly","monthly");
$config["paid_type"] = array("cash","cheque","card","netbanking");


/**
*  Delivery time configs
**/
$config["time_interval_min"] = 30; // here time intervals are in mitnus
$config["min_start_time"] = "6:00"; //Morning shcedule start time 
$config["max_end_time"] = "21:00"; //Shcedule End time
$config["time_formate_for_picker"] = "HH:mm"; //Shcedule End time

$config["coupon_discount_type"] = array("flat","percentage");
$config["coupon_type"] = array("general","share");
$config["offers_type"] = array("plusone","flatcombo");

$config["units"] = array("stuck","gram","kilo","nos","ml","liter","pers","porties");