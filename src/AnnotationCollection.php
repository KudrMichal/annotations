<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 10/12/17
 * Time: 8:53 PM
 */

namespace KudrMichal\Annotations;


use KudrMichal\Annotations\Exceptions\IllegalOperationException;
use Traversable;

class AnnotationCollection implements \ArrayAccess, \Countable, \IteratorAggregate {

    /** @var string Annotations array */
    private $annotations;

    /**
     * AnnotationCollection constructor.
     * @param $annotations
     */
    public function __construct(array $annotations = []) {
        $this->annotations = $annotations;
    }

    /**
     * Get annotation value
     * @param string $annotation
     * @return string | NULL
     * @todo throw exception if key does not exist
     */
    public function get(string $annotation) {
        return $this->annotations[$annotation];
    }

    /**
     * Annotation exists?
     * @param string $annotation
     * @return bool
     */
    public function contains(string $annotation) : bool {
        return array_key_exists($annotation, $this->annotations);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->annotations);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset) {
        return $this->contains($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value) {
        throw new IllegalOperationException("AnnotationCollection is immutable!");
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset) {
        throw new IllegalOperationException("AnnotationCollection is immutable!");
    }

    /**
     * @inheritdoc
     */
    public function current() {
        current($this->annotations);
    }

    /**
     * @inheritdoc
     */
    public function next() {
        next($this->annotations);
    }

    /**
     * @inheritdoc
     */
    public function key() {
        key($this->annotations);
    }

    /**
     * @inheritdoc
     */
    public function valid() {
        valid($this->annotations);
    }

    /**
     * @inheritdoc
     */
    public function rewind() {
        rewind($this->annotations);
    }


    /**
     * @inheritdoc
     */
    public function getIterator() {
        return new \ArrayIterator($this->annotations);
    }


}