<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Date Helper
 *
 * Itirra - http://itirra.com
 *
 * @author  Alexei Chizhmakov
 * @link    http://itirra.com
 * @since   Version 1.0
 */

// ------------------------------------------------------------------------


/**
 * Convert Date
 */
if (!function_exists('convert_date')) {
  function convert_date($string, $outFormat, $addSmartYears = FALSE, $translateMonths = FALSE) {
    $result = "";
    $time = strtotime($string);
    if ($addSmartYears) {
      if (date('Y') != date('Y', $time)) {
        $outFormat .= ' Y';
      }
    }
    $result = date($outFormat, $time);
    if ($translateMonths) {
      $result = translate_month($result, TRUE);
    }
    return $result;
  }
}

if (!function_exists('get_first_week_day')) {
  function get_first_week_day() {
    return 'Mon';
  }
}

if (!function_exists('get_last_week_day')) {
  function get_last_week_day() {
    return 'Sun';
  }
}

if (!function_exists('ago')) {
  function ago($datefrom, $dateto = -1) {
    // loading date_lang
    $CI =& get_instance();
    $CI->lang->load('date');
    
    $datefrom = str_replace('-', '/', $datefrom);
    if($datefrom == 0) {
      return lang('not_happened');
    }
    if($dateto == -1) {
      $dateto = time();
    }
    $datefrom = strtotime($datefrom);
    $difference = $dateto - $datefrom;

    $dateToDayStart = date('Y-m-d', $dateto) . ' 00:00:00';
    $dateYesterdayStart = strtotime($dateToDayStart . "-1 day");
    $dateToDayStart = strtotime($dateToDayStart);
    
    if ($datefrom > $dateToDayStart) {
      // Event happened today

      // If difference is less than 60 seconds,
      // seconds is a good interval of choice
      if (strtotime('-1 minute', $dateto) < $datefrom) {
        $datediff = $difference;
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'second_ago')  . ' ' . lang('ago');
        return $res;
      }

      // If difference is between 60 seconds and
      // 60 minutes, minutes is a good interval
      if (strtotime('-1 hour', $dateto) < $datefrom) {
        $datediff = floor($difference / 60);
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'minute_ago') . ' ' . lang('ago');
        return $res;
      }


      // If difference is between 1 hour and 24 hours
      // hours is a good interval
      if (strtotime('-1 day', $dateto) < $datefrom) {
        $datediff = floor($difference / 60 / 60);
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'hour_ago') . ' ' . lang('ago');
        return $res;
      }

    } else {
      // Event happened yesterday or earlier

      if($datefrom >= $dateYesterdayStart){
        // event happened yesterday
        return lang('yesterday_at') . ' ' .  date('G:i', $datefrom);
      }
      
      // If difference is between 1 day and 7 days
      // days is a good interval
      if (strtotime('-1 week', $dateto) < $datefrom){
        $day_difference = 1;
        while (strtotime('-'.$day_difference.' day', $dateto) >= $datefrom) {
          $day_difference++;
        }
        $datediff = --$day_difference;
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'day_ago') . ' ' . lang('ago');
        return $res;
      }


      // If difference is between 1 week and 30 days
      // weeks is a good interval
      if (strtotime('-1 month', $dateto) < $datefrom) {
        $week_difference = 1;
        while (strtotime('-'.$week_difference.' week', $dateto) >= $datefrom)
        {
          $week_difference++;
        }
        $datediff = --$week_difference;
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'week_ago') . ' ' . lang('ago');
        return $res;
      }


      // If difference is between 30 days and 365 days
      // months is a good interval, again, the same thing
      // applies, if the 29th February happens to exist
      // between your 2 dates, the function will return
      // the 'incorrect' value for a day
      if (strtotime('-1 year', $dateto) < $datefrom) {
        $months_difference = 1;
        while (strtotime('-'.$months_difference.' month', $dateto) >= $datefrom)
        {
          $months_difference++;
        }
        $datediff = --$months_difference;
        $res = number_noun($datediff, 'month_ago') . ' ' . lang('ago');
        return $res;
      }


      // If difference is between 30 days and 365 days
      // months is a good interval, again, the same thing
      // applies, if the 29th February happens to exist
      // between your 2 dates, the function will return
      // the 'incorrect' value for a day
      if (strtotime('-1 year', $dateto) >= $datefrom) {
        $year_difference = 1;
        while (strtotime('-'.$year_difference.' year', $dateto) >= $datefrom) {
          $year_difference++;
        }
        $datediff = --$year_difference;
        $datediffStr = $datediff . '';
        $res = number_noun($datediff, 'year_ago') . ' ' . lang('ago');
        return $res;
      }
    }
  }
}

//************************************* DAY *****************************************************
if (!function_exists('day_interval')) {
  function day_interval($date = null, $format = 'Y-m-d') {
    if (!$date) {
      $date = date($format);
    }
    $result = array();
    $result[] = $date . ' 00:00:00';
    $result[] = $date . ' 23:59:59';
    return $result;
  }
}

//************************************* WEEK *****************************************************


if (!function_exists('this_week')) {
  function this_week() {
    $result = array();
    $result[] = first_day_of_week();
    $result[] = last_day_of_week();
    return $result;
  }
}


if (!function_exists('this_week_full')) {
  function week_full($date = null) {
    $result = array();
    if($date){
      $time = strtotime($date);
      $day = date('d', $time);
      $month = date('m', $time);
      $year = date('Y', $time);
      $result[] = first_day_of_week($day, $month, $year);
    } else {
      $result[] = first_day_of_week();
    }
    // adding 6 other days of the week
    for($i = 1; $i <= 6; $i++){
      $result[] = date('Y-m-d', strtotime($result[0] . '+ ' . $i . ' days'));
    }
    return $result;
  }
}


if (!function_exists('last_week')) {
  function last_week() {
    $result = array();
    $time = strtotime('-8 day', strtotime(date('m/d/Y')));
    $result[] = first_day_of_week(date('d', $time), date('m', $time), date('Y', $time));
    $result[] = last_day_of_week(date('d', $time), date('m', $time), date('Y', $time));
    return $result;
  }
}

if (!function_exists('first_day_of_week')) {
  function first_day_of_week($day = null, $month = null, $year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$day) {
      $day = date('d');
    }
    if (!$month) {
      $month = date('m');
    }
    if (!$year) {
      $year = date('Y');
    }
    $str = $month . '/'. $day . '/' .$year;
    $result = strtotime($str);
    while (get_first_week_day() != date('D', $result)) {
      $result = strtotime('-1 day', $result);
    }
    $result = date($format, $result);
    if ($addTime) {
      $result .= ' 00:00:00';
    }
    return $result;
  }
}

if (!function_exists('last_day_of_week')) {
  function last_day_of_week($day = null, $month = null, $year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$day) {
      $day = date('d');
    }
    if (!$month) {
      $month = date('m');
    }
    if (!$year) {
      $year = date('Y');
    }
    $str = $month . '/'. $day . '/' .$year;
    $result = strtotime($str);
    while (get_last_week_day() != date('D', $result)) {
      $result = strtotime('+1 day', $result);
    }
    $result = date($format, $result);
    if ($addTime) {
      $result .= ' 23:59:59';
    }
    return $result;
  }
}


//************************************* MONTH *****************************************************

if (!function_exists('this_month')) {
  function this_month() {
    $result = array();
    $result[] = first_day_of_month();
    $result[] = last_day_of_month();
    return $result;
  }
}

if (!function_exists('last_month')) {
  function last_month() {
    $result = array();
    $result[] = first_day_of_month(date('m') - 1);
    $result[] = last_day_of_month(date('m') - 1);
    return $result;
  }
}

if (!function_exists('first_day_of_month')) {
  function first_day_of_month($month = null, $year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$month) {
      $month = date('m');
    }
    if (!$year) {
      $year = date('Y');
    }
    $str = $month . '/01/' . $year;
    $result = date($format, strtotime($str));
    if ($addTime) {
      $result .= ' 00:00:00';
    }
    return $result;
  }
}

if (!function_exists('last_day_of_month')) {
  function last_day_of_month($month = null, $year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$month) {
      $month = date('m');
    }
    if (!$year) {
      $year = date('Y');
    }
    $str = $month . '/01/' . $year;
    $result = date($format, strtotime('-1 second', strtotime('+1 month',strtotime($str))));
    if ($addTime) {
      $result .= ' 23:59:59';
    }
    return $result;
  }
}


/**
 * russian_month
 * @param $numeric_month
 * @param $ucfirst
 */
if (!function_exists('russian_month')) {
  function translate_month($dateString, $ucfirst = false, $lang = 'ru') {
    $translations = array(
      'ru' => array(
    		'January' => 'Января',
        'February' => 'Февраля',
        'March' => 'Марта',
        'April' => 'Апреля',
        'May' => 'Мая',
        'June' => 'Июня',
        'July' => 'Июля',
        'August' => 'Августа',
        'September' => 'Сентября',
        'October' => 'Октября',
        'November' => 'Ноября',
        'December' => 'Декабря'
      ),
      'de' => array(
    		'January' => 'Januar',
        'February' => 'Februar',
        'March' => 'March',
        'April' => 'April',
        'May' => 'Darf',
        'June' => 'June',
        'July' => 'Juli',
        'August' => 'August',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Dezember'
      )
    );
    
    if (isset($translations[$lang]) && !empty($translations[$lang])) {
      foreach ($translations[$lang] as $search => $replace) {
        $dateString = str_replace($search, $ucfirst ? $replace : strtolower($replace), $dateString);
      }
    }
    return $dateString;
  }
}

//************************************* YEAR *****************************************************

if (!function_exists('this_year')) {
  function this_year() {
    $result = array();
    $result[] = first_day_of_year();
    $result[] = last_day_of_year();
    return $result;
  }
}

if (!function_exists('last_year')) {
  function last_year() {
    $result = array();
    $result[] = first_day_of_year(date('Y') - 1);
    $result[] = last_day_of_year(date('Y') - 1);
    return $result;
  }
}

if (!function_exists('first_day_of_year')) {
  function first_day_of_year($year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$year) {
      $year = date('Y');
    }
    $str = '01/01/' . $year;
    $result = date($format, strtotime($str));
    if ($addTime) {
      $result .= ' 00:00:00';
    }
    return $result;
  }
}

if (!function_exists('last_day_of_year')) {
  function last_day_of_year($year = null, $addTime = FALSE, $format = 'Y-m-d') {
    if (!$year) {
      $year = date('Y');
    }
    $str = '12/31/' . $year;
    $result = date($format, strtotime($str));
    if ($addTime) {
      $result .= ' 23:59:59';
    }
    return $result;
  }
}


//************************************* GENERATORS *****************************************************
if (!function_exists('generate_years')) {
  function generate_years($startYear = 1940, $endYear = 2010, $reverseOrder = false){
    $result = array();
    if($reverseOrder){
      for($y = $endYear; $y >= $startYear; $y--){
        $result[] = $y;
      }
    } else {
      for($y = $startYear; $y <= $endYear; $y++){
        $result[] = $y;
      }
    }
    return $result;
  }
}


if (!function_exists('generate_months')) {
  function generate_months($langCode = 'ru', $genitive = false){ //genitive = roditelniy padezh
    $result = array();
    switch($langCode){
      case 'ru':
        if($genitive){
          $result['01'] = 'января';
          $result['02'] = 'февраля';
          $result['03'] = 'марта';
          $result['04'] = 'апреля';
          $result['05'] = 'мая';
          $result['06'] = 'июня';
          $result['07'] = 'июля';
          $result['08'] = 'августа';
          $result['09'] = 'сентября';
          $result['10'] = 'октября';
          $result['11'] = 'ноября';
          $result['12'] = 'декабря';
        } else {
          $result['01'] = 'январь';
          $result['02'] = 'февраль';
          $result['03'] = 'март';
          $result['04'] = 'апрель';
          $result['05'] = 'май';
          $result['06'] = 'июнь';
          $result['07'] = 'июль';
          $result['08'] = 'август';
          $result['09'] = 'сентябрь';
          $result['10'] = 'октябрь';
          $result['11'] = 'ноябрь';
          $result['12'] = 'декабрь';
        }
        break;

      case 'ua':
        if($genitive){
          $result['01'] = 'січень';
          $result['02'] = 'лютий';
          $result['03'] = 'березень';
          $result['04'] = 'квітень';
          $result['05'] = 'травень';
          $result['06'] = 'червень';
          $result['07'] = 'липень';
          $result['08'] = 'серпень';
          $result['09'] = 'вересень';
          $result['10'] = 'жовтень';
          $result['11'] = 'листопад';
          $result['12'] = 'грудень';
        } else {
          $result['01'] = 'січня';
          $result['02'] = 'лютого';
          $result['03'] = 'березня';
          $result['04'] = 'квітня';
          $result['05'] = 'травня';
          $result['06'] = 'червня';
          $result['07'] = 'липня';
          $result['08'] = 'серпня';
          $result['09'] = 'вересня';
          $result['10'] = 'жовтня';
          $result['11'] = 'листопада';
          $result['12'] = 'грудня';
        }
        break;
    }

    return $result;
  }
}

/**
 * generate_hours
 * @step
 * @startHour
 * @endhour
 */
if (!function_exists('generate_hours')) {
  function generate_hours($step = 1, $startHour = 0, $endHour = 23){
    //default values
    if($step === null) $step = 1;
    if($startHour === null) $startHour = 0;
    if($endHour === null) $endHour = 23;

    $result = array();
    if($startHour > $endHour){
      for($h = $startHour; $h >= $endHour; $h -= $step){
        $result[] = ($h < 10) ? ('0' . $h) : $h;
      }
    } else {
      for($h = $startHour; $h <= $endHour; $h += $step){
        $result[] = ($h < 10) ? ('0' . $h) : $h;
      }
    }
    return $result;
  }
}


/**
 * generate_minutes
 * @step
 * @startMinute
 * @endMinute
 */
if (!function_exists('generate_minutes')) {
  function generate_minutes($step = 1, $startMinute = 0, $endMinute = 59){
    //default values
    if($step === null) $step = 1;
    if($startMinute === null) $startMinute = 0;
    if($endMinute === null) $endMinute = 59;

    $result = array();

    if($startMinute > $endMinute){
      for($m = $startMinute; $m >= $endMinute; $m -= $step){
        $result[] = ($m < 10) ? ('0' . $m) : $m;
      }
    } else {
      for($m = $startMinute; $m <= $endMinute; $m += $step){
        $result[] = ($m < 10) ? ('0' . $m) : $m;
      }
    }
    return $result;
  }
}

//************************************* Age *****************************************************


/**
 * DO NOT USE IT!
 * REWRITE THIS FROM SCRATCH
 * It works wrong.
 * E.g. for birthday date = 1990-05-05
 * and current date = 2012-01-01 it returns 22, which is wrong
 *
 *  While rewriting consider using number_noun function
 */
if (!function_exists('age')) {
  function age($birthday){
    $result = '';
    if ($birthday) {
      list($year, $month, $day) = explode("-", $birthday);
      $result = date("Y") - $year . '';
      if ($result == 11 || $result == 12 || $result == 13 || $result == 14) {
        $result = $result . ' лет';
      } else if ($result == 1 || $result[strlen($result) - 1] == '1') {
        $result = $result . ' год';
      } else if ($result == 2
      || $result == 3
      || $result == 4
      || $result[strlen($result) - 1] == '2'
      || $result[strlen($result) - 1] == '3'
      || $result[strlen($result) - 1] == '4') {
        $result = $result . ' года';
      } else {
        $result = $result . ' лет';
      }
    }
    return $result;
  }
};

if (!function_exists('date_interval')) {
 function date_interval($dateFrom, $dateTo, $format = 'Y-m-d') {
    $result = array($dateFrom);
    $day = $dateFrom;
    $count = 1;
    while ($day != $dateTo) {
      $dayTime = strtotime('+1 day', strtotime($day));
      $day = date($format, $dayTime);
      $result[] = $day;
      $count++;
      if ($count > 50) {
        break;
      }
    }
    return $result;
  }
}

if (!function_exists('rstrptime')) {
  function rstrptime($date, $format) {
    $masks = array(
      '%d' => '(?P<d>[0-9]{2})',
      '%m' => '(?P<m>[0-9]{2})',
      '%Y' => '(?P<Y>[0-9]{4})'
    );

    $rexep = "#".strtr(preg_quote($format), $masks)."#";
    if(!preg_match($rexep, $date, $out))
    return false;

    $ret = array(
      "tm_mday" => $out['d'],
      "tm_mon"  => $out['m'],
      "tm_year" => $out['Y']
    );
    return $ret;
  }
}