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

    public function getRequiredValues(String $folder)
    {
        $dirPath = $this->getDirPath($folder);
        $files = File::allFiles($dirPath);
        $values = [];

        foreach($files as $file)
        {
            $fileContent = $file->getContents();
            $matches = [];
            preg_match_all('/{[A-Za-z0-9]+}/', $fileContent, $matches);

            $values += $matches[0];
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
            $processedFile = $this->processFile($file, $data);
            $filePath = str_replace(
                $dirPath,
                $basePath,
                $file->getPathName()
            );

            File::put($filePath, $processedFile);
        }
    }

    public function processFile($file, $data)
    {
        $fileContent = $file->getContents();

        foreach($data as $key => $value)
        {
            $fileContent = str_replace($key, $value, $fileContent);
        }

        return $fileContent;
    }
}
