<?php

namespace AllatraIt\LaravelOpenapi\Services;

use DirectoryIterator;
use Symfony\Component\Yaml\Yaml;

class OpenapiYamlDataProvider
{
    public function getDefinitions(): array
    {
        $definitionsDir = config('openapi.definitions_dir');
        $data = $this->scanDir($definitionsDir);
        $data = $this->arrangeDefinitions($data);
        $content = $this->arrayToString($data);

        return Yaml::parse($content);
    }

    private function scanDir(string $dir): array
    {
        $fileContent = [];

        /** @var DirectoryIterator $f */
        foreach (new DirectoryIterator($dir) as $f) {
            if ($f->isDot()) {
                continue; // skip . and ..
            }

            if ($f->isFile() && \in_array($f->getExtension(), ['yaml', 'yml'], true)) {
                $fileContent = array_merge_recursive($fileContent, $this->parseFile($f->getPathname()));
            } elseif ($f->isDir()) {
                $fileContent = array_merge_recursive($fileContent, $this->scanDir($f->getPathname()));
            }
        }

        return $fileContent;
    }

    private function parseFile(string $filePath): array
    {
        $fileData = [];
        $initIndent = null;
        $prevIndent = 0;
        $prevPosition = [];
        $rawData = file($filePath);

        if (false === $rawData) {
            return $fileData;
        }

        foreach ($rawData as $rawLine) {
            $line = trim($rawLine);

            if ('' === $line || str_starts_with($line, '#')) {
                continue;
            }

            $matches = [];

            if ($this->isIndentExists($rawLine, $matches)) {
                $indent = \strlen($matches[0]) + 1;
                $initIndent ??= $indent;
                $pos = $indent > $prevIndent ? $prevIndent : $indent - $initIndent;

                if ($this->isContainsNestedParents($line, $prevPosition, $pos - $initIndent)) {
                    $prevPosition[$pos][$rawLine] = [];
                    $prevPosition[$indent] = &$prevPosition[$pos][$rawLine];
                    $this->removeExcessReferences($prevPosition, $indent);
                } else {
                    $prevPos = $pos;

                    while ($prevPos >= 0) {
                        if (\array_key_exists($prevPos, $prevPosition)) {
                            $prevPosition[$prevPos][] = $rawLine;
                            $this->removeExcessReferences($prevPosition, $prevPos);
                            break;
                        }

                        $prevPos -= $initIndent;
                    }
                }

                $prevIndent = $indent;
            } else {
                $fileData[$rawLine] = [];
                $prevIndent = 0;
                $prevPosition[$prevIndent] = &$fileData[$rawLine];
                $this->removeExcessReferences($prevPosition, $prevIndent);
            }
        }

        return $fileData;
    }

    private function isIndentExists(string $rawLine, array &$matches): bool
    {
        return 1 === preg_match('/^ +(?!\S)/', $rawLine, $matches);
    }

    private function arrangeDefinitions(array $data): array
    {
        uksort($data, static function ($a, $b) {
            $aIsAnchor = preg_match('/: +&/', $a);
            $bIsAnchor = preg_match('/: +&/', $b);

            if (0 === $aIsAnchor && 1 === $bIsAnchor) {
                return 1;
            }

            if (1 === $aIsAnchor && 0 === $bIsAnchor) {
                return -1;
            }

            return 0;
        });

        return $data;
    }

    private function isContainsNestedParents(string $line, array $prevPosition, int $pos): bool
    {
        $isPossibleParentNode = 1 === preg_match('/:$|: +&|^- +name: +[\w\-]+/', $line);

        if ($isPossibleParentNode && \array_key_exists($pos, $prevPosition)) {
            return 1 === preg_match('/:$|: +&|^- +name: +[\w\-]+/', trim(array_key_last($prevPosition[$pos])));
        }

        return $isPossibleParentNode;
    }

    private function removeExcessReferences(array &$links, int $position): void
    {
        foreach ($links as $key => $value) {
            if ($key <= $position) {
                continue;
            }

            unset($links[$key]);
        }
    }

    private function arrayToString(array $data): string
    {
        $result = '';

        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $result .= $key.$this->arrayToString($value);
            } else {
                $result .= str_ends_with($value, \PHP_EOL) ? $value : $value.\PHP_EOL;
            }
        }

        return $result;
    }
}
