<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AwardResource;
use App\Repositories\AwardRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AwardController extends APIController
{
    protected $awardRepository;
    protected $profileRepository;

    public function __construct(
        AwardRepository $awardRepository,
        ProfileRepository $profileRepository
    ) {
        $this->awardRepository = $awardRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create award
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'award_date' => 'date_format:"Y-m-d"',
            'user_id' => 'numeric|min:0'
        ]);

        $input = $request->only([
            'title',
            'description',
            'award_date'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Check user_id, set profile_id
        if (!empty($request->get('user_id'))) {
            $findProfileById = $this->profileRepository->find($request->get('user_id'));
            if (!$findProfileById) {
                return $this->respondNotFound();
            } else {
                $input['profile_id'] = $request->get('user_id');
            }
        } else {
            $input['profile_id'] = auth()->user()->id;
        }

        $award = $this->awardRepository->create($input);

        return $this->respondCreated([
            'message' => __('api.award.create.success'),
            'data' => $award
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $id = null)
    {
        $limit = $request->get('limit', PORTFOLIO_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'title', 'order_by']);
        $isAdmin = (auth()->user()->role == ADMIN);

        return AwardResource::collection(
            $this->awardRepository->getListAwardByProfileId($isAdmin ? $id : auth()->user()->id, $params, $limit)
        );
    }

    /**
     * Update award
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
            // Check award exist
            $award = $this->awardRepository->find($request->get('id'));
        if (null == $award) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

            $validation = Validator::make($request->all(), [
                'title' => 'sometimes|required',
                'description' => 'sometimes|required',
                'award_date' => 'sometimes|required|date_format:"Y-m-d"',
            ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

            // Update to database
            $input = $request->only([
                'id',
                'title',
                'description',
                'award_date'
            ]);

            $award = $this->awardRepository->update($award, $input);

            return $this->respondWithData([
                'message' => __('api.award.update.success'),
                'data' => $award
            ]);
    }

    /**
     * Remove the specified resource
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Check award exist or belong to current user
        $award = $this->awardRepository->find($request->get('id'));
        if (null == $award) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->awardRepository->delete($award)) {
            return $this->respondWithData([
                'message' => __('api.award.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
