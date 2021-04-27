<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

/**
 * Generate encryption key for app-config.php
 * @return stirng
 */
function generate_encryption_key()
{
    $CI =& get_instance();
    // In case accessed from my_functions_helper.php
    $CI->load->library('encryption');
    $key = bin2hex($CI->encryption->create_key(16));

    return $key;
}
/**
 * Get decrypted get refrance
 * @param string $key
 * @return string
 **/
function _get_val($key,$force_exit = false){
    $CI =& get_instance();
    $e_s = $CI->input->get($key);
    $d_s = $CI->encryption->decrypt($e_s);
    if($force_exit){
        if($d_s == NULL || $d_s == "")
            exit();
    }
    return $d_s;
    
}
/**
 * Get return encrypted value
 * @param string $value
 * @return string
 **/
function _encrypt_val($value,$url_safe=true){
    $CI =& get_instance();
    $ret = $CI->encryption->encrypt($value);
    if ($url_safe) {
          $ret = strtr($ret, array('+' => '.', '=' => '-', '/' => '~'));
    }
    return $ret;
}
/**
 * Get return decrypted value
 * @param string $value
 * @return string
 **/
function _decrypt_val($value,$force_exit = true){
    $CI =& get_instance();
    $value = strtr($value, array('.' => '+', '-' => '=', '~' => '/'));

    $d_s = $CI->encryption->decrypt($value);
    
    if($force_exit){
        if($d_s == NULL || $d_s == "")
            exit();
    }
    
    return $d_s;
} 
/**
 * Function used to validate all recaptcha from google reCAPTCHA feature
 * @param  string $str
 * @return boolean
 */
function do_recaptcha_validation($str = '')
{
    $CI =& get_instance();
    $CI->load->library('form_validation');
    $google_url = "https://www.google.com/recaptcha/api/siteverify";
    $secret     = get_option('recaptcha_secret_key');
    $ip         = $CI->input->ip_address();
    $url        = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
    $curl       = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res, true);
    //reCaptcha success check
    if ($res['success']) {
        return true;
    } else {
        $CI->form_validation->set_message('recaptcha', _l('recaptcha_error'));

        return false;
    }
}
/**
 * Get current date format from options
 * @return string
 */
function get_current_date_format($php = false)
{
    $format = get_option('dateformat');
    $format = explode('|', $format);

    $hook_data = do_action('get_current_date_format', array(
        'format' => $format,
        'php' => $php,
    ));

    $format = $hook_data['format'];
    $php    = $php;

    if ($php == false) {
        return $format[1];
    } else {
        return $format[0];
    }
}
/**
 * Get timezones list
 * @return array timezones
 */
function get_timezones_list()
{
    return array(
        'EUROPE'=>DateTimeZone::listIdentifiers(DateTimeZone::EUROPE),
        'AMERICA'=>DateTimeZone::listIdentifiers(DateTimeZone::AMERICA),
        'INDIAN'=>DateTimeZone::listIdentifiers(DateTimeZone::INDIAN),
        'AUSTRALIA'=>DateTimeZone::listIdentifiers(DateTimeZone::AUSTRALIA),
        'ASIA'=>DateTimeZone::listIdentifiers(DateTimeZone::ASIA),
        'AFRICA'=>DateTimeZone::listIdentifiers(DateTimeZone::AFRICA),
        'ANTARCTICA'=>DateTimeZone::listIdentifiers(DateTimeZone::ANTARCTICA),
        'ARCTIC'=>DateTimeZone::listIdentifiers(DateTimeZone::ARCTIC),
        'ATLANTIC'=>DateTimeZone::listIdentifiers(DateTimeZone::ATLANTIC),
        'PACIFIC'=>DateTimeZone::listIdentifiers(DateTimeZone::PACIFIC),
        'UTC'=>DateTimeZone::listIdentifiers(DateTimeZone::UTC),
    );
}

/**
 * Check if visitor is on mobile
 * @return boolean
 */
function is_mobile()
{
    $CI =& get_instance();
    if ($CI->agent->is_mobile()) {
        return true;
    }

    return false;
}
/**
 * Set session alert / flashdata
 * @param string $type    Alert type
 * @param string $message Alert message
 */
function set_alert($type, $message)
{
    $CI =& get_instance();
    $CI->session->set_flashdata('message-' . $type, $message);
}
/**
 * Redirect to blank page
 * @param  string $message Alert message
 * @param  string $alert   Alert type
 */
function blank_page($message = '', $alert = 'danger')
{
    set_alert($alert, $message);
    redirect(admin_url('not_found'));
}
/**
 * Redirect to access danied page and log activity
 * @param  string $permission If permission based to check where user tried to acces
 */
function access_denied($permission = '')
{
    set_alert('danger', _l('access_denied'));
    logActivity('Tried to access page where don\'t have permission [' . $permission . ']');
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect(admin_url('access_denied'));
    }
}
/**
 * Throws header 401 not authorized, used for ajax requests
 */
function ajax_access_denied()
{
    header('HTTP/1.0 401 Unauthorized');
    echo _l('access_denied');
    die;
}
/**
 * Set debug message - message wont be hidden in X seconds from javascript
 * @since  Version 1.0.1
 * @param string $message debug message
 */
function set_debug_alert($message)
{
    get_instance()->session->set_flashdata('debug', $message);
}

/**
 * System popup message for admin area
 * This is used to show some general message for user within a big full screen div with white background
 * @param string $message message for the system popup
 */
function set_system_popup($message)
{
    if (!is_admin()) {
        return false;
    }

    if (defined('APP_DISABLE_SYSTEM_STARTUP_HINTS') && APP_DISABLE_SYSTEM_STARTUP_HINTS) {
        return false;
    }

    get_instance()->session->set_userdata(array(
        'system-popup'=>$message,
    ));
}
/**
 * Available date formats
 * @return array
 */
function get_available_date_formats()
{
    $date_formats = array(
        'd-m-Y|%d-%m-%Y' => 'd-m-Y',
        'd/m/Y|%d/%m/%Y' => 'd/m/Y',
        'm-d-Y|%m-%d-%Y' => 'm-d-Y',
        'm.d.Y|%m.%d.%Y' => 'm.d.Y',
        'm/d/Y|%m/%d/%Y' => 'm/d/Y',
        'Y-m-d|%Y-%m-%d' => 'Y-m-d',
        'd.m.Y|%d.%m.%Y' => 'd.m.Y',
    );

    return do_action('get_available_date_formats', $date_formats);
}
/**
 * Get weekdays as array
 * @return array
 */
function get_weekdays()
{
    return array(
        _l('wd_monday'),
        _l('wd_tuesday'),
        _l('wd_wednesday'),
        _l('wd_thursday'),
        _l('wd_friday'),
        _l('wd_saturday'),
        _l('wd_sunday'),
    );
}
/**
 * Get non translated week days for query help
 * Do not edit this
 * @return array
 */
function get_weekdays_original()
{
    return array(
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    );
}

/**
 * Outputs language string based on passed line
 * @since  Version 1.0.1
 * @param  string $line  language line string
 * @param  string $label sprint_f label
 * @return string        formatted language
 */
function _l($line, $label = '', $log_errors = true)
{
    $CI =& get_instance();

    $hook_data = do_action('before_get_language_text', array('line'=>$line, 'label'=>$label));
    $line = $hook_data['line'];
    $label = $hook_data['label'];

    if (is_array($label) && count($label) > 0) {
        $_line = vsprintf($CI->lang->line(trim($line), $log_errors), $label);
    } else {
        $_line = @sprintf($CI->lang->line(trim($line), $log_errors), $label);
    }

    $hook_data = do_action('after_get_language_text', array('line'=>$line, 'label'=>$label, 'formatted_line'=>$_line));
    $_line = $hook_data['formatted_line'];
    $line = $hook_data['line'];

    if ($_line != '') {
        if (preg_match('/"/', $_line) && !is_html($_line)) {
            $_line = htmlspecialchars($_line, ENT_COMPAT);
        }

        return $_line;//ForceUTF8\Encoding::toUTF8($_line);
    }

    if (mb_strpos($line, '_db_') !== false) {
        return 'db_translate_not_found';
    }

    return $line;//ForceUTF8\Encoding::toUTF8($line);
}

/**
 * Format date to selected dateformat
 * @param  date $date Valid date
 * @return date/string
 */
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    if (strpos($date, ' ') !== false) {
        return _dt($date);
    }
    $format = get_current_date_format();
    $date   = strftime($format, strtotime($date));

    return do_action('after_format_date', $date);
}

/**
 * Format datetime to selected datetime format
 * @param  datetime $date datetime date
 * @return datetime/string
 */
function _dt($date, $is_timesheet = false)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00 00:00:00') {
        return '';
    }
    $format = get_current_date_format();
    $hour12 = (get_option('time_format') == 24 ? false : true);

    if ($is_timesheet == false) {
        $date = strtotime($date);
    }

    if ($hour12 == false) {
        $tf = '%H:%M:%S';
        if ($is_timesheet == true) {
            $tf = '%H:%M';
        }
        $date   = strftime($format . ' ' . $tf, $date);
    } else {
        $date = date(get_current_date_format(true). ' g:i A', $date);
    }

    return do_action('after_format_datetime', $date);
}

/**
 * Convert string to sql date based on current date format from options
 * @param  string $date date string
 * @return mixed
 */
function to_sql_date($date, $datetime = false)
{
    if ($date == '' || $date == null) {
        return null;
    }

    $to_date     = 'Y-m-d';
    $from_format = get_current_date_format(true);


    $hook_data['date']        = $date;
    $hook_data['from_format'] = $from_format;
    $hook_data['datetime']    = $datetime;

    $hook_data = do_action('before_sql_date_format', $hook_data);

    $date        = $hook_data['date'];
    $from_format = $hook_data['from_format'];

    if ($datetime == false) {
        return date_format(date_create_from_format($from_format, $date), $to_date);
    } else {
        if (strpos($date, ' ') === false) {
            $date .= ' 00:00:00';
        } else {
            $hour12 = (get_option('time_format') == 24 ? false : true);
            if ($hour12 == false) {
                $_temp = explode(' ', $date);
                $time  = explode(':', $_temp[1]);
                if (count($time) == 2) {
                    $date .= ':00';
                }
            } else {
                $tmp = _simplify_date_fix($date, $from_format);
                $time = date("G:i", strtotime($tmp));
                $tmp = explode(' ', $tmp);
                $date = $tmp[0]. ' ' . $time.':00';
            }
        }

        $date = _simplify_date_fix($date, $from_format);
        $d = strftime('%Y-%m-%d %H:%M:%S', strtotime($date));

        return do_action('to_sql_date_formatted', $d);
    }
}

/**
 * Function that will check the date before formatting and replace the date places
 * This function is custom developed because for some date formats converting to y-m-d format is not possible
 * @param  string $date        the date to check
 * @param  string $from_format from format
 * @return string
 */
function _simplify_date_fix($date, $from_format)
{
    if ($from_format == 'd/m/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    } elseif ($from_format == 'm/d/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm.d.Y') {
        $date = preg_replace('#(\d{2}).(\d{2}).(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm-d-Y') {
        $date = preg_replace('#(\d{2})-(\d{2})-(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    }

    return $date;
}
/**
 * Check if passed string is valid date
 * @param  string  $date
 * @return boolean
 */
function is_date($date)
{
    if (strlen($date) < 10) {
        return false;
    }

    return (bool) strtotime($date);
}
/**
 * Get locale key by system language
 * @param  string $language language name from (application/languages) folder name
 * @return string
 */
function get_locale_key($language = 'english')
{
    $locale = 'en';
    if ($language == '') {
        return $locale;
    }

    $locales = get_locales();

    if (isset($locales[$language])) {
        $locale = $locales[$language];
    } elseif (isset($locales[ucfirst($language)])) {
        $locale = $locales[ucfirst($language)];
    } else {
        foreach ($locales as $key => $val) {
            $key      = strtolower($key);
            $language = strtolower($language);
            if (strpos($key, $language) !== false) {
                $locale = $val;
                // In case $language is bigger string then $key
            } elseif (strpos($language, $key) !== false) {
                $locale = $val;
            }
        }
    }

    $locale = do_action('before_get_locale', $locale);

    return $locale;
}
/**
 * Load language in admin area
 * @param  string $staff_id
 * @return string return loaded language
 */
function load_admin_language($staff_id = '')
{
    $CI =& get_instance();

    $CI->lang->is_loaded = array();
    $CI->lang->language  = array();

    $language = get_option('active_language');
    if (is_staff_logged_in() || $staff_id != '') {
        $staff_language = get_staff_default_language($staff_id);
        if (!empty($staff_language)) {
            if (file_exists(APPPATH . 'language/' . $staff_language)) {
                $language = $staff_language;
            }
        }
    }

    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    $language = do_action('after_load_admin_language', $language);

    return $language;
}

/**
 * Get current url with query vars
 * @return string
 */
function current_full_url()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());

    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}

if (defined('APP_CSRF_PROTECTION')) {
    add_action('app_admin_head', 'csrf_jquery_token');
    add_action('app_customers_head', 'csrf_jquery_token');
    add_action('app_external_form_head', 'csrf_jquery_token');
    add_action('elfinder_tinymce_head', 'csrf_jquery_token');
}

/**
 * Generate md5 hash
 * @return string
 */
function app_generate_hash(){
    return md5(rand() . microtime() . time() . uniqid());
}

/**
 * If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF
 * @return mixed
 */
function csrf_jquery_token()
{
    $csrf = array();
    $csrf['formatted'] = array(get_instance()->security->get_csrf_token_name()=>get_instance()->security->get_csrf_hash());
    $csrf['token_name'] = get_instance()->security->get_csrf_token_name();
    $csrf['hash'] = get_instance()->security->get_csrf_hash(); ?>
    <script>
        if (typeof (jQuery) === 'undefined' && !window.deferAfterjQueryLoaded) {
            window.deferAfterjQueryLoaded = [];
            Object.defineProperty(window, "$", {
                set: function (value) {
                    window.setTimeout(function () {
                        $.each(window.deferAfterjQueryLoaded, function (index, fn) {
                            fn();
                        });
                    }, 0);
                    Object.defineProperty(window, "$", {
                        value: value
                    });
                },
                configurable: true
            });
        }

        var csrfData = <?php echo json_encode($csrf); ?>;

        if (typeof(jQuery) == 'undefined') {
            window.deferAfterjQueryLoaded.push(function () {
                csrf_jquery_ajax_setup();
            });
            window.addEventListener('load',function(){
                csrf_jquery_ajax_setup();
            },true);
        } else {
            csrf_jquery_ajax_setup();
        }

        function csrf_jquery_ajax_setup() {
            $.ajaxSetup({
                data: csrfData.formatted
            });
        }
 </script>
 <?php
}

/**
 * In some places of the script we use app_happy_text function to output some words in orange color
 * @param  string $text the text to check
 * @return string
 */
function app_happy_text($text)
{
    $regex = do_action('app_happy_regex', 'congratulations!?|congrats!?|happy!?|feel happy!?|awesome!?|yay!?');
    $re = '/'.$regex.'/i';

    $app_happy_color = do_action('app_happy_color', 'rgb(255, 59, 0)');

    preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);
    foreach ($matches as $match) {
        $text = preg_replace('/'.$match[0].'/i', '<span style="color:'.$app_happy_color.';font-weight:bold;">'.$match[0].'</span>', $text);
    }

    return $text;
}

/**
 * Return server temporary directory
 * @return string
 */
function get_temp_dir()
{
    if (function_exists('sys_get_temp_dir')) {
        $temp = sys_get_temp_dir();
        if (@is_dir($temp) && is_writable($temp)) {
            return rtrim($temp, '/\\') . '/';
        }
    }

    $temp = ini_get('upload_tmp_dir');
    if (@is_dir($temp) && is_writable($temp)) {
        return rtrim($temp, '/\\') . '/';
    }

    $temp = TEMP_FOLDER;
    if (is_dir($temp) && is_writable($temp)) {
        return $temp;
    }

    return '/tmp/';
}

// TODO
function round_timesheet_time($datetime)
{
    $dt = new DateTime($datetime);
    $r = 15;
    // echo roundUpToMinuteInterval($dt,$r)->format('Y-m-d H:i:s') . '<br />';
    // echo roundDownToMinuteInterval($dt,$r)->format('Y-m-d H:i:s') . '<br />';
    $datetime = roundUpToMinuteInterval($dt, $r)->format('Y-m-d H:i:s');

    return $datetime;
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundUpToMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundDownToMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        floor($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

/**
 * @param $dateTime
 * @param int $minuteInterval
 * @return \DateTime
 */
function roundToNearestMinuteInterval($dateTime, $minuteInterval = 10)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

/**
 * @param $input_name
 * @param $upload_path
 * @param $file_format
 * @param $filename
 * @param int $max_upload_size
 * @return boolean
 */
function _do_upload($input_name, $upload_path, $file_format="",$filename="", $max_upload_size = 1024) {
    if($_FILES[$input_name]["size"] > 0){
        if(!file_exists($upload_path))
        {
            mkdir($upload_path);
        }
        $CI =& get_instance();
        $config['upload_path'] = $upload_path;
        if($filename != "")
            $config['file_name'] = $filename;
        if($file_format != "")
            $config['allowed_types'] = $file_format;
      
        $config["encrypt_name"] = true;  
        $config['remove_spaces'] = true;
        $config['max_size']      = $max_upload_size;
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if (!$CI->upload->do_upload($input_name)) {
            $CI->upload->display_errors();
            return FALSE;
        } else {
            return TRUE;
        }
    }
    return false;
}

/**
 * @param $input_name
 * @param $upload_path
 * @param $file_format
 * @param $filename
 * @param int $max_upload_size
 * @return \DateTime
 */
function _do_upload_multiple($input_name, $upload_path, $file_format="", $max_upload_size = 1024) {
    $data = array();
    $CI =& get_instance();
      $count = count($_FILES[$input_name]['name']);
      
      if(!file_exists($upload_path))
      {
        mkdir($upload_path);
      }  
      $config['upload_path'] = $upload_path; 
      if($file_format != "")
        $config['allowed_types'] = $file_format;
        
      $config['max_size'] = $max_upload_size;
      $config["encrypt_name"] = true;
      $config['remove_spaces'] = true;
      for($i=0;$i<$count;$i++){
        if(!empty($_FILES[$input_name]['name'][$i])){
          $config['file_name'] = $_FILES[$input_name]['name'][$i];
            
          $_FILES['file']['name'] = $_FILES[$input_name]['name'][$i];
          $_FILES['file']['type'] = $_FILES[$input_name]['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES[$input_name]['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES[$input_name]['error'][$i];
          $_FILES['file']['size'] = $_FILES[$input_name]['size'][$i];
          
          $CI->load->library('upload',$config); 
    
          if($CI->upload->do_upload('file')){
            $uploadData = $CI->upload->data();
            $filename = $uploadData['file_name'];
            $array = array("size"=>$_FILES['file']['size'],"type"=>$_FILES['file']['type']);
            $array["file_name"] = $filename;
            $data[] = $array;
          }else{
            $data[] = $CI->upload->display_errors();
          }
        }
      }
      return $data;
}
if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
    }
}
if (!function_exists('getheaderlanguage')) {
    function getheaderlanguage(){
        $language = "english";
        $request_headers = getallheaders();
        if(isset($request_headers[X_APP_LANGUAGE])){
            $language = $request_headers[X_APP_LANGUAGE];
        }
        return $language;
    }
}
if (!function_exists('generateNumericOTP')) {
    // Function to generate OTP 
    function generateNumericOTP($n) { 
        
        // Take a generator string which consist of 
        // all numeric digits 
        $generator = "1357902468"; 
    
        // Iterate for n-times and pick a single character 
        // from generator and append it to $result 
        
        // Login for generating a random character from generator 
        //     ---generate a random number 
        //     ---take modulus of same with length of generator (say i) 
        //     ---append the character at place (i) from generator to result 
    
        $result = ""; 
    
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        } 
    
        // Return result 
        return $result; 
    } 
}
if (!function_exists('_back_url')) {
    function _back_url()
    {
        $CI =& get_instance();
        $previous = $CI->session->tempdata("back_url");
        if ($previous == null || $previous == "") {
            $previous = "javascript:history.go(-1)";
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previous = $_SERVER['HTTP_REFERER'];
            }
        }
        return $previous;
    }
}
if(!function_exists('_set_back_url')){
    function _set_back_url(){
        $CI =& get_instance();
        $url = current_full_url();
        $CI->session->set_tempdata(array("back_url"=>$url), null, 3000);
    }
}
function _set_data_filter($key,$value=""){
    $CI =& get_instance();
    if(is_array($value)){
        foreach($value as $k=>$val){
            $k1 = $key."_".$k;
            $CI->session->set_tempdata(array($k1=>$val), NULL, 3000);
        }
    }else{
        $CI->session->set_tempdata(array($key=>$value), NULL, 3000);
    }
}
function _get_data_filter($key,$sub_key=""){
    $CI =& get_instance();
    if($sub_key != ""){
        $key = $key."_".$sub_key;
    }
    return $CI->session->tempdata($key);
}