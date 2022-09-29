<?php

namespace App\Imports;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PreProcessChinasHeaders
{
    public function __invoke($filePath): array
    {
        // avoid reading entire file into memory with fopen
        $fullFilePath = Storage::path($filePath);

        $file = fopen($fullFilePath, 'r');

        $header1 = fgets($file);

        $header1Array = explode(",", $header1);

        $headerMerged = [];
        foreach ($header1Array as $index => $item) {
            $headerMerged[] = $this->formatHeader($item);
        }

        $headerValidationFile = fopen($fullFilePath . ".header_validation.txt", "w");
        $newFile = fopen($fullFilePath . ".with_merged_headers.txt", "w");

        fwrite($headerValidationFile, implode(",", $headerMerged) . PHP_EOL);
        fwrite($newFile, implode(",", $headerMerged) . PHP_EOL);

        $recordCount = 0;

        // iterate through rest of file
        while (!feof($file)) {

            $line = fgets($file);
            if (trim($line) !== "") {
                ++$recordCount;
            }
            fwrite($newFile, $line);

            // if line is first line; write to $headerValidationFile as well;
            if ($recordCount === 1) {
                fwrite($headerValidationFile, $line);
            }

        }

        fclose($newFile);
        fclose($headerValidationFile);
        fclose($file);

        return [$filePath . ".with_merged_headers.txt", $filePath . ".header_validation.txt", $recordCount];
    }

    public function formatHeader(string $header): string
    {
        // logger("PreProcessChinasHeaders.formatHeader() starts...");

        // replace back slash as hypen
        // replace space as underscore
        // remove full stop
        return Str::lower(
            Str::replace('/', '-',
                Str::replace(' ', '_',
                    Str::replace('.', '',
                        trim($header)
                    )
                )
            )
        );
    }
}
