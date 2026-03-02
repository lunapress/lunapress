<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

trait ExtractorPatternMatchTrait
{
    public function supports(string $filePath): bool
    {
        $filename = basename($filePath);

        foreach ($this->getPatterns() as $pattern) {
            if (fnmatch($pattern, $filename)) {
                return true;
            }
        }

        return false;
    }
}
