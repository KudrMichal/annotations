<?php

namespace KudrMichal\Annotations;

/**
 * Class Annotation
 *
 * Annotation object reprezentation
 *
 * @author Michal Kudr
 * @package KudrMichal\Annotations
 */
class Annotation {

    /**
     * Annotation name without @
     * @var string
     */
    private $name;

    /**
     * Annotation text value
     * @var string | null
     */
    private $value;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string | null
     */
    public function getValue() {
        return $this->value;
    }



}