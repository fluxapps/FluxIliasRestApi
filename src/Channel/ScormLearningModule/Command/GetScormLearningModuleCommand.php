<?php

namespace Fluxlabs\FluxIliasRestApi\Channel\ScormLearningModule\Command;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\ScormLearningModule\ScormLearningModuleDto;
use Fluxlabs\FluxIliasRestApi\Channel\Object\ObjectQuery;
use Fluxlabs\FluxIliasRestApi\Channel\ScormLearningModule\ScormLearningModuleQuery;
use ilDBInterface;
use LogicException;

class GetScormLearningModuleCommand
{

    use ObjectQuery;
    use ScormLearningModuleQuery;

    private ilDBInterface $database;


    public static function new(ilDBInterface $database) : /*static*/ self
    {
        $command = new static();

        $command->database = $database;

        return $command;
    }


    public function getScormLearningModuleById(int $id) : ?ScormLearningModuleDto
    {
        $scorm_learning_module = null;
        while (($scorm_learning_module_ = $this->database->fetchAssoc($result ??= $this->database->query($this->getScormLearningModuleQuery(
                $id
            )))) !== null) {
            if ($scorm_learning_module !== null) {
                throw new LogicException("Multiple scorm learning modules found with the id " . $id);
            }
            $scorm_learning_module = $this->mapScormLearningModuleDto(
                $scorm_learning_module_
            );
        }

        return $scorm_learning_module;
    }


    public function getScormLearningModuleByImportId(string $import_id) : ?ScormLearningModuleDto
    {
        $scorm_learning_module = null;
        while (($scorm_learning_module_ = $this->database->fetchAssoc($result ??= $this->database->query($this->getScormLearningModuleQuery(
                null,
                $import_id
            )))) !== null) {
            if ($scorm_learning_module !== null) {
                throw new LogicException("Multiple scorm learning modules found with the import id " . $import_id);
            }
            $scorm_learning_module = $this->mapScormLearningModuleDto(
                $scorm_learning_module_
            );
        }

        return $scorm_learning_module;
    }


    public function getScormLearningModuleByRefId(int $ref_id) : ?ScormLearningModuleDto
    {
        $scorm_learning_module = null;
        while (($scorm_learning_module_ = $this->database->fetchAssoc($result ??= $this->database->query($this->getScormLearningModuleQuery(
                null,
                null,
                $ref_id
            )))) !== null) {
            if ($scorm_learning_module !== null) {
                throw new LogicException("Multiple scorm learning modules found with the ref id " . $ref_id);
            }
            $scorm_learning_module = $this->mapScormLearningModuleDto(
                $scorm_learning_module_
            );
        }

        return $scorm_learning_module;
    }
}
