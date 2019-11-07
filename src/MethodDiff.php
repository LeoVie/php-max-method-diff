<?php

namespace LeoVie\PHPMaxMethodDiff;

use SebastianBergmann\Diff\Differ;

class MethodDiff
{
    private $name;
    private $diff;
    private $deletedLines = 0;
    private $addedLines = 0;

    /** @var Differ */
    private $differ;

    public function __construct(string $name, string $expectedContent, string $actualContent)
    {
        $this->name = $name;

        $this->differ = new Differ();

        $expectedContent = $this->normalizeLineEndings($expectedContent);
        $actualContent = $this->normalizeLineEndings($actualContent);

        $this->diff = $this->differ->diff($expectedContent, $actualContent);

        $this->determineLineDiffs($expectedContent, $actualContent);
    }

    private function normalizeLineEndings(string $expectedContent): string
    {
        $expectedContent .= "\n";
        return $expectedContent;
    }

    private function determineLineDiffs(string $expectedContent, string $actualContent): void
    {
        $diffLines = $this->differ->diffToArray($expectedContent, $actualContent);
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
}