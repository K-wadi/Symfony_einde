<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Upload-logica apart gehouden (aanbevolen op symfony.com).
 * Slaat alleen een veilige bestandsnaam op in de database.
 */
class FileUploader
{
    public function __construct(
        private readonly string $targetDirectory,
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException) {
            throw new FileException('Upload van afbeelding is mislukt.');
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /** Bij update/delete: oude afbeelding van schijf verwijderen. */
    public function remove(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = $this->targetDirectory.'/'.$fileName;
        if (is_file($path)) {
            unlink($path);
        }
    }
}
