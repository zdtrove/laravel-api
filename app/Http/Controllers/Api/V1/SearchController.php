<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\SearchResource;
use App\Repositories\OccupationRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\SkillRepository;
use Illuminate\Http\Request;
use Validator;

class SearchController extends APIController
{
    protected $skillRepository;
    protected $applicationRepository;
    protected $companyRepository;
    protected $occupationRepository;
    protected $awardRepository;

    public function __construct(
        SkillRepository $skillRepository,
        ApplicationRepository $applicationRepository,
        CompanyRepository $companyRepository,
        OccupationRepository $occupationRepository,
        SkillRepository $awardRepository
    ) {
        $this->skillRepository = $skillRepository;
        $this->applicationRepository = $applicationRepository;
        $this->companyRepository = $companyRepository;
        $this->occupationRepository = $occupationRepository;
        $this->awardRepository = $awardRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $limit = $request->get('limit', ADMIN_LIMIT_PER_PAGE);
        $module = $request->get('module');
        if (!empty($module)) {
            $params = $request->only(['page', 'title', 'email', 'order_by', 'created_range', 'status']);
            switch ($module) {
                case SEARCH_SKILL:
                    $repository = $this->skillRepository;
                    break;
                case SEARCH_APPLICATION:
                    $repository = $this->applicationRepository;
                    break;
                case SEARCH_COMPANY:
                    $repository = $this->companyRepository;
                    break;
                case SEARCH_OCCUPATION:
                    $repository = $this->occupationRepository;
                    break;
            }
            if (isset($repository)) {
                return SearchResource::collection(
                    $repository->getPaginated($limit, $params)
                );
            }
        }

        return $this->respondWithNoContent();
    }
}
