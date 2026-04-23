<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra
 */
class xAdmin_ApartmentReserv extends Base_Admin_Controller {

  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array('apartment.id' => '');
  
  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array('date_from', 'date_to');
  
  /** Additional Actions. */
  protected $additionalActions = array('calendar');

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Calendar View
   */
  public function calendar() {
    $this->load->helper('common/itirra_date');

    $aId = isset($_GET['apartment.id']) ? $_GET['apartment.id'] : null;

    $year = isset($_GET['y']) ? $_GET['y'] : date('Y');
    $month = isset($_GET['m']) ? $_GET['m'] : date('m');

    if ($aId) {
      $prefs['template'] = '
         {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}
         {heading_row_start}<tr>{/heading_row_start}
         {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
         {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
         {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
         {heading_row_end}</tr>{/heading_row_end}
         {week_row_start}<tr>{/week_row_start}
         {week_day_cell}<td>{week_day}</td>{/week_day_cell}
         {week_row_end}</tr>{/week_row_end}
         {cal_row_start}<tr>{/cal_row_start}
         {cal_cell_start}<td>{/cal_cell_start}
         {cal_cell_content}<div class="reserv">{day}</div>{/cal_cell_content}
         {cal_cell_content_today}<div class="highlight reserv">{day}</div>{/cal_cell_content_today}
         {cal_cell_no_content}{day}{/cal_cell_no_content}
         {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}
         {cal_cell_blank}&nbsp;{/cal_cell_blank}
         {cal_cell_end}</td>{/cal_cell_end}
         {cal_row_end}</tr>{/cal_row_end}
         {table_close}</table>{/table_close}
      ';

      $this->load->library('calendar', $prefs);
      $this->layout->set("calendar", $this->calendar);

      $nextMonthTs = strtotime("+1 month", strtotime("$year-$month-01"));
      $prevMonthTs = strtotime("-1 month", strtotime("$year-$month-01"));

      $this->layout->set("nextMonth", date('m', $nextMonthTs));
      $this->layout->set("nextYear", date('Y', $nextMonthTs));
      $this->layout->set("prevMonth", date('m', $prevMonthTs));
      $this->layout->set("prevYear", date('Y', $prevMonthTs));

      $firstDayOfMonth = first_day_of_month($month);
      $lastDayOfMonth = last_day_of_month($month);
      $reservations = ManagerHolder::get('ApartmentReserv')->getAllReservations($aId, $firstDayOfMonth, $lastDayOfMonth);

      $reservationDays = array();
      if (!empty($reservations)) {
        $result = array();
        foreach ($reservations as $r) {
          $result = array_merge($result, date_interval($r['date_from'], $r['date_to']));
        }

        foreach ($result as $res) {
          $rArr = explode('-', $res);
          if ($rArr[1] == $month) {
            $reservationDays[(int)$rArr[2]] = (int)$rArr[2];
          }
        }
      }

      $this->layout->set("reservations", $reservationDays);
    }

    $this->layout->set("year", $year);
    $this->layout->set("month", $month);

    $apartments = ManagerHolder::get('Apartment')->getAsViewArray(array(), 'id');
    $this->layout->set("apartments", $apartments);

    $this->layout->set("aId", $aId);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/apartmentreserv' . get_get_params());
    $this->layout->view('calendar');
  }

}
