<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\NotificationResource;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Validator;

class NotificationController extends APIController
{
    protected $profileRepository;

    public function __construct(
        ProfileRepository $profileRepository
    ) {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Display a listing of new users have registered
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listNewRegisteredProfile(Request $request)
    {
        $limit = $request->get('limit', 5);
        $params = $request->only(['page', 'name_kanji', 'order_by', 'appeal', 'with_or', 'applications']);

        return NotificationResource::collection(
            $this->profileRepository->getListProfileAfterDaysWithPaging($params, $limit, 1)
        );
    }
}
