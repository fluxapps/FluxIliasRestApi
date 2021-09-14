<?php

namespace Fluxlabs\FluxIliasRestApi\Channel\Object\Command;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\Object\ObjectDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\Object\ObjectIdDto;
use Fluxlabs\FluxIliasRestApi\Channel\Object\ObjectQuery;
use Fluxlabs\FluxIliasRestApi\Channel\Object\Port\ObjectService;
use ilRepUtil;

class DeleteObjectCommand
{

    use ObjectQuery;

    private ObjectService $object;


    public static function new(ObjectService $object) : /*static*/ self
    {
        $command = new static();

        $command->object = $object;

        return $command;
    }


    public function deleteObjectById(int $id) : ?ObjectIdDto
    {
        return $this->deleteObject(
            $this->object->getObjectById(
                $id
            )
        );
    }


    public function deleteObjectByImportId(string $import_id) : ?ObjectIdDto
    {
        return $this->deleteObject(
            $this->object->getObjectByImportId(
                $import_id
            )
        );
    }


    public function deleteObjectByRefId(int $ref_id) : ?ObjectIdDto
    {
        return $this->deleteObject(
            $this->object->getObjectByRefId(
                $ref_id
            )
        );
    }


    private function deleteObject(?ObjectDto $object) : ?ObjectIdDto
    {
        if ($object === null) {
            return null;
        }

        ilRepUtil::deleteObjects($object->getParentRefId(), [$object->getRefId()]);

        return ObjectIdDto::new(
            $object->getId(),
            $object->getImportId(),
            $object->getRefId()
        );
    }
}