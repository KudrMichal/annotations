<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 10/11/17
 * Time: 11:28 PM
 */

namespace KudrMichal\Annotations;

/**
 * Class FileCache
 *
 * Simple filesystem cache
 *
 * @author Michal Kudr
 * @package KudrMichal\Annotations
 */
class FileCache implements ICache {

    /** @var string Filesystem cache path */
    private $cachePath;

    /**
     * FileCache constructor.
     * @param string $cachePath
     */
    public function __construct(string $cachePath) {
        //check if temp dir exists
        if (!is_null($cachePath) && !file_exists($cachePath)) {
            throw new CacheException("Temporary directory $cachePath not found!");
        }
        $this->cachePath = "$cachePath/KudrMichal-annotations";
        @mkdir($this->cachePath, 0777, TRUE);
    }

    /**
     * @inheritdoc
     */
    public function read($key) {
        $key = md5($key);
        if (file_exists($this->cachePath . "/$key")) {
            return unserialize(file_get_contents($this->cachePath . "/$key"));
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function write($key, $value) {
        file_put_contents($this->cachePath . "/" . md5($key), is_object($value) || is_array($value) ? serialize($value) : $value);
    }

    /**
     * @inheritdoc
     * @todo
     */
    public function clean() {

    }

    /**
     * @inheritdoc
     */
    public function delete($key) {
        rmdir($this->cachePath . "/" . md5($key));
    }


}