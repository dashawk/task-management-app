<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;

abstract class BaseApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * API version
     *
     * @var string
     */
    protected string $apiVersion = '1.0';

    /**
     * Get the API version
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Set the API version
     *
     * @param string $version
     * @return void
     */
    public function setApiVersion(string $version): void
    {
        $this->apiVersion = $version;
    }
}
