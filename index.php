<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);


define('DATABASE', 'sr943');
define('USERNAME', 'sr943');
define('PASSWORD', 'SuVvKQn4');
define('CONNECTION', 'sql1.njit.edu');

$obj = new main();

class globals
{
  
  public static function all($html)

  {
    echo "$html"; 
  }


}


class main {
  public function __construct(){
   


    try {
    
      $html="";
    $html.= "<h3>Connected successfully </h3><hr>";    //conection message
    globals :: all($html);

    
    $td = new todo();
    $collection = new todos();
    $resultset = $collection->fetch();
    $line="<h3>All the records from todos table : </h3>";
    table :: printtable($resultset,$line);
    $id = 2;
    $resultset = $collection->fetchOne($id);
    $line="<h3>One record from todos table : </h3>";
    table :: printRow($resultset,$line);


    $ac = new accounts();
    $collection = new accounts();
    $resultset = $collection->fetch();
    $line="<h3>All the records from accounts table : </h3>";
    table :: printtable($resultset,$line);
    $id = 5;
    $resultset = $collection->fetchOne($id);
    $line="<h3>One record from accounts table : </h3>";
    table :: printRow($resultset,$line);

    $ac = new accounts();
    $collection = new accounts();
    $id = 13;
    $phone = "phone";
    $phoneno=5565656;
    
    $results = $collection->update($phone,$phoneno, $id);
    print_r($results);

    $line="<h3>Updated record : </h3>";    
    $resultset = $collection->fetchOne($id);         
    table :: printRow($resultset,$line);


 

  }
  catch(PDOException $e)
  {
    $html.= "<h3>Connection failed:</h3> <br>" . $e->getMessage();
  }
  
}

}


class dbConn{
    //variable to hold connection object.
    protected static $db;
    //private construct - class cannot be instatiated externally.
    private function __construct() {
        try {
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}






class table{
  public static function printtable($result,$line){
$html="";
      
      
      $html.= $line;
      $html.="<table style='border: 1px solid black'>";          
      foreach ($result as $row) {
        
        $html.="<tr>";

        foreach ($row as $column) {
          $html.="<td style='border: 1px solid black'>$column </td>";
          
        }
        $html.="</tr>";
      }    
      $html.="</table>";
      $html.="<br><hr>";

      globals :: all($html);

      
      $conn = null;
    }

    public static function printRow($result,$line){
$html="";
      
      
      $html.= $line;
      $html.="<table style='border: 1px solid black'>";     
      $html.="<tr>";     
      foreach ($result as $row) {
        
          $html.="<td style='border: 1px solid black'>$row </td>";
           
      } 
      $html.="</tr>";   
      $html.="</table>";
      $html.="<br><hr>";

      globals :: all($html);

      
      $conn = null;
    }

  }

class collection{


      static public function create() {
      $model = new static::$modelName;
      return $model;
    }

  public function fetch(){

   
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName;
      //  echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet;



    }
  

  
  public function fetchOne($id){


        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet[0];



    }
  

  
 public function update($phone, $phoneno,$id){

    $db = dbConn::getConnection();
        $tableName = get_called_class();        
        $sql = "UPDATE " . $tableName . " SET " . $phone . " = " . $phoneno . " WHERE id = ". $id;
        $statement = $db->prepare($sql);
        $statement->execute();        
        return 'successful!!!!';

    }
  
}

class accounts extends collection {
    protected static $modelName = 'account';
}
class todos extends collection {
    protected static $modelName = 'todo';
}
class model {
    protected $tableName;
    public function save()
    {
        if ($this->id = '') {
            $sql = $this->insert();
        } else {
            $sql = $this->update();
        }
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $statement->execute();
      }
    }


class account extends model {
    public $id ;
    public $email ;
    public $fname ;
    public $lname ;
    public $phone ;
    public $birthday ;
    public $gender ;
    public $password ;
    

    public function __construct(){

 
        $this->tableName = 'accounts';

}
}

class todo extends model {
    public $id;
    public $owneremail;
    public $ownerid;
    public $createddate;
    public $duedate;
    public $message;
    public $isdone;

    public function __construct(){

 
        $this->tableName = 'todos';

    }



}




?>