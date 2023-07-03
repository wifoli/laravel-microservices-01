<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCompany;
use App\Http\Resources\CompanyResource;
use App\Jobs\CompanyCreated;
use App\Services\CompanyService;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService,
        protected EvaluationService $evaluationService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = $this->companyService->getCompanies($request->get('filter', ''));

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCompany $request)
    {
        $company = $this->companyService
            ->createNewCompany($request->validated(), $request->file('image'));

        CompanyCreated::dispatch($company->email)->onQueue('queue_email');

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $company = $this->companyService->getCompanyByUUID($uuid);

        $evaluations = $this->evaluationService->getEvaluationsCompany($uuid)->body();

        return (new CompanyResource($company))
            ->additional([
                'evaluations' => json_decode($evaluations),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCompany $request, string $uuid)
    {
        $this->companyService
            ->updateCompanyByUUID($uuid, $request->validated(), $request->file('image'));

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $this->companyService->deleteCompanyByUUID($uuid);


        return response()->noContent();
    }
}
