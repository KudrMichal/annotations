<?php

namespace KudrMichal\Anotations\Tests;

include __DIR__ . "/../bootstrap.php";

use KudrMichal\Annotations\AnnotationCollection;
use KudrMichal\Annotations\Exceptions\IllegalOperationException;
use Tester\Assert;
use Tester\TestCase;

/**
 * Class AnnotationCollectionTest
 *
 * Test collection behavior
 *
 * @author Michal Kudr (kudrmichal@gmail.com)
 * @package KudrMichal\Anotations\Tests
 */
class AnnotationCollectionTest extends TestCase {

    /**
     * Test collection operations
     * @return void
     */
    public function testCollection() {
        $annotations = new AnnotationCollection(['first' => "firstValue", 'second' => "secondValue"]);

        Assert::same(2, count($annotations));
        Assert::same(2, $annotations->count());
        Assert::true($annotations->contains("first"));
        Assert::true($annotations->contains("second"));
        Assert::false($annotations->contains("third"));

        Assert::same("firstValue", $annotations['first']);
        Assert::same("firstValue", $annotations->get("first"));

        Assert::same("secondValue", $annotations['second']);
        Assert::same("secondValue", $annotations->get("second"));

        Assert::exception(function() use ($annotations) {
            $annotations['third'] = "thirdValue";
        }, IllegalOperationException::class);

        Assert::exception(function() use ($annotations) {
            unset($annotations['thirdValue']);
        }, IllegalOperationException::class);

        foreach($annotations as $annotation => $value) {
            Assert::true(is_string($annotation));
            Assert::true(is_null($value) || is_string($annotation));
        }

    }

}

(new AnnotationCollectionTest())->run();