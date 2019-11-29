<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProfileApplicationResource;
use App\Repositories\ProfileApplicationRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileApplicationController extends APIController
{
    protected $profileApplicationRepository;
    protected $profileRepository;

    public function __construct(
        ProfileApplicationRepository $profileApplicationRepository,
        ProfileRepository $profileRepository
    ) {
        $this->profileApplicationRepository = $profileApplicationRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create profile application
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'application' => 'required',
            'level' => 'required|numeric|min:0|max:5',
            'description' => 'required',
            'user_id' => 'numeric|min:0'
        ]);
        
        $input = $request->only([
            'application',
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

        $profileApplication = $this->profileApplicationRepository->create($input);

        return $this->respondCreated([
            'message' => __('api.profile_application.create.success'),
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
        $limit = $request->get('limit', PROFILE_APPLICATION_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'title', 'order_by']);
        $isAdmin = (auth()->user()->role == ADMIN);

        return ProfileApplicationResource::collection(
            $this->profileApplicationRepository->getListApplicationByProfileId(
                $isAdmin ? $id : auth()->user()->id,
                $params,
                $limit
            )
        );
    }

    /**
     * Update profile application
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        // Check profile application exist
        $profileApplication = $this->profileApplicationRepository->find($request->get('id'));
        if (null == $profileApplication) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        $validation = Validator::make($request->all(), [
            'application' => 'sometimes|required',
            'level' => 'numeric|min:0|max:5',
            'description' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $input = $request->only([
            'id',
            'application',
            'level',
            'description'
        ]);

        // Update to database
        $profileApplication = $this->profileApplicationRepository->update($profileApplication, $input);

        return $this->respondWithData([
            'message' => __('api.profile_application.update.success'),
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

        // Check profile application exist
        $profileApplication = $this->profileApplicationRepository->find($request->get('id'));
        if (null == $profileApplication) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->profileApplicationRepository->delete($profileApplication)) {
            return $this->respondWithData([
                'message' => __('api.profile_application.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
