<?php

namespace Fluxlabs\FluxIliasRestApi\Channel\ScormLearningModule\Command;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\Object\ObjectIdDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\ScormLearningModule\ScormLearningModuleDto;
use Fluxlabs\FluxIliasRestApi\Channel\ScormLearningModule\Port\ScormLearningModuleService;
use Fluxlabs\FluxIliasRestApi\Channel\ScormLearningModule\ScormLearningModuleQuery;
use ilObjSCORM2004LearningModule;
use ilUtil;

class UploadScormLearningModuleCommand
{

    use ScormLearningModuleQuery;

    private ScormLearningModuleService $scorm_learning_module;


    public static function new(ScormLearningModuleService $scorm_learning_module) : /*static*/ self
    {
        $command = new static();

        $command->scorm_learning_module = $scorm_learning_module;

        return $command;
    }


    public function uploadScormLearningModuleById(int $id, string $file) : ?ObjectIdDto
    {
        return $this->uploadScormLearningModule(
            $this->scorm_learning_module->getScormLearningModuleById(
                $id
            ),
            $file
        );
    }


    public function uploadScormLearningModuleByImportId(string $import_id, string $file) : ?ObjectIdDto
    {
        return $this->uploadScormLearningModule(
            $this->scorm_learning_module->getScormLearningModuleByImportId(
                $import_id
            ),
            $file
        );
    }


    public function uploadScormLearningModuleByRefId(int $ref_id, string $file) : ?ObjectIdDto
    {
        return $this->uploadScormLearningModule(
            $this->scorm_learning_module->getScormLearningModuleByRefId(
                $ref_id
            ),
            $file
        );
    }


    private function uploadScormLearningModule(?ScormLearningModuleDto $scorm_learning_module, string $file) : ?ObjectIdDto
    {
        if ($scorm_learning_module === null) {
            return null;
        }

        $ilias_scorm_learning_module = $this->getIliasScormLearningModule(
            $scorm_learning_module->getId(),
            $scorm_learning_module->getRefId()
        );
        if ($ilias_scorm_learning_module === null) {
            return null;
        }

        $ilias_scorm_learning_module->createDataDirectory();

        $new_version = $scorm_learning_module->getVersion();
        if ($new_version >= 1) {
            $new_version = $new_version + 1;
        } else {
            $new_version = 1;
        }

        $file_name = "upload_" . $new_version . ".zip";
        $file_path = $ilias_scorm_learning_module->getDataDirectory() . "/" . $file_name;

        ilUtil::moveUploadedFile(
            $file,
            $file_name,
            $file_path
        );
        ilUtil::unzip($file_path);
        ilUtil::renameExecutables($ilias_scorm_learning_module->getDataDirectory());

        if ($new_version === 1) {
            if ($ilias_scorm_learning_module instanceof ilObjSCORM2004LearningModule) {
                $ilias_scorm_learning_module->setImportSequencing($ilias_scorm_learning_module->getSequencingExpertMode());
            }
            $ilias_scorm_learning_module->readObject();
            $ilias_scorm_learning_module->setLearningProgressSettingsAtUpload();
        }

        $ilias_scorm_learning_module->setModuleVersion($new_version);
        $ilias_scorm_learning_module->update();

        return ObjectIdDto::new(
            $scorm_learning_module->getId(),
            $scorm_learning_module->getImportId(),
            $scorm_learning_module->getRefId()
        );
    }
}