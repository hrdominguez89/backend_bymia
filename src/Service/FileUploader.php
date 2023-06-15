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

    private $enviro;

    public function __construct(FilesystemInterface $filesystem, SluggerInterface $slugger)
    {
        $this->filesystem = $filesystem;
        $this->slugger = $slugger;
        $this->enviro = $_ENV['APP_ENV'] === 'dev' ? 'testing/' : '';
    }

    public function upload(UploadedFile $file, $nombre, $path = false): string
    {
        $safeFilename =  $this->slugger->slug($nombre);
        $newFilename =  $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        if ($path) {
            $path = '/' . $this->enviro . $path . '/' .  $newFilename;
        } else {
            $path = '/' . $this->enviro .  $newFilename;
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
