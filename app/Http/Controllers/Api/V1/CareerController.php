<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CareerResource;
use App\Repositories\CareerRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Validator;

class CareerController extends APIController
{
    protected $careerRepository;
    protected $profileRepository;

    public function __construct(
        CareerRepository $careerRepository,
        ProfileRepository $profileRepository
    ) {
        $this->careerRepository = $careerRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create career
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'company' => 'required',
            'occupation' => 'required',
            'description' => 'required',
            'work_from' => 'required|date_format:"Y-m-d"',
            'work_to' => 'nullable|date_format:"Y-m-d"|after:work_from',
            'user_id' => 'numeric|min:0'
        ]);

        $input = $request->only([
            'company',
            'occupation',
            'description',
            'work_from',
            'work_to'
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

        $career = $this->careerRepository->create($input);

        return $this->respondCreated([
            'message' => __('api.career.create.success'),
            'data' => $career
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
        $limit = $request->get('limit', CAREER_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'title', 'order_by']);
        $isAdmin = (auth()->user()->role == ADMIN);

        return CareerResource::collection(
            $this->careerRepository->getListCareerByProfileId($isAdmin ? $id : auth()->user()->id, $params, $limit)
        );
    }

    /**
     * Update career
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        // Check career exist
        $career = $this->careerRepository->find($request->get('id'));
        if (null == $career) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        $validation = Validator::make($request->all(), [
            'company' => 'sometimes|required',
            'occupation' => 'sometimes|required',
            'work_from' => 'sometimes|required|date_format:"Y-m-d"',
            'work_to' => 'nullable|date_format:"Y-m-d"|after:work_from'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Update to database
        $input = $request->only([
            'id',
            'company',
            'occupation',
            'description',
            'work_from',
            'work_to'
        ]);

        $career = $this->careerRepository->update($career, $input);

        return $this->respondWithData([
            'message' => __('api.career.update.success'),
            'data' => $career
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

        // Check career exist
        $career = $this->careerRepository->find($request->get('id'));
        if (null == $career) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->careerRepository->delete($career)) {
            return $this->respondWithData([
                'message' => __('api.career.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
