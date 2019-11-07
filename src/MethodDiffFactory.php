<?php

namespace LeoVie\PHPMaxMethodDiff;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class MethodDiffFactory
{
    public function createMethodDiff(string $methodName, string $expectedClassPath, string $actualClassPath): MethodDiff
    {
        $expectedMethodContent = $this->getMethodContent($methodName, $expectedClassPath);
        $actualMethodContent = $this->getMethodContent($methodName, $actualClassPath);

        return new MethodDiff($methodName, $expectedMethodContent, $actualMethodContent);
    }

    private function getMethodContent(string $methodName, string $classPath): string
    {
        $code = file_get_contents($classPath);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return '';
        }

        $traverser = new NodeTraverser;
        $visitor = new MethodVisitor($methodName);
        $traverser->addVisitor($visitor);

        $traverser->traverse($ast);

        return $visitor->getMethodContent();
    }
}