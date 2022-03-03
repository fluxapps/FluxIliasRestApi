<?php

namespace FluxIliasRestApi\Channel\Change;

use FluxIliasRestApi\Adapter\Api\Change\ChangeDto;
use FluxIliasRestApi\Adapter\Api\Change\LegacyChangeType;
use ilDBConstants;

trait ChangeQuery
{

    private function changeDatabaseExists() : bool
    {
        return $this->database->tableExists($this->getChangeDatabaseTable());
    }


    private function getChangeDatabaseTable() : string
    {
        return "flilre_change";
    }


    private function getChangePurgeQuery(int $keep_changes_inside_days) : string
    {
        return "DELETE FROM " . $this->database->quoteIdentifier($this->getChangeDatabaseTable()) . "
WHERE time<" . $this->database->quote(time() - ($keep_changes_inside_days * 24 * 60 * 60), ilDBConstants::T_FLOAT);
    }


    private function getChangeQuery(?float $from = null, ?float $to = null, ?float $after = null, ?float $before = null) : string
    {
        $wheres = [];

        if ($from !== null) {
            $wheres[] = "time>=" . $this->database->quote($from, ilDBConstants::T_FLOAT);
        }

        if ($to !== null) {
            $wheres[] = "time<=" . $this->database->quote($to, ilDBConstants::T_FLOAT);
        }

        if ($after !== null) {
            $wheres[] = "time>" . $this->database->quote($after, ilDBConstants::T_FLOAT);
        }

        if ($before !== null) {
            $wheres[] = "time<" . $this->database->quote($before, ilDBConstants::T_FLOAT);
        }

        return "SELECT *
FROM " . $this->database->quoteIdentifier($this->getChangeDatabaseTable()) . "
" . (!empty($wheres) ? "WHERE " . implode(" AND ", $wheres) : "") . "
ORDER BY time ASC";
    }


    private function mapChangeDto(array $change) : ChangeDto
    {
        return ChangeDto::new(
            $change["id"],
            LegacyChangeType::from(
                $change["type"]
            ),
            $change["time"],
            $change["user_id"],
            $change["user_import_id"] ?: null,
            json_decode($change["data"])
        );
    }
}
