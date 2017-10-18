<?php

namespace KudrMichal\Annotations;

/**
 * Interface ICache
 *
 * Cache storage
 *
 * @author Michal Kudr
 * @package KudrMichal\Annotations
 */
interface ICache {

    /**
     * @param $key
     * @return mixed
     */
    public function read($key);

    /**
     * @param string $key
     * @param $value
     * @return void
     */
    public function write($key, $value);

    /**
     * Delete data
     * @param string $key
     * @return void
     */
    public function delete($key);

    /**
     * Delete all cached data
     * @return void
     */
    public function clean();

}