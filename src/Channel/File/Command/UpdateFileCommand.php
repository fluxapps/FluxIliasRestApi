<?php

namespace Fluxlabs\FluxIliasRestApi\Channel\File\Command;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\File\FileDiffDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\File\FileDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\Object\ObjectIdDto;
use Fluxlabs\FluxIliasRestApi\Channel\File\FileQuery;
use Fluxlabs\FluxIliasRestApi\Channel\File\Port\FileService;

class UpdateFileCommand
{

    use FileQuery;

    private FileService $file;


    public static function new(FileService $file) : /*static*/ self
    {
        $command = new static();

        $command->file = $file;

        return $command;
    }


    public function updateFileById(int $id, FileDiffDto $diff) : ?ObjectIdDto
    {
        return $this->updateFile(
            $this->file->getFileById(
                $id
            ),
            $diff
        );
    }


    public function updateFileByImportId(string $import_id, FileDiffDto $diff) : ?ObjectIdDto
    {
        return $this->updateFile(
            $this->file->getFileByImportId(
                $import_id
            ),
            $diff
        );
    }


    public function updateFileByRefId(int $ref_id, FileDiffDto $diff) : ?ObjectIdDto
    {
        return $this->updateFile(
            $this->file->getFileByRefId(
                $ref_id
            ),
            $diff
        );
    }


    private function updateFile(?FileDto $file, FileDiffDto $diff) : ?ObjectIdDto
    {
        if ($file === null) {
            return null;
        }

        $ilias_file = $this->getIliasFile(
            $file->getId(),
            $file->getRefId()
        );
        if ($ilias_file === null) {
            return null;
        }

        $this->mapFileDiff(
            $diff,
            $ilias_file
        );

        $ilias_file->update();

        return ObjectIdDto::new(
            $file->getId(),
            $diff->getImportId() ?? $file->getImportId(),
            $file->getRefId()
        );
    }
}