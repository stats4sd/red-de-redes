<?php

namespace App\Imports;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PreProcessDavisHeaders
{
    public function __invoke($filePath): array
    {
        // avoid reading entire file into memory with fopen
        $fullFilePath = Storage::path($filePath);

        $file = fopen($fullFilePath, 'r');

        $header1 = fgets($file);
        $header2 = fgets($file);


        $header1Array = explode("\t", $header1);
        $header2Array = explode("\t", $header2);

        $headerMerged = [];
        foreach ($header1Array as $index => $item) {

            // if the header1 is empty, only use header2 (without trailing _)
            $headerMerged[] = $item
                ? $this->formatHeader($item) . '_' . $this->formatHeader($header2Array[$index])
                : $this->formatHeader($header2Array[$index]);
        }

        $newFile = fopen($fullFilePath . ".with_merged_headers.txt", "w");

        fwrite($newFile, implode("\t", $headerMerged) . PHP_EOL);

        $recordCount = 0;

        // iterate through rest of file
        while (!feof($file)) {
            ++$recordCount;
            fwrite($newFile, fgets($file));
        }

        fclose($newFile);
        fclose($file);

        return [$filePath . ".with_merged_headers.txt", $recordCount];
    }

    public function formatHeader(string $header): string
    {
        return Str::replace(' ', '_',
            Str::replace('.', '',
                trim($header)
            )
        );
    }
}
