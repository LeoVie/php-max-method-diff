<?php

namespace LeoVie\PHPMaxMethodDiff\Tests;

use LeoVie\PHPMaxMethodDiff\MethodDiffFactory;
use PHPUnit\Framework\TestCase;

class MethodDiffFactoryTest extends TestCase
{
    /** @var MethodDiffFactory */
    private $methodDiffFactory;

    protected function setUp(): void
    {
        $this->methodDiffFactory = new MethodDiffFactory();
    }

    public function test_createMethodDiff_returnsExpected(): void
    {
        $methodName = 'foo';
        $expectedClass = __DIR__ . '/testdata/ExpectedTestClass.php';
        $actualClass = __DIR__ . '/testdata/ActualTestClass.php';

        $methodDiff = $this->methodDiffFactory->createMethodDiff($methodName, $expectedClass, $actualClass);

        $expectedAddedLines = 2;
        $expectedDeletedLines = 1;
        $expectedDiff =
            "--- Original\n+++ New\n@@ @@\n print 'This';\n print 'is';\n print 'the';\n-print 'expected';\n+print 'actual';\n print 'method!';\n+print '#wubwub';\n";

        self::assertEquals($methodName, $methodDiff->getName());
        self::assertEquals($expectedAddedLines, $methodDiff->getAddedLines());
        self::assertEquals($expectedDeletedLines, $methodDiff->getDeletedLines());
        self::assertEquals($expectedDiff, $methodDiff->getDiff());
    }
}