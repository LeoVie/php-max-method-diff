<?php

namespace LeoVie\PHPMaxMethodDiff;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;

class MethodVisitor extends NodeVisitorAbstract
{
    /** @var string */
    private $searchMethodName;

    /** @var string */
    private $methodContent;

    public function __construct(string $searchMethodName)
    {
        $this->searchMethodName = $searchMethodName;
    }

    public function getMethodContent(): string
    {
        return $this->methodContent;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            if ((string)$node->name === $this->searchMethodName) {
                $statements = $node->getStmts();

                $prettyPrinter = new PrettyPrinter\Standard;
                $this->methodContent = $prettyPrinter->prettyPrint($statements);
            }
        }
        return $node;
    }
}