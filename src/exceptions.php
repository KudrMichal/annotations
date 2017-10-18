<?php

namespace KudrMichal\Annotations\Exceptions;

/**
 * Class AnnotationsException
 *
 * Base exception
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Annotations\Exceptions
 */
class AnnotationsException extends \Exception {

}

/**
 * Class CacheException
 *
 * Cache problem, for example temp directory doesn't exist
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Annotations\Exceptions
 */
class CacheException extends AnnotationsException {

}

/**
 * Class AnnotationNotFound
 *
 * Annotation not found in doc
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Annotations\Exceptions
 */
class AnnotationNotFound extends AnnotationsException {

}

/**
 * Class IllegalOperationException
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Annotations\Exceptions
 */
class IllegalOperationException extends AnnotationsException {

}