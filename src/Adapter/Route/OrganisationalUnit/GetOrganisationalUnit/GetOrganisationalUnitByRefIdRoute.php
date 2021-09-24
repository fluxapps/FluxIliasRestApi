<?php

namespace Fluxlabs\FluxIliasRestApi\Adapter\Route\OrganisationalUnit\GetOrganisationalUnit;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\Api;
use Fluxlabs\FluxRestApi\Body\JsonBodyDto;
use Fluxlabs\FluxRestApi\Body\TextBodyDto;
use Fluxlabs\FluxRestApi\Method\Method;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxRestApi\Status\Status;

class GetOrganisationalUnitByRefIdRoute implements Route
{

    private Api $api;


    public static function new(Api $api) : /*static*/ self
    {
        $route = new static();

        $route->api = $api;

        return $route;
    }


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : string
    {
        return Method::GET;
    }


    public function getRoute() : string
    {
        return "/organisational-unit/by-ref-id/{ref_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $organisational_unit = $this->api->getOrganisationalUnitByRefId(
            $request->getParam(
                "ref_id"
            )
        );

        if ($organisational_unit !== null) {
            return ResponseDto::new(
                JsonBodyDto::new(
                    $organisational_unit
                )
            );
        } else {
            return ResponseDto::new(
                TextBodyDto::new(
                    "Organisational unit not found"
                ),
                Status::_404
            );
        }
    }
}
