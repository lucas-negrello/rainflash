<?php

use App\Http\Middleware\FilamentAuthenticate;
use Illuminate\Http\Request;

it('redirects unauthenticated request to login via FilamentAuthenticate', function () {
    $middleware = new FilamentAuthenticate(app('auth'));

    $request = Request::create('/admin/panel', 'GET');

    $ref = new ReflectionClass($middleware);
    $method = $ref->getMethod('redirectTo');
    $method->setAccessible(true);
    $redirect = $method->invoke($middleware, $request);

    expect($redirect)->toBe(route('login'));
});
