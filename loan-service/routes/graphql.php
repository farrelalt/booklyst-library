<?php

use Illuminate\Support\Facades\Route;
use Nuwave\Lighthouse\Support\Http\Controllers\GraphQLController;

/*
|--------------------------------------------------------------------------
| GraphQL Route
|--------------------------------------------------------------------------
| Endpoint utama GraphQL (/graphql)
*/

Route::post('/graphql', GraphQLController::class);
