<?php


namespace Vleroy\Gen;


use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class GenService
{
    protected $rootPath = '/templates/';

    public function __construct()
    {
    }

    public function folderExists(String $folder)
    {
        try {
            File::directories($this->getDirPath($folder));
            return true;
        } catch (DirectoryNotFoundException $e) {
            return false;
        }
    }

    public function getDirPath($folder)
    {
        return resource_path().$this->rootPath.$folder;
    }

    public function getValuesInString(String $str)
    {
        $matches = [];
        preg_match_all('/{[A-Za-z0-9]+}/', $str, $matches);
        return $matches[0];
    }

    public function getRequiredValues(String $folder)
    {
        $dirPath = $this->getDirPath($folder);
        $files = File::allFiles($dirPath);
        $values = [];

        foreach($files as $file)
        {
            // Search values in file path
            $filePath = $file->getPathname();
            $values += $this->getValuesInString($filePath);

            // Search values in file content
            $fileContent = $file->getContents();
            $values += $this->getValuesInString($fileContent);
        }

        return array_unique($values);
    }

    public function processFolder($folder, $data)
    {
        $dirPath = $this->getDirPath($folder);
        $basePath = base_path();
        $files = File::allFiles($dirPath);

        foreach($files as $file)
        {
            // Process file content
            $fileContent = $file->getContents();
            $processedFile = $this->processString($fileContent, $data);

            // Process file path
            $filePath = $file->getPathName();
            $filePath = $this->processString($filePath, $data);
            $filePath = str_replace(
                $dirPath,
                $basePath,
                $filePath
            );

            $fileDirPath = pathinfo($filePath, PATHINFO_DIRNAME);
            File::makeDirectory($fileDirPath, 0755, true, true);

            File::put($filePath, $processedFile, 0755, true);
        }
    }

    public function processString($str, $data)
    {
        foreach($data as $key => $value)
        {
            $str = str_replace($key, $value, $str);
        }

        return $str;
    }
}
