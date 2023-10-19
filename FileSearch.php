<?php

declare(strict_types=1);

/**
 * Class for searching and displaying file names in a specified folder
 */
class FileSearch
{
    private string $folderPath;
    private string $searchPattern;
    private string $recursiveSearchPattern;
    private bool $isRecursive;

    /**
     * Class constructor
     *
     * @param string $folderPath Path to the folder with files
     * @param bool $isRecursive Enable isRecursive mode
     */
    public function __construct(string $folderPath, bool $isRecursive)
    {
        $this->folderPath = $folderPath;
        $this->searchPattern = '/^[a-z0-9]+\.ixt$/i';
        $this->recursiveSearchPattern = '/.+\/[a-z0-9]+\.ixt$/i';
        $this->isRecursive = $isRecursive;
    }

    /**
     * Method for performing file search and displaying file names
     */
    public function searchFiles(): void
    {
        // Get the list of files in the folder
        $files = $this->isRecursive ? $this->scanRecursive($this->folderPath) : scandir($this->folderPath);

        // Array to store the found files
        $foundFiles = [];

        // Iterate over the files
        foreach ($files as $file) {
            // Check if the file name matches the regular expression
            $searchPattern = $this->isRecursive ? $this->recursiveSearchPattern : $this->searchPattern;

            if (preg_match($searchPattern, $file)) {
                // Add the file name to the array of found files
                $foundFiles[] = $file;
            }
        }

        // Sort the array of found files by name
        sort($foundFiles);

        // Display the file names
        foreach ($foundFiles as $file) {
            echo $file . "\n";
        }
    }

    /**
     * Recursively scan a folder and return the list of files
     *
     * @param string $folderPath Path to the folder
     * @return array List of files
     */
    private function scanRecursive(string $folderPath): array
    {
        $files = [];
        $dir = new RecursiveDirectoryIterator($folderPath);
        $iterator = new RecursiveIteratorIterator($dir);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}

// Get the recursive flag from the command line arguments
$isRecursive = (bool) array_search('--recursive', $argv);

// Get the folder path from the command line arguments
$folderPath = './datafiles';

if ($index = array_search('--path', $argv)) {
    $folderPath = $argv[++$index];
}

// Create an instance of the FileSearch class with the specified folder path and recursive flag
$fileSearch = new FileSearch($folderPath, $isRecursive);

// Perform the file search and display the file names
$fileSearch->searchFiles();