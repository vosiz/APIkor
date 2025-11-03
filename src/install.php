<?php

namespace Apikor;

class EngineInstall {

    private $Engine;

    /**
     * Constructor
     */
    public function __construct() {

        $this->Engine = Engine::GetSingleton();
    }

    /**
     * Installation of main database
     * @throws DbException
     * @throws ConfigException
     * @throws \Exception
     */
    public function MainDatabase() {

        try {

            $cfg = $this->Engine->GetLocalConfig();
            $server = $cfg->GetDataValue("MysqlHost");
            $port = $cfg->GetDataValue("MysqlPort");
            $user = $cfg->GetDataValue("MysqlUser");
            $pass = $cfg->GetDataValue("MysqlPassword");
            $main_db = $cfg->GetDataValue("MysqlDb");

            $conn = null;
            if ($port === "") {
                $conn = new \mysqli($server, $user, $pass);
            } else {
                $conn = new \mysqli($server, $user, $pass, "", (int)$port);
            }

            if($conn->connect_error) {

                throw new DbException("Cannot connect to MySql server: ".$conn->connect_error);
            }

            // create database first
            $sql = "CREATE DATABASE IF NOT EXISTS `$main_db` CHARACTER SET utf8 COLLATE utf8_general_ci";
            $this->QueryMySql($conn, $sql);
            print_r("Database $main_db installed\n");

            $sql = "USE `$main_db`";
            $this->QueryMySql($conn, $sql);

            // add tables and data - migrations
            $db_path = __DIR__.'/'.$cfg->GetDataValue("DbMigdirPath");
            $files = Tools\FILEOPS_GetFiles($db_path, 'sql');
            if(!$files)
                throw new ConfigException("No files on path $db_path");
            
            foreach($files as $file) {

                $this->RunMigration($conn, $file);
            }
            print_r("Import done");

        } catch(\Exception $exc) {

            throw $exc;

        } finally {

            if(!is_null($conn))
                $conn->close();
        }
        
    }


    /**
     * Executes MySQL query
     * @param \mysqli $mysqli Instance of mysqli
     * @param string $sql Sql query
     * @throws \Exception
     */
    private function QueryMySql(\mysqli $mysqli, string $sql) {

        $result = $mysqli->query($sql);
        if($result === FALSE) {

            throw new \Exception("Query failed: '$sql', [$mysqli->errno] $mysqli->error");
        }
    }

    /**
     * Runs single migration
     * @param \mysqli $mysqli Instance of mysqli
     * @param string $file Path to migration file
     * @throws \Exception
     * @throws DbException
     */
    private function RunMigration(\mysqli $mysqli, string $file) {

        try {

            $sql = file_get_contents($file);
            if (!$sql) {
                throw new \Exception("Cannot read file $file");
            }
    
            $sql = preg_replace('/\/\*\![0-9]{5} .*?\*\//s', '', $sql);
            $statements = array_filter(array_map('trim', explode(';', $sql)));
    
            if (empty($statements)) {
                throw new \Exception("No SQL statements found in $file");
            }
    
            $mysqli->begin_transaction();
    
            foreach ($statements as $stmt) {

                if ($stmt === '') 
                    continue; 
    
                if (!$mysqli->query($stmt)) {

                    $mysqli->rollback();
                    throw new \Exception("SQL error ({$mysqli->errno}): {$mysqli->error}\nStatement: $stmt");
                }
            }
    
            $mysqli->commit();
            print_r("Migration done");
    
        } catch (\Exception $exc) {
            
            if ($mysqli->errno) {
                $mysqli->rollback();
            }
            throw new DbException(sprintf("Migration failure for file %s: %s", $file, $exc->getMessage()));
        }
    }
    
}