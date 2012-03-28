<?php
/**
 * http://www.coderholic.com/php-database-query-logging-with-pdo/ adresindeki koddan ilham
 * alınarak düzeltilmiş ve geliştirilmiştir.
 * @version 0.7
 */


/**
* PDO sınıfını extend ederek bütün log bilgilerini FirePHP konsoluna basar.
 *@see PDO
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
        if (!count(self::$log))
            FB::info ("Hiç sorgu çalıştırılmadı");
        else
            self::printLog();
    }
    
    public function query($query) {
        $start = microtime(true); // Zamanlayiciyi baslat
            try {
                $result = parent::query($query);
            } catch (PDOException $e) {
                FB::error($e, "HATA: $query");
                
            }
        $time = microtime(true) - $start; // Zamanlayiciyi durdur
        
        // Sorgu basamaklarini bul
        $explain = parent::query("EXPLAIN $query")->fetchAll(PDO::FETCH_ASSOC); 
        self::$log[] = array($query, $result->rowCount() . " adet", round($time * 1000, 3) . " ms", $explain);
        
        return $result;
    }

    /**
     * @return LoggedPDOStatement
     */
    public function prepare($query,$options=NULL) {
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
 * PDOStatement sınıfını hem loglanabilecek hemde query plan alabilecek şekilde dekore ettim.
 * Bu dekorasyon loglama işini LoggedPDO sınıfına devrediyor.  
 * 
* @see LoggedPDO
*/
class LoggedPDOStatement {
    /**
     * The PDOStatement we decorate
     */
    private $statement;
    /**
     * Explain'de kullanılacak sorgunun parametrelerini tutan array
     * @var array
     */
    private $bindPairs;

    public function __construct(PDOStatement $statement) {
        $this->statement = $statement;
        $this->bindPairs = array();
        $this->pseudoQuery = "";
    }

    /**
    * Çalıştırılan sorguyu süreleri ile beraber log'a gönderir.
    * Suni sorguyu oluşturarak Explain yapar.
     * 
    * @return PDO result set
    */
    public function execute(array $input_parameters = null) {
        $start = microtime(true);
            try {
                $result = $this->statement->execute();
            } catch (PDOException $e) {
                FB::error($e, "Hata: " . $this->statement->queryString);
                return;
            }
        $time = microtime(true) - $start;
        
        if ($input_parameters != null)
        	$this->bindPairs = array_merge($this->bindPairs, $input_parameters);
        
        if (strpos(strtolower(trim($this->statement->queryString)), strtolower("EXPLAIN")) !== false ) {
        $parsedQuery =  str_replace(
                        array_keys($this->bindPairs),
                        array_values($this->bindPairs),
                        $this->statement->queryString
                        );
                
        $explain = getQueryPlan($parsedQuery);
        LoggedPDO::$log[] = array('[PS]' . $parsedQuery, $this->statement->rowCount(),round($time * 1000, 3),$explain);
        }
        return $result;
    }
    
    
    public function bindValue ($parameter, $value, $data_type = PDO::PARAM_STR) {
        
        $this->bindPairs[$parameter] = $data_type == PDO::PARAM_STR ?  "'$value'" : $value;
        
        $this->statement->bindValue($parameter, $value, $data_type);
                
    }
    public function bindParam ($parameter, &$variable, $data_type = null, $length = null, $driver_options = null) {
        throw new Exception("bindParam fonksiyonu loggedPHP'de henuz desteklenmemektedir. Bunun yerine bindValue fonksiyonunu kullanınız", 1002);
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

function getQueryPlan($query,$fetch_style = PDO::FETCH_ASSOC) {
	$result = array();
	try {
	    $db = getPDO(true);
	    $query = "EXPLAIN $query";
	    $st = $db->query($query);
	    $result = $st->fetchAll($fetch_style);
	} catch (PDOException $e) {
		
		FB::error($e);
		//die('hata');
	}
	return $result;
}

?>
