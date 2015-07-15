<?php
/**
 * User: Nahid Hossain
 * Email: mail@akmnahid.com
 * Phone: +880 172 7456 280
 * Date: 7/8/2015
 * Time: 11:28 PM
 */

class DataManagementQueries extends CI_Model {

    public static $dbObj=null;

    public function __construct(){
        self::$dbObj = $this->db;
    }


    /**
     * Returns The object Lists
     * @return mixed
     */
    public static function getObjectsList($display=true){
        return self::$dbObj->query("SELECT * FROM EASOL.[Schema] WHERE TableType='Primary' ".(($display==true) ? " AND Display=1 " : "")." ORDER BY TableName")->result();
    }
    public static function getAssociationsList($display=true){
        return self::$dbObj->query("SELECT * FROM EASOL.[Schema] WHERE TableType='Association' ".(($display==true) ? " AND Display=1 " : "")." ORDER BY TableName")->result();
    }

    public static function getTypesList($display=true){
        return self::$dbObj->query("SELECT * FROM EASOL.[Schema] WHERE TableType='Type' ".(($display==true) ? " AND Display=1 " : "")." ORDER BY TableName")->result();
    }

    public static function getDescriptorsList($display=false){
        return self::$dbObj->query("SELECT * FROM EASOL.[Schema] WHERE TableType='Descriptor' ".(($display==true) ? " AND Display=1 " : "")." ORDER BY TableName")->result();
    }

    public static function getTableDetails($tableName){

        return self::$dbObj->query("SELECT COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = ?
ORDER BY ORDINAL_POSITION",[$tableName])->result();

    }

    public static function getTableData($tableName,$start=0,$pageSize=50){

        return self::$dbObj->query("SELECT * FROM edfi.".addslashes($tableName)." ORDER BY (SELECT 0) OFFSET ".(((int)$start-1)*(int)$pageSize)." ROWS FETCH NEXT ".(int)$pageSize." ROWS ONLY")->result();

    }
    public static function getAllTableData($tableName){

        return self::$dbObj->query("SELECT * FROM edfi.".addslashes($tableName))->result();

    }

    public static function getTableDataCount($tableName){
        return self::$dbObj->query("SELECT COUNT(*) as total FROM edfi.".addslashes($tableName))->row();
    }
}