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

    $q= new All();
    $q-> query();

   
  }
  catch(PDOException $e)
  {
    $html.= "<h3>Connection failed:</h3> <br>" . $e->getMessage();
  }
  
}

}


class All{

public function query(){

  $collection = new todos();
    $resultset = $collection->fetch();
    $line="<h3>All the records from todos table : </h3>";
    table :: printtable($resultset,$line);
    $id = 2;
    $resultset = $collection->fetchOne($id);
    //print_r(  $resultset )  ;
    $line="<h3>One record from todos table : </h3>";
    table :: printtable($resultset,$line);


    //$ac = new accounts();
    $collection1 = new accounts();
    $resultset = $collection1->fetch();
    $line="<h3>All the records from accounts table : </h3>";
    table :: printtable($resultset,$line);
    $id = 5;
    $resultset = $collection1->fetchOne($id);
    $line="<h3>One record from accounts table : </h3>";
    table :: printtable($resultset,$line);

    $acObj = new account();
    $acObj->id = 12;
    $acObj->save();
    
    $line="<h3>Phone number updated for id 12 from accounts table : </h3>";  
    $collection1 = new accounts();
    $resultset = $collection1->fetchOne(12);      
    //print_r($resultset);   
    table :: printtable($resultset,$line);


    $td = new todo();
    $collection = new todos();
    $id = 6;
    $results = $td->delete($id);
    print_r($results);
    $line="<h3>Record deleted from todos table where id is 6 : </h3>";    
    $resultset = $collection->fetch();         
    table :: printtable($resultset,$line);


    //$td = new todo();
    //$collection = new todos();
    
    $td->id=17;
    $results = $td->delete($id);
    $results = $td->save();
    
    $line="<h3>Record inserted in todos table for id = 17 : </h3>";    
    $resultset = $collection->fetch();         
    table :: printtable($resultset,$line);

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
        return $recordsSet;



    }
  

  
 /*public function update($phone, $phoneno,$id){

        $db = dbConn::getConnection();
        $tableName = get_called_class();        
        $sql = "UPDATE " . $tableName . " SET " . $phone . " = " . $phoneno . " WHERE id = ". $id;
        $statement = $db->prepare($sql);
        $statement->execute();        
        return 'successful!!!!';

    }

  public function delete($id){

        $db = dbConn::getConnection();
        $tableName = get_called_class();        
        $sql = "DELETE FROM " . $tableName .  " WHERE id = ". $id;
        $statement = $db->prepare($sql);
        $statement->execute();        
        return 'Deleted';

    }


    public function insert($string){

        $db = dbConn::getConnection();
        $tableName = get_called_class();     
        //$sql="";   
        $sql = "INSERT INTO " . $tableName .  " VALUES ("  . $string . ")";
        $statement = $db->prepare($sql);
        $statement->execute();        
        return 'Inserted';

    }*/
  
}

class accounts extends collection {
    protected static $modelName = 'account';
}
class todos extends collection {
    protected static $modelName = 'todo';
}
class model {
    protected $tableName;
    static $column;
    static $newphnNo;
    public function save()
    {

        if ($this->id == '') {
            $sql = $this->insert();
        } else {
            $sql = $this->update();
        }
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $statement->execute();
        
      }


       public function update(){

        //$db = dbConn::getConnection();
       $tableName = $this->tableName;         
        $sql = "Update ". $tableName . " SET ".static::$column."='".static::$newphnNo."' WHERE id=" . $this->id;
        //$statement = $db->prepare($sql);
        //$statement->execute();        
        return $sql;

    }

  public function delete($id){

        $db = dbConn::getConnection();
        $tableName = $this->tableName;        
        $sql = "DELETE FROM " . $tableName .  " WHERE id = ". $id;
        $statement = $db->prepare($sql);
        $statement->execute();        
        return 'Deleted';

    }


    public function insert(){

       // $db = dbConn::getConnection();
        $tableName = $this->tableName;      
        //$sql="";   
        $sql = "INSERT INTO " . $tableName .  " VALUES ("  . static :: $string . ")";
       // $statement = $db->prepare($sql);
        //$statement->execute();        
        return $sql;

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

    static $column = 'phone';
    static $newphnNo ='0258963147';
    

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

   static $string = '20,"new@njit.edu",14,"2017-11-19","2017-11-19","new inserted row",0';
    

    public function __construct(){

 
        $this->tableName = 'todos';

    }



}




?>