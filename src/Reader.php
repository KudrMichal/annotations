<?php

namespace KudrMichal\Annotations;

use KudrMichal\Annotations\Exceptions\AnnotationNotFound;

/**
 * Class Reader
 *
 * Annotations reading
 *
 * @author Michal Kudr kudrmichal@gmail.com
 * @package KudrMichal\Annotations
 *
 * @todo cache
 */
class Reader {

    /** @var ICache */
    private $cache;

    /** @var [] Memory cache */
    private $loaded;

    /**
     * Reader constructor.
     * @param ICache $cache
     */
    public function __construct(ICache $cache = NULL) {
        //if no cache, set fake cache
        $this->cache = $cache ?: new NullCache();
    }

    /**
     * Returns all class annotations in array key => annotation and value => annotation text if exists
     *
     * @param string $class
     * @return string[]
     */
    public function getClassAnnotations(string $class) : AnnotationCollection {
        //search in memory
        if (isset($this->loaded[$class])) {
            return $this->loaded[$class];
        }

        if (!$annotations = $this->cache->read($class)) {
            $refl = new \ReflectionClass($class);
            $annotations = $this->parse($refl->getDocComment());
            $this->cache->write($class, $annotations);
        }
        return $this->loaded[$class] = $annotations;
    }

    /**
     * Returns class annotation value by name
     * @param string $class
     * @param string $annotation
     * @return NULL|string
     * @throws AnnotationNotFound
     */
    public function getClassAnnotation(string $class, string $annotation) {
        if (!$this->hasClassAnnotation($class, $annotation)) {
            throw new AnnotationNotFound("Class $class doesn't contain annotation $annotation");
        }
        $annotations = $this->getClassAnnotations($class);
        return $annotations[$annotation];
    }

    /**
     * Has class annotation with specific name?
     * @param string $class
     * @param string $annotation
     * @return bool
     */
    public function hasClassAnnotation(string $class, string $annotation) {
        return isset($this->getClassAnnotations($class)[$annotation]);
    }

    /**
     * Returns method annotations in array key => annotation and value => annotation text if exists
     * @param string $class
     * @param string $method
     * @return string[]
     */
    public function getMethodAnnotations(string $class, string $method) : AnnotationCollection {
        //method cache key
        $cacheKey = "$class-method-$method";
        //search in memory cache
        if (isset($this->loaded[$cacheKey])) {
            return $this->loaded[$cacheKey];
        }

        if (!$annotations = $this->cache->read($cacheKey)) {
            $refl = new \ReflectionMethod($class, $method);
            $annotations = $this->parse($refl->getDocComment());
            $this->cache->write($cacheKey, $annotations);
        }
        //save in memory and return
        return $this->loaded[$cacheKey] = $annotations;
    }

    /**
     * Returns class annotation value by name
     * @param string $class
     * @param string $method
     * @param string $annotation
     * @return NULL|string
     * @throws AnnotationNotFound
     */
    public function getMethodAnnotation(string $class, string $method, string $annotation) {
        $annotations = $this->getMethodAnnotations($class, $method, $annotation);
        if (!$this->hasMethodAnnotation($class, $method, $annotation)) {
            throw new AnnotationNotFound("Method $class::$method doesn't contain annotation $annotation");
        }
        return isset($annotations[$annotation]) ? $annotations[$annotation] : NULL;
    }

    /**
     * Has method specific annotation?
     * @param string $class
     * @param string $method
     * @param string $annotation
     * @return bool
     */
    public function hasMethodAnnotation(string $class, string $method, string $annotation) {
        return isset($this->getMethodAnnotations($class, $method)[$annotation]);
    }


    /**
     * Returns property annotations in array key => annotation and value => annotation text if exists
     * @param string $class
     * @param string $property
     * @return string[]
     */
    public function getPropertyAnnotations(string $class, string $property) : AnnotationCollection {
        //property cache key
        $cacheKey = "$class-property-$property";
        //search in memory cache
        if (isset($this->loaded[$cacheKey])) {
            return $this->loaded[$cacheKey];
        }

        if (!$annotations = $this->cache->read($cacheKey)) {
            $refl = new \ReflectionProperty($class, $property);
            $annotations = $this->parse($refl->getDocComment());
            $this->cache->write($cacheKey, $annotations);
        }
        //save in memory and return
        return $this->loaded[$cacheKey] = $annotations;
    }

    /**
     * Get specific annotation from property doc
     * @param string $class
     * @param string $property
     * @param string $annotation
     * @return mixed|string
     * @throws AnnotationNotFound
     */
    public function getPropertyAnnotation(string $class, string $property, string $annotation) {
        if (!$this->hasPropertyAnnotation($class, $property, $annotation)) {
            throw new AnnotationNotFound("Class $class doesn't contain property $annotation");
        }
        $annotations = $this->getPropertyAnnotations($class, $property);
        return $annotations[$annotation];
    }

    /**
     * Has property specific annotation?
     * @param string $class
     * @param string $property
     * @param string $annotation
     * @return bool
     */
    public function hasPropertyAnnotation(string $class, string $property, string $annotation) {
        return isset($this->getPropertyAnnotations($class, $property)[$annotation]);
    }

    /**
     * Parse doc block
     * @param string $annotationText
     * @return string[]
     */
    protected function parse($annotationText) : AnnotationCollection {
        $annotations = [];

        //annotation block doesn't contain @ annotation
        if (strpos($annotationText, "@") === FALSE) {
            return new AnnotationCollection();
        }
        //look for @annotations in every doc row
        foreach(explode("\n", $annotationText) as $row) {
            preg_match_all("/@([\\w-_]+)(\\s+([^@]+))?/", $row, $matches);
            //$matches[1] contains annotation names, $matches[2] contains their text values
            foreach($matches[1] as $index => $match) {
                $annotations[$match] = empty(trim($matches[2][$index])) ? NULL : trim($matches[2][$index]);
            }
        }

        return new AnnotationCollection($annotations);
    }

    /**
     * Clear all cached annotations
     * @return void
     */
    public function clearCache() {
        $this->loaded = [];
        $this->cache->clean();
    }


}