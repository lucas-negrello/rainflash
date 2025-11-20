<?php

namespace App\Http\Middleware;

use App\Services\FeatureAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function __construct(
        protected FeatureAccessService $featureService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  string  $featureKey  The feature key to check
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Assuming user has a company relationship
        // Adjust this based on your actual user-company relationship
        $company = $user->companies()->first();

        if (!$company) {
            abort(403, 'Company not found.');
        }

        if (!$this->featureService->canAccess($company, $featureKey)) {
            abort(403, "Access to feature '{$featureKey}' is not available in your plan.");
        }

        return $next($request);
    }
}
