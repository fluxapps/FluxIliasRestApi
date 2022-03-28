<?php

namespace FluxIliasRestApi\Adapter\Route\Change;

use FluxIliasRestApi\Adapter\Api\IliasRestApi;
use FluxIliasRestApi\Libs\FluxRestApi\Body\JsonBodyDto;
use FluxIliasRestApi\Libs\FluxRestApi\Body\TextBodyDto;
use FluxIliasRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Method\LegacyDefaultMethod;
use FluxIliasRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Method\Method;
use FluxIliasRestApi\Libs\FluxRestApi\Libs\FluxRestBaseApi\Status\LegacyDefaultStatus;
use FluxIliasRestApi\Libs\FluxRestApi\Request\RequestDto;
use FluxIliasRestApi\Libs\FluxRestApi\Response\ResponseDto;
use FluxIliasRestApi\Libs\FluxRestApi\Route\Route;

class GetChangesRoute implements Route
{

    private IliasRestApi $ilias_rest_api;


    private function __construct(
        /*private readonly*/ IliasRestApi $ilias_rest_api
    ) {
        $this->ilias_rest_api = $ilias_rest_api;
    }


    public static function new(
        IliasRestApi $ilias_rest_api
    ) : /*static*/ self
    {
        return new static(
            $ilias_rest_api
        );
    }


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return [
            "after",
            "before",
            "from",
            "to"
        ];
    }


    public function getMethod() : Method
    {
        return LegacyDefaultMethod::GET();
    }


    public function getRoute() : string
    {
        return "/changes";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $changes = $this->ilias_rest_api->getChanges(
            $request->getQueryParam(
                "from"
            ),
            $request->getQueryParam(
                "to"
            ),
            $request->getQueryParam(
                "after"
            ),
            $request->getQueryParam(
                "before"
            )
        );

        if ($changes !== null) {
            return ResponseDto::new(
                JsonBodyDto::new(
                    $changes
                )
            );
        } else {
            return ResponseDto::new(
                TextBodyDto::new(
                    "Changes not found"
                ),
                LegacyDefaultStatus::_404()
            );
        }
    }
}