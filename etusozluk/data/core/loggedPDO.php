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
        $start = microtime(true); // Zamanlayiciyi baslat
            $result = parent::query($query);
        $time = microtime(true) - $start; // Zamanlayiciyi durdur
        
        // Sorgu basamaklarini bul
        $explain = parent::query("EXPLAIN $query")->fetchAll(PDO::FETCH_ASSOC);
        self::$log[] = array($query, $result->rowCount() . " adet", round($time * 1000, 3) . " ms", $explain);
        
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
        $i = 0;
        foreach(self::$log as $entry) { $i++;
            $exp = array();
            $exp[] = array('ID', 'Select Tipi', 'Tablo', 'Tip', 'Olası Anahtarlar', 'Anahtar', 'Anahtar Boyutu', 'Ref', 'Satır', 'Bilgi');
            
            $exp = array_merge($exp,
                    $entry[3]
                    );
            FB::table("[$i] {$entry[0]} | Toplam {$entry[1]} satır {$entry[2]} ms sürdü" , $exp);
            $totalTime += $entry[2];
            $totalRows += $entry[1];
        }
        //self::$log[] = array('TOPLAM', "$totalRows adet", " $totalTime ms");
        //FB::table("Sorgular", self::$log);
        FB::info("Toplam $i sorgu $totalRows adet satırı $totalTime ms'de döndürdü");
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
        
        $explain = parent::query("EXPLAIN $this->statement->queryString")->fetchAll(PDO::FETCH_ASSOC);
        LoggedPDO::$log[] = array('[PS]' . $this->statement->queryString, $this->statement->rowCount(),round($time * 1000, 3),$explain);
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
