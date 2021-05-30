<?php

namespace PrimeX\Packages\Core\Console;

trait HandleCSV
{
    /**
     * The lines of the processed CSV as array
     * @var array
     */
    protected $lines = [];

    /**
     * CSV Keys - header - for validation
     * @var array
     */
    protected $keys = [];

    protected function handleCSV($csvFile)
    {
        ini_set('auto_detect_line_endings', TRUE);

        // BOM as a string for comparison - removing the "space" characters but not bullet proof
        // - would need to know the character encoding of the CSV to be able to convert, etc.
        $bom = "\xef\xbb\xbf";
        $context = stream_context_create();

        $handle = fopen($csvFile, "r", false, $context);
        if ($handle === false) {
            $this->error('Cannot find csv file ' . $csvFile);
            return false;
        }

        // Progress file pointer and get first 3 characters to compare to the BOM string.
        if (fgets($handle, 4) !== $bom) {
            // BOM not found - rewind pointer to start of file.
            rewind($handle);
        }

        $csvHeader = fgetcsv($handle);
        $keys = array_map('trim', $csvHeader);
        if (empty($keys)) {
            return $this->error('Invalid CSV');
        }
        if ($keys != $this->keys) {
            return $this->error('Looks like there is a problem with your CSV format. We need a CSV header with ' . implode(", ", $this->keys));
        }

        while (!feof($handle) && ($line = fgetcsv($handle)) !== false) {
            $this->lines[] = array_combine($keys, $line);
        }
    }
}
