<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $slugger;
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem, SluggerInterface $slugger)
    {
        $this->filesystem = $filesystem;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $path = false,$nombre): string
    {
        $safeFilename =  $this->slugger->slug($nombre);
        $newFilename =  $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        if($path){
            $path = '/' . $path . '/' .  $newFilename;
        }else{
            $path = '/' .  $newFilename;
        }
        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream($path, $stream, ['ACL' => 'public-read']);
        if ($result === false) {
            throw new FileException(
                sprintf('Could not write uploaded file %s', $newFilename)
            );
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        return $newFilename;
    }
}
