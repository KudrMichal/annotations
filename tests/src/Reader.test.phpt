<?php


namespace KudrMichal\Anotations\Tests;

include __DIR__ . "/../bootstrap.php";

use KudrMichal\Annotations\Exceptions\AnnotationNotFound;
use KudrMichal\Annotations\FileCache;
use KudrMichal\Annotations\Reader;
use Tester\Assert;
use Tester\TestCase;

/**
 * Class ReaderTest
 *
 * Test annotation reading
 *
 * @author Michal
 * @package KudrMichal\Anotations\Tests
 * @todo test todo
 *
 * @test @test2 test2
 * michal je bůh
 * @XML-Element test_element
 */
class ReaderTest extends TestCase {

    /** @var Reader */
    private $reader;

    /** @var Reader */
    private $fileCacheReader;

    /**
     * @propertyTest propTest
     * something
     * @propertyTest2 @propertyTest3 propTest3
     * @var mixed
     */
    private $testProperty;

    /**
     * ReaderTest constructor.
     */
    public function __construct() {
        //null cache reader
        $this->reader = new Reader();
        //filesystem cache
        $this->fileCacheReader = new Reader(new FileCache(TEMP_DIR));
    }

    public function testClass() {
        $begin = microtime(TRUE);
        $annotations = $this->reader->getClassAnnotations(self::class);
        var_dump(microtime(TRUE) - $begin);
        Assert::true($this->reader->hasClassAnnotation(self::class, "author"));
        Assert::true($this->reader->hasClassAnnotation(self::class, "package"));
        Assert::true(isset($annotations["author"]));
        Assert::same("Michal", $annotations["author"]);
        Assert::true(isset($annotations["package"]));
        Assert::same("KudrMichal\Anotations\Tests", $annotations["package"]);
        Assert::true(isset($annotations["todo"]));
        Assert::same("test todo", $annotations["todo"]);
        Assert::null($annotations['test']);
        Assert::same("test2", $annotations['test2']);
        Assert::exception(function() {
            $this->reader->getClassAnnotation(self::class, "michal");
        }, "\\KudrMichal\\Annotations\\Exceptions\\AnnotationNotFound");

        $begin = microtime(TRUE);
        $annotations1 = $this->fileCacheReader->getClassAnnotations(self::class);
        var_dump(microtime(TRUE) - $begin);
        Assert::true(isset($annotations["author"]));
        Assert::same("Michal", $annotations["author"]);

        Assert::same("test_element", $annotations["XML-Element"]);

        //5 annotations
        Assert::same(6, count($annotations1));
        $begin = microtime(TRUE);
        $annotations2 = $this->fileCacheReader->getClassAnnotations(self::class);
        var_dump(microtime(TRUE) - $begin);
        //5 annotations
        Assert::same(6, count($annotations2));


        Assert::null($this->reader->getClassAnnotation(self::class, "test"));
        Assert::same("Michal", $this->reader->getClassAnnotation(self::class, "author"));

        //Assert::true(FALSE);

    }

    /**
     * Test method annotation reading
     * @methodTest test
     * @return void
     */
    public function testMethod() {
        $annotations = $this->reader->getMethodAnnotations(self::class, __FUNCTION__);
        Assert::same(2, count($annotations));
        Assert::same("test", $annotations['methodTest']);
        Assert::same("void", $annotations['return']);
    }

    /**
     * Test property annotation reading
     * @return void
     */
    public function testProperty() {
        $annotations = $this->reader->getPropertyAnnotations(self::class, "testProperty");

        Assert::count(4, $annotations);
        Assert::same("propTest", $annotations['propertyTest']);
        Assert::null($annotations['propertyTest2']);
        Assert::same("propTest3", $annotations['propertyTest3']);
    }


}

(new ReaderTest())->run();