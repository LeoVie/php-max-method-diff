<?php

namespace LeoVie\PHPMaxMethodDiff;

use SebastianBergmann\Diff\Differ;

class MethodDiff
{
    private $name;

    /** @var string */
    private $expectedContent;

    /** @var string */
    private $actualContent;

    /** @var string */
    private $diff;

    /** @var int */
    private $deletedLines = 0;

    /** @var int */
    private $addedLines = 0;

    /** @var Differ */
    private $differ;

    public function __construct(string $name, string $expectedContent, string $actualContent)
    {
        $this->name = $name;

        $this->expectedContent = $this->normalizeLineEndings($expectedContent);
        $this->actualContent = $this->normalizeLineEndings($actualContent);

        $this->differ = new Differ();

        $this->diff = $this->differ->diff($this->expectedContent, $this->actualContent);

        $this->determineLineDiffs();
    }

    private function normalizeLineEndings(string $expectedContent): string
    {
        $expectedContent .= "\n";
        return $expectedContent;
    }

    private function determineLineDiffs(): void
    {
        $diffLines = $this->differ->diffToArray($this->expectedContent, $this->actualContent);
        foreach ($diffLines as $diffLine) {
            if ($diffLine[1] === 2) {
                $this->deletedLines++;
            } else if ($diffLine[1] === 1) {
                $this->addedLines++;
            }
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeletedLines(): int
    {
        return $this->deletedLines;
    }

    public function getAddedLines(): int
    {
        return $this->addedLines;
    }

    public function getDiff(): string
    {
        return $this->diff;
    }

    public function getExpectedContent(): string
    {
        return $this->expectedContent;
    }

    public function getActualContent(): string
    {
        return $this->actualContent;
    }
}