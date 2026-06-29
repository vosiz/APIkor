<?php

namespace Apikor\Deploy;

use Apikor\Tools\DbConnection;
use Vosiz\Utils\Io;

class Deployer {

    const INFO_TABLE = 'apikor_info';

    private $Db;
    private $MigPath;
    private $Dirs = [];


    /**
     * Constructor
     * @param DbConnection $db
     * @param string $mig_path Absolute path to migrations folder
     */
    public function __construct(DbConnection $db, string $mig_path) {

        $this->Db      = $db;
        $this->MigPath = $mig_path;
    }

    /**
     * Adds directory to be created on deploy
     * @param string $path Absolute path
     * @return Deployer
     */
    public function AddDir(string $path) {

        $this->Dirs[] = $path;
        return $this;
    }


    /**
     * Checks whether apikor DB is installed
     * @return bool
     */
    public function IsInstalled() {

        try {

            $this->Db->Query(self::INFO_TABLE)->Select(['current_version'])->Execute();
            return true;

        } catch(\Exception $exc) {

            return false;
        }
    }

    /**
     * Full install — creates directories and runs all migrations
     * @throws \Exception on SQL failure
     */
    public function Install() {

        $this->CreateDirs();
        $this->RunMigrations($this->GetFiles());
        $this->UpdateInfo();
    }

    /**
     * Update — creates directories and runs migrations newer than last update
     * SQL errors are non-fatal (duplicate tables etc.)
     */
    public function Update() {

        $this->CreateDirs();
        $since = $this->GetUpdatedAt();
        $this->RunMigrations($this->GetFiles($since), true);
        $this->UpdateInfo();
    }


    private function CreateDirs() {

        foreach($this->Dirs as $dir) {

            if(Io\Dir::Create($dir))
                Io\Path::SetPermissions($dir);
        }
    }

    /**
     * Returns migration files sorted chronologically
     * Optionally filters to only files newer than $since
     * @param string|null $since DateTime string (Y-m-d H:i:s)
     * @return array
     */
    private function GetFiles(?string $since = null) {

        $files = Io\Dir::GetFiles($this->MigPath);
        $files = array_values(array_filter($files, fn($f) => pathinfo($f, PATHINFO_EXTENSION) === 'sql'));
        sort($files);

        if($since === null)
            return $files;

        $since_stamp = (new \DateTime($since))->format('Ymd_His');
        return array_values(array_filter($files, function($f) use ($since_stamp) {

            return substr(pathinfo($f, PATHINFO_FILENAME), 0, 15) > $since_stamp;
        }));
    }

    /**
     * Runs SQL migration files
     * @param array $files Absolute paths to .sql files
     * @param bool $tolerant Ignore SQL errors when true
     * @throws \Exception on SQL failure when not tolerant
     */
    private function RunMigrations(array $files, bool $tolerant = false) {

        foreach($files as $file) {

            $statements = array_filter(array_map('trim', explode(';', file_get_contents($file))));
            foreach($statements as $stmt) {

                try {

                    $this->Db->Raw($stmt);

                } catch(\Exception $exc) {

                    if(!$tolerant) throw $exc;
                }
            }
        }
    }

    /**
     * Returns the last update timestamp from apikor_info
     * @return string|null
     */
    private function GetUpdatedAt() {

        $rows = $this->Db->Query(self::INFO_TABLE)->Select(['updated'])->Execute();
        return !empty($rows) ? $rows[0]->updated : null;
    }

    /**
     * Updates apikor_info updated timestamp
     */
    private function UpdateInfo() {

        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $this->Db->Raw("UPDATE " . self::INFO_TABLE . " SET updated = ?", [$now]);
    }

}
