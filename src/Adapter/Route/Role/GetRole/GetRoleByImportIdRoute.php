<?php

namespace FluxIliasRestApi\Adapter\Route\Role\GetRole;

use FluxIliasBaseApi\Adapter\Role\RoleDto;
use FluxIliasRestApi\Adapter\Api\IliasRestApi;
use FluxRestApi\Adapter\Body\JsonBodyDto;
use FluxRestApi\Adapter\Body\TextBodyDto;
use FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxRestApi\Adapter\Method\DefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteParamDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxRestApi\Adapter\Status\DefaultStatus;

class GetRoleByImportIdRoute implements Route
{

    private function __construct(
        private readonly IliasRestApi $ilias_rest_api
    ) {

    }


    public static function new(
        IliasRestApi $ilias_rest_api
    ) : static {
        return new static(
            $ilias_rest_api
        );
    }


    public function getDocumentation() : ?RouteDocumentationDto
    {
        return RouteDocumentationDto::new(
            $this->getRoute(),
            $this->getMethod(),
            "Get role by import id",
            null,
            [
                RouteParamDocumentationDto::new(
                    "import_",
                    "string",
                    "Role import id"
                )
            ],
            null,
            null,
            [
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::JSON,
                    null,
                    RoleDto::class,
                    "Role"
                ),
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::TEXT,
                    DefaultStatus::_404,
                    null,
                    "Role not found"
                )
            ]
        );
    }


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/role/by-import-id/{import_id}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        $role = $this->ilias_rest_api->getRoleByImportId(
            $request->getParam(
                "import_id"
            )
        );

        if ($role !== null) {
            return ServerResponseDto::new(
                JsonBodyDto::new(
                    $role
                )
            );
        } else {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "Role not found"
                ),
                DefaultStatus::_404
            );
        }
    }
}
