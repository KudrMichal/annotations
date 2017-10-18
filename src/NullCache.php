<?php

namespace KudrMichal\Annotations;

/**
 * Class NullCache
 *
 * Cache fake
 * Good for testing or something
 *
 * @author Michal Kudr
 * @package KudrMichal\Annotations
 */
class NullCache implements ICache {

    /**
     * @inheritdoc
     */
    public function read($key) {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function write($key, $value) {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function delete($key) {}

    /**
     * @inheritdoc
     */
    public function clean() {}


}