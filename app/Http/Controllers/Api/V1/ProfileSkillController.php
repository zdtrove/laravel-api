<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProfileSkillResource;
use App\Repositories\ProfileSkillRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileSkillController extends APIController
{
    protected $profileSkillRepository;
    protected $profileRepository;

    public function __construct(
        ProfileSkillRepository $profileSkillRepository,
        ProfileRepository $profileRepository
    ) {
        $this->profileSkillRepository = $profileSkillRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create profile skill
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'skill' => 'required',
            'level' => 'required|numeric|min:0|max:5',
            'description' => 'required',
            'user_id' => 'numeric|min:0'
        ]);

        $input = $request->only([
            'skill',
            'level',
            'description'
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

        $profileApplication = $this->profileSkillRepository->create($input);

        return $this->respondCreated([
            'message' => __('api.profile_skill.create.success'),
            'data' => $profileApplication
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
        $limit = $request->get('limit', PROFILE_SKILL_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'title', 'order_by']);
        $isAdmin = (auth()->user()->role == ADMIN);

        return ProfileSkillResource::collection(
            $this->profileSkillRepository->getListSkillByProfileId($isAdmin ? $id : auth()->user()->id, $params, $limit)
        );
    }

    /**
     * Update profile skill
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        // Check profile skill exist
        $profileApplication = $this->profileSkillRepository->find($request->get('id'));
        if (null == $profileApplication) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        $validation = Validator::make($request->all(), [
            'skill' => 'sometimes|required',
            'level' => 'numeric|min:0|max:5'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Update to database
        $input = $request->only([
            'id',
            'skill',
            'level',
            'description'
        ]);

        $profileApplication = $this->profileSkillRepository->update($profileApplication, $input);

        return $this->respondWithData([
            'message' => __('api.profile_skill.update.success'),
            'data' => $profileApplication
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

        // Check profile skill exist
        $profileApplication = $this->profileSkillRepository->find($request->get('id'));
        if (null == $profileApplication) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->profileSkillRepository->delete($profileApplication)) {
            return $this->respondWithData([
                'message' => __('api.profile_skill.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
