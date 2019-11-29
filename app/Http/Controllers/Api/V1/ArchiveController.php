<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ArchiveResource;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Validator;

class ArchiveController extends APIController
{
    protected $profileRepository;

    public function __construct(
        ProfileRepository $profileRepository
    ) {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', PORTFOLIO_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'order_by', 'query', 'appeal', 'name_kanji', 'with_or', 'applications']);

        return ArchiveResource::collection(
            $this->profileRepository->getListProfile($params, $limit)
        );
    }
}
