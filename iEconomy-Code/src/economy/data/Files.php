<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 17:37
 */

namespace economy\data;


trait Files
{

    /**
     * @return string
     */
    public function getAccountFolder(): string {
        /** @var DataProvider $this */
        return $this->getPlugin()->getDataFolder() . "accounts/";
    }

    public function getAccountFile(string $owner, bool $createDirectory = true): string {
        /** @var DataProvider $this */
        $owner = strtolower($owner);
        $dir = $this->getAccountFolder() . substr($owner, 0, 1);
        if(!is_dir($dir) && $createDirectory) {
            @mkdir($dir);
        }
        return $dir . "/".$owner.$this->getExtension();
    }

    public function accountExists(string $owner): bool {
        return file_exists($this->getAccountFile($owner));
    }

}