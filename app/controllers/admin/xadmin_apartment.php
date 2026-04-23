<?php
require_once APPPATH . 'controllers/admin/base/base_admin_lang_controller.php';

/**
 * xAdmin_Apartment controller.
 * Управление объектами в админке.
 */
class xAdmin_Apartment extends Base_Admin_Lang_Controller {

  /** Фильтры. */
  protected $filters = array(
    'is_published' => '',
    'objecttype.id' => '',
    'city.id' => ''
  );

  /** Параметры поиска. */
  protected $searchParams = array("id");

  /**
   * Конструктор (совместим с PHP 8.1+)
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Устанавливает переменные шаблона для списка объектов.
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->actions["reserv"] = $this->adminBaseRoute . '/' . strtolower($this->entityUrlName) . '/reserv';
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }

  /**
   * Создаёт или загружает объект по ID.
   */
  protected function createEntityId($entityId = null) {
    $langs = config_item('languages');
    $entity = new $this->managerName;
    $entity = $entity->toArray();

    if ($entityId) {
      $entity = ManagerHolder::get($this->managerName)->getById($entityId, 'e.*, city.*, objectfeatures.*, objecttype.*, image.*, images.*, header.*');

      if (!empty($entity['objectfeatures'])) {
        foreach ($entity['objectfeatures'] as &$of) {
          $translations = ManagerHolder::get('ObjectFeatureTranslation')->getAllWhere(
            array('objectfeature_id' => $of['id'], 'language' => 'en'), 'e.*'
          );
          if (!empty($translations)) {
            $translation = array_pop($translations);
            $of['name'] = $translation['name'];
          }
        }
      }

      if (empty($entity)) {
        redirect($this->adminBaseRoute . '/' . strtolower($this->entityUrlName));
      }

    } else {
      foreach ($langs as $k => $l) {
        $lang = array('language' => $k);
        foreach (ManagerHolder::get($this->managerName)->i18nFields as $f) {
          $lang[$f] = "";
        }
        $entity[ManagerHolder::get($this->managerName)->translationTableAlias][] = $lang;
      }
    }

    $this->layout->set("languages", array_keys($langs));
    $this->layout->set("i18nFields", ManagerHolder::get($this->managerName)->i18nFields);
    return $entity;
  }

  /**
   * Pre-save обработка объекта перед сохранением.
   */
  protected function preSave(&$entity) {
    if (!empty($entity['objecttype_id'])) {
      $objType = ManagerHolder::get('ObjectType')->getById($entity['objecttype_id']);
      if ($objType['level'] == 0) {
        set_flash_error('Объект не может быть привязан к виду собственности верхнего уровня.');
        $this->redirectToReffer();
      }
    }

    $eArr = $entity->toArray();
    $city = ManagerHolder::get('City')->getById($eArr['city_id'])['title'];

    $street = '';
    $houseNum = '';
    foreach ($eArr['translations'] as $tr) {
      if ($tr['language'] === 'en') {
        $street = $tr['street'];
        $houseNum = $tr['house_num'];
        break;
      }
    }

    $this->load->library('common/GeoCode', array(
      'key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
      'language' => 'en'
    ));

    $geoSearchString = $city . ', ' . $street . ' ' . $houseNum;
    $geoResult = $this->geocode->get_coords($geoSearchString);

    if (!empty($geoResult['lat_lng'])) {
      $entity['latitude'] = $geoResult['lat_lng']['lat'];
      $entity['longitude'] = $geoResult['lat_lng']['lng'];
    }
  }

  /**
   * Редирект на бронирование конкретной квартиры.
   */
  public function reserv($id) {
    redirect($this->adminBaseRoute . '/apartmentreserv?apartment.id=' . $id);
  }

}
