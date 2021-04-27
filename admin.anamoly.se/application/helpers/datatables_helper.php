<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * General function for datatable listing
 * @param  array $columns            table columns with database table field key array('database_field'=>'column_title'); 
 * @param  array $data               table records
 */
function _datatable($columns=array(),$data=array(),$edit=array(),$delete=array(),$image_fields=array(),$action=array()){
    $table = '
    <div class="table-responsive"><table class="table  table-vcenter js-dataTable-full">';
    $table .= '<thead><tr>';
        if(!empty($columns)){
            foreach($columns as $head)
            {
                $table .= "<th>".$head."</th>";
            }
        }
        if(!empty($edit) || !empty($delete) || !empty($action)){
            $table .= "<th style='width:150px;' class='d-none d-sm-table-cell'>"._l("Action")."</th>";
        }
        
    $table .= '</tr></thead>';
    $table .= '<tbody>';
    if(!empty($data) ){
        foreach($data as $dt){
            $table .= "<tr>";
            if(!empty($columns)){
                foreach($columns as $key=>$head)
                {
                        if($key == "status"){
                            switch($dt->$key){
                                case "0":
                                    $table .= "<td width='85px'><label class='badge badge-warning' >"._l("Pending")."</label></td>";
                                    break;
                                case "1":
                                    $table .= "<td width='85px'><label class='badge badge-info' >"._l("Approved")."</label></td>";
                                    break;
                                case "2":
                                    $table .= "<td width='85px'><label class='badge badge-danger' >"._l("Rejected")."</label></td>";
                                    break;
                                case "3":
                                    $table .= "<td width='85px'><label class='badge badge-success' >"._l("Complete")."</label></td>";
                                    break;
                                case "4":
                                    $table .= "<td width='85px'><label class='badge badge-primary' >"._l("Cancel")."</label></td>";
                                    break;
                            }
                        }else{
                            if(!empty($image_fields) && key_exists($key,$image_fields)){
                                $table .= "<td><div style='background-image:url(".base_url($image_fields[$key]."/".$dt->$key)."); background-size:cover; width : 40px; height : 40px; display:block; background-color:#e5e5e5; '></div></td>";
                            }else{
                                $table .= "<td>".$dt->$key."</td>";    
                            }
                        }
                            
                }                
            }
            
            /*if(!empty($image_fields)){
                    foreach($image_fields as $img_key=>$img_value){
                        $table .= "<td><div style='background-image:url(".$img_value."/".$dt->$img_key."); background-size:cover'></div></td>";
                    }
            }
            */
            
            if(!empty($edit) || !empty($delete) || !empty($action)){
                $table .= "<td class='d-none d-sm-table-cell'><div class='btn-group'>";
            }
            if(!empty($action))
            {
                foreach($action as $act)
                {
                    $table .= anchor($act["action"]."/".$dt->$act["key"], $act["icon"], array("class"=>"btn btn-sm btn-secondary js-tooltip-enabled ".$act['btnclass'],"data-toggle"=>"tooltip","data-original-title"=>(isset($act["title"])? $act["title"] : "" )));
                }    
            }
            if(!empty($edit)){
                    $url = $edit["action"];
                    
                    if(key_exists("key",$edit))
                        $url = $url."/".$dt->$edit["key"];
                    
                    if(key_exists("key2",$edit))
                        $url = $url."/".$dt->$edit["key2"];
                    
                    $table .= anchor($url, '<i class="fa fa-pencil"></i>', array("class"=>"btn btn-sm btn-secondary js-tooltip-enabled","data-toggle"=>"tooltip","data-original-title"=>(isset($act["title"])? $act["title"] : "" )));
            }
            if(!empty($delete)){
                    $url = $delete["action"];
                    
                    if(key_exists("key",$delete))
                        $url = $url."/".$dt->$delete["key"];
                    
                    if(key_exists("key2",$delete))
                        $url = $url."/".$dt->$delete["key2"];
                    
                    $table .= anchor($url, '<i class="fa fa-times"></i>', array("class"=>"btn btn-sm btn-secondary js-tooltip-enabled", "onclick"=>"return confirm('"._l('Are you sure delete?')."')","data-toggle"=>"tooltip","data-original-title"=>(isset($act["title"])? $act["title"] : "" )));
            }
            if(!empty($edit) || !empty($delete)){
                $table .= "</div></td>";
            }
            $table .= "</tr>";
        }
            
    }
    $table .= '</tbody>';    
    $table .= '</table></div>';
    
    return $table;
}
