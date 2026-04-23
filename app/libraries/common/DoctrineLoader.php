<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'database/Doctrine.php';

/**
 * Doctrine initialization class.
 * @author Itirra
 * @link http://itirra.com
 */
class DoctrineLoader {

  /** Flags. Switch them wisely. */
  private $shouldMigrate = false;

  /**
   * Constructor.
   */
  public function __construct() {
    // Get config
    require_once APPPATH . 'config/database.php';

    // Create DSN
    $db['default']['dsn'] = $db['default']['dbdriver'] .
                    					'://' . $db['default']['username'] .
                    					':' . $db['default']['password'] .
                          		'@' . $db['default']['hostname'] .
                            	'/' . $db['default']['database'];

    // Autoload
    spl_autoload_register(array('Doctrine_Core', 'autoload'));
    spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

    if (isset($db['default']['doctrine_cache']) && $db['default']['doctrine_cache']) {
      $this->enableMemcache();
    }

    // Create connection
    $connection = Doctrine_Manager::connection($db['default']['dsn'], $db['default']['database']);

    // Connection attributes
    $connection->setCharset("utf8");
    $connection->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);
    $connection->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
    //Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
    Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);

    if (isset($db['default']['enable_profiler']) && $db['default']['enable_profiler']) {
      $profiler = new Doctrine_Connection_Profiler();
      $connection->setListener($profiler);
    }

    if ($this->shouldMigrate) {
      try {
        $this->migrate($connection);
      } catch (Exception $e) {
        log_message('error', 'Doctrine migration failed. ' . $e->getMessage());
      }
    }

    Doctrine_Core::loadModels(APPPATH . 'model/entities');
  }

  /**
   * Enable Memcache.
   */
  private function enableMemcache() {
    $servers = array(
      'host' => 'localhost',
      'port' => 11211,
      'persistent' => true
    );

    $cacheDriver = new Doctrine_Cache_Memcache(array(
      'servers' => $servers,
      'compression' => false
    ));

    Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
  }

  /**
   * Migrate.
   * @throws Exception
   */
  private function migrate($connection) {
    if (empty($connection)) throw new InvalidArgumentException('No connection specified.');

    $migration = new Doctrine_Migration(APPPATH . 'doctrine/migrations', $connection);
    try {
      $migration->migrate();
    } catch (Exception $e) {
      // Append migration version
      throw new Exception('Migration version: ' . $migration->getCurrentVersion() . '. ' . $e->getMessage());
    }
  }

}