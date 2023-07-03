<?php

namespace App\Services;

use App\Services\Traits\ConsumeExternalService;
use Illuminate\Http\Client\Response;

class EvaluationService
{
    use ConsumeExternalService;

    protected $token, $url;

    public function __construct()
    {
        $micro_02 = config('services.micro_02');
        $this->token = $micro_02['token'];
        $this->url = $micro_02['url'];
    }

    public function getEvaluationsCompany(string $company): Response
    {
        return $this->request(
            method: 'get',
            endpoint: "/evaluations/{$company}"
        );
    }
}
