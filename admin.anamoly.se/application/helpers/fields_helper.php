<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Function that renders input for admin area based on passed arguments
 * @param  string $name             input name
 * @param  string $label            label name
 * @param  string $value            default value
 * @param  string $type             input type eq text,number
 * @param  array  $input_attrs      attributes on <input
 * @param  array  $form_group_attr  <div class="form-group"> html attributes
 * @param  string $form_group_class additional form group class
 * @param  string $input_class      additional class on input
 * @return string
 */
 
function _input_field($name, $label = '', $value = '', $type = 'text', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '', $note =' ')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        //if(trim($val) != "")
            $_input_attrs .= $key . '=' . '"' . $val . '" ';
        //else
        //    $_input_attrs .= $key;
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>
    <div class="form-material floating">';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '">';
    
    $input .= '</div><small id="'.$name.'_note">'.$note.'</small></div>';

    return $input;
}
function _checkbox($name,$label,$value='',$input_attrs=array(),$checked=false,$form_group_class="",$checkbox_class=""){
    ob_start();
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    ?>
    <div class="form-group <?php echo $form_group_class; ?>">
        <div class="checkbox">
            <label class="css-control css-control-lg css-control-secondary css-checkbox">
                <input type="checkbox" class="css-control-input <?php echo $checkbox_class; ?>"  name="<?php echo $name; ?>" <?php echo ($checked) ? "checked" : ""; ?> <?php echo ($value!="") ? "value='$value'" : ''; ?> <?php echo $_input_attrs; ?>  />
                <span class="css-control-indicator"></span>
                <?php echo $label ?>
            </label>
        </div>
    </div>
    <?php
    $settings = ob_get_contents();
    ob_end_clean();
    return $settings;
}
function _submit_button($label="",$input_attrs=array()){
    ob_start();
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }
    ?>
    <div class="col-md-12"><button type="submit" class="btn btn-primary btn-flat" <?php echo $_input_attrs; ?> >  <?php echo ($label=="")? _l("Submit") : $label; ?></button></div>
    <?php
    $settings = ob_get_contents();
    ob_end_clean();
    return $settings;

}
function _box_open($heading='',$box_attrs=array()){
    ob_start();
    $_box_attrs     = '';
    $_box_class = '';
    foreach ($box_attrs as $key => $val) {
        if($key == "class")
            $_box_class = $val;
        else
            $_box_attrs .= $key . '=' . '"' . $val . '" ';
    }

    ?>
    <div class="box <?php echo $_box_class; ?>" <?php echo $_box_attrs; ?> >
        <?php if($heading != ''){ ?>
        <div class="box-header">
            <h3 class="box-title"><?php echo $heading; ?></h3>
        </div>
        <?php } ?>
        <div class="box-body">
    <?php
    $settings = ob_get_contents();
    ob_end_clean();
    return $settings;
}
function _box_close($footer=''){
   ob_start();
   ?>
        </div>
   </div>
   <?php 
   $settings = ob_get_contents();
   ob_end_clean();
   return $settings;

}
/**
 * Render date picker input for admin area
 * @param  [type] $name             input name
 * @param  string $label            input label
 * @param  string $value            default value
 * @param  array  $input_attrs      input attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $input_class      <input> additional class
 * @return string
 */
function _date_input($name, $label = '', $value = '', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<div class="input-group date">';
    $input .= '<input type="text" id="' . $name . '" name="' . $name . '" class="form-control datepicker' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '">';
    $input .= '<div class="input-group-addon">
    <i class="fa fa-calendar calendar-icon"></i>
</div>';
    $input .= '</div>';
    $input .= '</div>';

    return $input;
}

/**
 * Render date time picker input for admin area
 * @param  [type] $name             input name
 * @param  string $label            input label
 * @param  string $value            default value
 * @param  array  $input_attrs      input attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $input_class      <input> additional class
 * @return string
 */
function _datetime_input($name, $label = '', $value = '', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '')
{
    $html = _date_input($name, $label, $value, $input_attrs, $form_group_attr, $form_group_class, $input_class);
    $html = str_replace('datepicker', 'datetimepicker', $html);

    return $html;
}

/**
 * Render timepicker input for admin area
 * @param  [type] $name             input name
 * @param  string $label            input label
 * @param  string $value            default value
 * @param  array  $input_attrs      input attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $input_class      <input> additional class
 * @return string
 */
function _time_input($name, $label = '', $value = '', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="bootstrap-timepicker"><div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<div class="input-group date">';
    $input .= '<input type="text" id="' . $name . '" name="' . $name . '" class="form-control timepicker' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '">';
    $input .= '<div class="input-group-addon">
    <i class="fa fa-clock-o"></i>
</div>';
    $input .= '</div>';
    $input .= '</div></div>';

    return $input;
}

    
/**
 * Render textarea for admin area
 * @param  [type] $name             textarea name
 * @param  string $label            textarea label
 * @param  string $value            default value
 * @param  array  $textarea_attrs      textarea attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $textarea_class      <textarea> additional class
 * @return string
 */
function _textarea($name, $label = '', $value = '', $textarea_attrs = array(), $form_group_attr = array(), $form_group_class = '', $textarea_class = '')
{
    $textarea         = '';
    $_form_group_attr = '';
    $_textarea_attrs  = '';
    if (!isset($textarea_attrs['rows'])) {
        $textarea_attrs['rows'] = 4;
    }

    if (isset($textarea_attrs['class'])) {
        $textarea_class .= ' '. $textarea_attrs['class'];
        unset($textarea_attrs['class']);
    }

    foreach ($textarea_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_textarea_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_textarea_attrs = rtrim($_textarea_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($textarea_class)) {
        $textarea_class = trim($textarea_class);
        $textarea_class = ' ' . $textarea_class;
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    $textarea .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>
    ';
    /*
    if (strpos($textarea_class, 'js-simplemde') == false) {
        $textarea .= '<div class="form-material floating">';
    }else{
        $textarea .= '<div>';
    }
    */
    $v = $value;//clear_textarea_breaks($value);
    if (strpos($textarea_class, 'tinymce') !== false) {
        $v = $value;
    }
    if ($label != '' && strpos($textarea_class, 'js-simplemde') == false) {
        $textarea = $textarea.'<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }else{
        $textarea = $textarea.'<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $textarea .= '<textarea id="' . $name . '" name="' . $name . '" class="form-control' . $textarea_class . '" ' . $_textarea_attrs . '>' . set_value($name, $v) . '</textarea>';
    
    $textarea .= '</div>';

    return $textarea;
}

/**
 * Render <select> field optimized for admin area and bootstrap-select plugin
 * @param  string   $name       select name
 * @param  array    $options    option to include  formate array("option_val"=>"option_title")
 * @param  string   $label      select label
 * @param  string/array  $selected         default selected value
 * @param  array    $extra      extra for select
 * @return string
 */
function _select($name, $options, $label = '',$key_value_field=array(), $selected='',$select_attrs=array(),$extra=''){
    $select_class = '';
    $form_group_class = '';
    $form_group_attr = '';
    $select= '';
    $include_blank = false;
    if(is_array($extra)){
        if(key_exists('form_group_class',$extra))
            $form_group_class = $extra['form_group_class'];
            
        if(key_exists('form_group_attr',$extra))
            $form_group_attr = $extra['form_group_attr'];
            
        if(key_exists('include_blank',$extra))
            $include_blank = $extra['include_blank'];
            
        if(key_exists('select_class',$extra))
            $select_class = $extra['select_class'];
    }
    $_select_attrs     = '';
    foreach ($select_attrs as $key => $val) {
        $_select_attrs .= $key . '=' . '"' . $val . '" ';
    }
    $select .= '<div class="select-placeholder form-group ' . $form_group_class . '" ' . $form_group_attr . '>';
    if ($label != '') {
        $select .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $select .= '<select id="' . $name . '" name="' . $name . '" class="form-control select2 js-select2 ' . $select_class . '" data-live-search="true" '.$_select_attrs.' >';
    if ($include_blank) {
        $select .= '<option value="">'.$include_blank.'</option>';
    }
    if(array_key_exists("multiple",$select_attrs)){
        if(!is_array($selected))
        $selected = explode(",",$selected);
    }
    foreach($options as $k=>$option){
        $key = '';
        $val = '';
        if(!empty($key_value_field)){
            if($key_value_field[0] == "key"){
                $key = $k;
                $val = $k;
            }else if($key_value_field[0] == "value"){
                $key = $option;
                $val = $option;
            }else{
                $keyvalue=$key_value_field[0];                
                $key = $option->$keyvalue;
                $val = $option->$keyvalue;
                if(count($key_value_field) > 1){
                    $valuekey=$key_value_field[1];
                    $val = $option->$valuekey;
                }
                    
            }
        }else{
            $key = $k;
            $val = $option;
        }
        $is_selected = "";
        if($selected != ''){
           
            if(is_array($selected) && in_array($key,$selected))
                $is_selected = "selected";
            else if($selected == $key)
                $is_selected = "selected";
                
        }
        $select .= '<option value="' . trim($key) . '" '.$is_selected.' >' . $val . '</option>';
    }
    $select .= '</select>';
    $select .= '</div>';
    return $select;   
}

function _image_field($name,$label,$image_value="",$input_attrs=array(),$form_group_class='',$note=''){
    ob_start();
     $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    ?>
            <div class="form-group <?php echo $form_group_class; ?>">

        <?php if($image_value != ''){ ?>
        <div class="">
            <div class="bus-product thumbnail mb-10 bg-gray-lighter">                                   
                <div class="pro-icon1" style="background-image: url('<?php echo $image_value; ?>'); height: 150px; width: 100%; background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
            </div>
        </div>
        <?php } ?>
        <div class="">
            <div class="custom-file">
                
                <input type="file" id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="custom-file-input js-custom-file-input-enabled" <?php echo $_input_attrs; ?> 
                 data-validation-optional="true"
  data-validation="mime size"
  data-validation-allowing="jpg, png, gif"
  data-validation-max-size="512kb"
  data-validation-error-msg-size="<?php _l("You can not upload images larger than 512kb"); ?>"
  data-validation-error-msg-mime="<?php _l("You can only upload images"); ?>"
  />
                <?php if($label !=''){ ?>
                <label class="custom-file-label" for="<?php echo $name; ?>"> <?php echo $label; ?></label>
                <?php } ?>
            </div>
            <small><?php echo $note; ?></small>
        </div>
        
  </div>
    <?php
  $settings = ob_get_contents();
  ob_end_clean();
  return $settings;
}

/**
 * For more readable code created this function to render only yes or not values for settings
 * @param  string $option_value option from database to compare
 * @param  string $label        input label
 * @param  string $tooltip      tooltip
 */
function _yes_no_option($option_value, $label, $tooltip = '', $replace_yes_text = '', $replace_no_text = '', $replace_1 = '', $replace_0 = '')
{
    ob_start(); ?>
    <div class="form-group">
        <label for="<?php echo $option_value; ?>" class="control-label clearfix">
            <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="'. _l($tooltip, '', false) .'"></i> ': '') . _l($label, '', false); ?>
        </label>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_1_<?php echo $label; ?>" name="settings[<?php echo $option_value; ?>]" value="<?php echo $replace_1 == '' ? 1 : $replace_1; ?>" <?php if (get_option($option_value) == ($replace_1 == '' ? '1' : $replace_1)) {
        echo 'checked';
    } ?>>
            <label for="y_opt_1_<?php echo $label; ?>">
                <?php echo $replace_yes_text == '' ? _l('settings_yes') : $replace_yes_text; ?>
            </label>
        </div>
        <div class="radio radio-primary radio-inline">
                <input type="radio" id="y_opt_2_<?php echo $label; ?>" name="settings[<?php echo $option_value; ?>]" value="<?php echo $replace_0 == '' ? 0 : $replace_0; ?>" <?php if (get_option($option_value) == ($replace_0 == '' ? '0' : $replace_0)) {
        echo 'checked';
    } ?>>
                <label for="y_opt_2_<?php echo $label; ?>">
                    <?php echo $replace_no_text == '' ? _l('settings_no') : $replace_no_text; ?>
                </label>
        </div>
    </div>
    <?php
    $settings = ob_get_contents();
    ob_end_clean();
    echo $settings;
}


function _radio($name,$options, $selected='',$label='',$classes='',$input_attrs=array(),$form_group_classes=""){
   ob_start();
        $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }
   ?>
   <div class="col-md-12">
   <div class="form-group <?php echo $form_group_classes; ?>"> 
   <?php 
   if($label!=''){
   ?>
   <label><?php echo $label; ?></label>
   <?php
   }
   foreach($options as $key=>$val){
        $is_checked = '';
        if($selected != '' && $key == $selected){
            $is_checked = 'checked=""';
        }
        
   ?>
   <div class="custom-control custom-radio custom-control-inline mb-5">
    <input name="<?php echo $name; ?>" id="radio_<?php echo $key; ?>" value="<?php echo $key; ?>" type="radio" <?php echo $is_checked; ?> class="custom-control-input <?php echo $classes; ?>" <?php echo $_input_attrs; ?> >
    <label for="radio_<?php echo $key; ?>" class="custom-control-label"><?php echo $val; ?></label>
   </div>
   <?php
   }
   ?>
   </div>
   </div>
   <?php
   $settings = ob_get_contents();
   ob_end_clean();
   echo $settings;
}

/**
 * Render color picker input for admin area
 * @param  [type] $name             input name
 * @param  string $label            input label
 * @param  string $value            default value
 * @param  array  $input_attrs      input attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $input_class      <input> additional class
 * @return string
 */
function _color_input($name, $label = '', $value = '', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '')
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '" ';
    }

    $_input_attrs = rtrim($_input_attrs);

    $form_group_attr['app-field-wrapper'] = $name;

    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '" ';
    }

    $_form_group_attr = rtrim($_form_group_attr);

    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
    $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
    }
    $input .= '<div class="input-group">';
    $input .= '<input type="text" id="' . $name . '" name="' . $name . '" class="form-control colorpickerjs' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '">';
  
    $input .= '</div>';
    $input .= '</div>';

    return $input;
}
?>