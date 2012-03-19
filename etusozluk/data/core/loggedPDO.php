<?php
/**
 * http://www.coderholic.com/php-database-query-logging-with-pdo/ adresindeki kod
 * esas alınarak düzeltilmiş ve geliştirilmiştir.
 */


/**
* PDO sınıfını extend ederek bütün log bilgilerini FirePHP konsoluna basar.
*/
class LoggedPDO extends PDO
{
    public static $log = array();
    
    public function __construct($dsn, $username = null, $password = null, $options=null) {
        self::$log[] = array('Sorgu', 'Satır', 'Tepki Süresi');
        parent::__construct($dsn, $username, $password, $options);
        
    }
    
    /**
     * Sınıf kaldırılana kadarki kaydedilen bütün sorgular loglanır. 
     * Sınıf kaldırıldığındaki eldeki loglar konsola basılır.
     */
    public function __destruct() {
        self::printLog();
    }
    
    public function query($query) {
        $start = microtime(true);
        $result = parent::query($query);
        $time = microtime(true) - $start;
        self::$log[] = array($query, $result->rowCount() . " adet", round($time * 1000, 3) . " ms");
        return $result;
    }

    /**
     * @return LoggedPDOStatement
     */
    public function prepare($query) {
        return new LoggedPDOStatement(parent::prepare($query));
    }
    
    public static function printLog() {
        $totalTime = $totalRows = 0;

        
        foreach(self::$log as $entry) {
            $totalTime += $entry[2];
            $totalRows += $entry[1];
        }
        self::$log[] = array('TOPLAM', "$totalRows adet", " $totalTime ms");
        FB::table("Sorgular", self::$log);
    }
}

/**
* PDOStatement decorator that logs when a PDOStatement is
* executed, and the time it took to run
* @see LoggedPDO
*/
class LoggedPDOStatement {
    /**
     * The PDOStatement we decorate
     */
    private $statement;

    public function __construct(PDOStatement $statement) {
        $this->statement = $statement;
    }

    /**
    * When execute is called record the time it takes and
    * then log the query
    * @return PDO result set
    */
    public function execute() {
        $start = microtime(true);
        $result = $this->statement->execute();
        $time = microtime(true) - $start;
        LoggedPDO::$log[] = array('[PS]' . $this->statement->queryString, $this->statement->rowCount(),round($time * 1000, 3));
        return $result;
    }
    /**
    * Other than execute pass all other calls to the PDOStatement object
    * @param string $function_name
    * @param array $parameters arguments
    */
    public function __call($function_name, $parameters) {
        return call_user_func_array(array($this->statement, $function_name), $parameters);
    }
}


?>
