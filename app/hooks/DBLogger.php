<?php
class DBLogger {

  private $layout = null;

  public function DBLogger() {
  }

  public function process() {
    log_message("error", "DBLogger!!!");
    $dbName = "bs_travelling";
    $profiler = $conn->getListener();
    $conn = Doctrine_Manager::getInstance()->getConnection($dbName);
    if ($profiler){
      $time = 0;
      
      $res = "<----------- DATABASE [" . uri_string() ."] ------------> \n";
      foreach ($profiler as $event) {
        $time += $event->getElapsedSecs();
        $res .= "Event: " . $event->getName() . "\n";
        $res .= $event->getQuery() . "\n";
        $params = $event->getParams();
        if(!empty($params)) {
          $res .= "Params: " .  print_r($params, true);
        }
        $res .= "Time" . $event->getElapsedSecs() . "\n";
        $res .= "\n";
      }
      $res .= "Total time: " . $time  . "\n";
      log_message("error", $res);
    }
  }



}
?>