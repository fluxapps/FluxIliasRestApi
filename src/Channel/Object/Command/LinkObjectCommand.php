<?php

namespace FluxIliasRestApi\Channel\Object\Command;

use FluxIliasRestApi\Adapter\Api\Object\ObjectDto;
use FluxIliasRestApi\Adapter\Api\Object\ObjectIdDto;
use FluxIliasRestApi\Channel\Object\ObjectQuery;
use FluxIliasRestApi\Channel\Object\Port\ObjectService;
use ilObjectDefinition;
use LogicException;

class LinkObjectCommand
{

    use ObjectQuery;

    private ObjectService $object;
    private ilObjectDefinition $object_definition;


    public static function new(ObjectService $object, ilObjectDefinition $object_definition) : /*static*/ self
    {
        $command = new static();

        $command->object = $object;
        $command->object_definition = $object_definition;

        return $command;
    }


    public function linkObjectByIdToId(int $id, int $parent_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectById(
                $id
            ),
            $this->object->getObjectById(
                $parent_id
            )
        );
    }


    public function linkObjectByIdToImportId(int $id, string $parent_import_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectById(
                $id
            ),
            $this->object->getObjectByImportId(
                $parent_import_id
            )
        );
    }


    public function linkObjectByIdToRefId(int $id, int $parent_ref_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectById(
                $id
            ),
            $this->object->getObjectByRefId(
                $parent_ref_id
            )
        );
    }


    public function linkObjectByImportIdToId(string $import_id, int $parent_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByImportId(
                $import_id
            ),
            $this->object->getObjectById(
                $parent_id
            )
        );
    }


    public function linkObjectByImportIdToImportId(string $import_id, string $parent_import_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByImportId(
                $import_id
            ),
            $this->object->getObjectByImportId(
                $parent_import_id
            )
        );
    }


    public function linkObjectByImportIdToRefId(string $import_id, int $parent_ref_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByImportId(
                $import_id
            ),
            $this->object->getObjectByRefId(
                $parent_ref_id
            )
        );
    }


    public function linkObjectByRefIdToId(int $ref_id, int $parent_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByRefId(
                $ref_id
            ),
            $this->object->getObjectById(
                $parent_id
            )
        );
    }


    public function linkObjectByRefIdToImportId(int $ref_id, string $parent_import_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByRefId(
                $ref_id
            ),
            $this->object->getObjectByImportId(
                $parent_import_id
            )
        );
    }


    public function linkObjectByRefIdToRefId(int $ref_id, int $parent_ref_id) : ?ObjectIdDto
    {
        return $this->linkObject(
            $this->object->getObjectByRefId(
                $ref_id
            ),
            $this->object->getObjectByRefId(
                $parent_ref_id
            )
        );
    }


    private function linkObject(?ObjectDto $object, ?ObjectDto $parent_object) : ?ObjectIdDto
    {
        if ($object === null || $parent_object === null || $object->getRefId() === null || $parent_object->getRefId() === null) {
            return null;
        }

        if ($object->getId() === $parent_object->getId()) {
            throw new LogicException("Can't link to its self");
        }

        $ilias_object = $this->getIliasObject(
            $object->getId(),
            $object->getRefId()
        );
        if ($ilias_object === null) {
            return null;
        }

        if (!$this->object_definition->allowLink($ilias_object->getType())) {
            throw new LogicException("Can't link object type " . $ilias_object->getType());
        }

        $ilias_object->createReference();
        $ilias_object->putInTree($parent_object->getRefId());
        $ilias_object->setPermissions($parent_object->getRefId());

        return ObjectIdDto::new(
            $object->getId(),
            $object->getImportId(),
            $ilias_object->getRefId() ?: null
        );
    }
}