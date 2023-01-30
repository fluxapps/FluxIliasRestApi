<?php

namespace FluxIliasRestApi\Service\FluxIliasRestObject\Command;

use FluxIliasBaseApi\Adapter\Permission\DefaultPermission;
use FluxIliasRestApi\Service\Object\Port\ObjectService;

class HasAccessToFluxIliasRestObjectProxyCommand
{

    private function __construct(
        private readonly ObjectService $object_service
    ) {

    }


    public static function new(
        ObjectService $object_service
    ) : static {
        return new static(
            $object_service
        );
    }


    public function hasAccessToFluxIliasRestObjectProxy(int $ref_id, int $user_id) : bool
    {
        return $this->object_service->hasAccessByRefIdByUserId(
            $ref_id,
            $user_id,
            DefaultPermission::READ
        );
    }
}
