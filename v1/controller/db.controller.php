<?php
include '../config/config.php';

class Database {
    // For possible scaling (Possible slave setup later)
    private static $writeDBConnection;
    private static $readDBConnection;

    // Singleton For an instance of a connection
    public static function connectWriteDB()
    {
        global $conf;
        // Check if null
        if(self::$writeDBConnection === null)
        {
            //Connect to Database
            self::$writeDBConnection = new PDO($conf['con_str'], $conf['user'], $conf['pwd']);
            //Set Attributes for PDO
            self::$writeDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$writeDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        //return connection
        return self::$writeDBConnection;
    }

    public static function connectReadDB()
    {
            global $conf;
           // Check if null
           if(self::$readDBConnection === null)
           {
               //Connect to Database
               self::$readDBConnection = new PDO($conf['con_str'], $conf['user'], $conf['pwd']);
               //Set Attributes for PDO
               self::$readDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               self::$readDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
           }
           //return connection
           return self::$readDBConnection;
    }
}
