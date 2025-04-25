<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exceptions\ExceptionsHandler;
use App\Services\ResponseService;

class BaseController extends Controller
{
    public function __construct(
        protected ResponseService $responseService,
        protected ExceptionsHandler $exceptionsHandler
    ) {}
}
