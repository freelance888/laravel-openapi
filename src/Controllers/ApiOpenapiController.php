<?php

namespace AllatraIt\LaravelOpenapi\Controllers;

use AllatraIt\LaravelOpenapi\Services\OpenapiYamlDataProvider;
use Illuminate\Routing\Controller;

class ApiOpenapiController extends Controller
{
    private OpenapiYamlDataProvider $dataProvider;

    public function __construct(OpenapiYamlDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function index()
    {
        $definitions = $this->dataProvider->getDefinitions();

        return view(
            'openapi::openapi',
            [
                'title' => config('openapi.title'),
                'openapi_ui_version' => config('openapi.openapi_ui_version'),
                'openapi_data' => $definitions,
            ]
        );
    }
}
