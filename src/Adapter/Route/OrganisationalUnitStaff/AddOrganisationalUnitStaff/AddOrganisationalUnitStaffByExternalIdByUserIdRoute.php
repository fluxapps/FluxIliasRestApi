<?php

namespace FluxIliasRestApi\Adapter\Route\OrganisationalUnitStaff\AddOrganisationalUnitStaff;

use FluxIliasBaseApi\Adapter\OrganisationalUnitStaff\OrganisationalUnitStaffDto;
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

class AddOrganisationalUnitStaffByExternalIdByUserIdRoute implements Route
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
            "Add organisational unit staff by organisational unit external id and user id",
            null,
            [
                RouteParamDocumentationDto::new(
                    "external_id",
                    "string",
                    "Organisational unit external id"
                ),
                RouteParamDocumentationDto::new(
                    "user_id",
                    "int",
                    "User id"
                ),
                RouteParamDocumentationDto::new(
                    "position_id",
                    "int",
                    "Organisational unit position id"
                )
            ],
            null,
            null,
            [
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::JSON,
                    null,
                    OrganisationalUnitStaffDto::class,
                    "Organisational unit staff"
                ),
                RouteResponseDocumentationDto::new(
                    DefaultBodyType::TEXT,
                    DefaultStatus::_404,
                    null,
                    "Organisational unit staff not found"
                )
            ]
        );
    }


    public function getMethod() : Method
    {
        return DefaultMethod::POST;
    }


    public function getRoute() : string
    {
        return "/organisational-unit/by-external-id/{external_id}/add-staff/by-id/{user_id}/{position_id}";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        $id = $this->ilias_rest_api->addOrganisationalUnitStaffByExternalIdByUserId(
            $request->getParam(
                "external_id"
            ),
            $request->getParam(
                "user_id"
            ),
            $request->getParam(
                "position_id"
            )
        );

        if ($id !== null) {
            return ServerResponseDto::new(
                JsonBodyDto::new(
                    $id
                )
            );
        } else {
            return ServerResponseDto::new(
                TextBodyDto::new(
                    "Organisational unit staff not found"
                ),
                DefaultStatus::_404
            );
        }
    }
}
