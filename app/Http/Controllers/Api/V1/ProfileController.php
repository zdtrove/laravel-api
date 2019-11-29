<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProfileResource;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends APIController
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
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
        $limit = $request->get('limit', PROFILE_LIMIT_PER_PAGE);
        $params = $request->only([
            'page',
            'name',
            'email',
            'code',
            'address',
            'phone',
            'order_by',
            'created_range',
            'status'
        ]);

        return ProfileResource::collection(
            $this->profileRepository->getPaginated($limit, $params)
        );
    }

    /**
     * Creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];
        $validation = Validator::make($request->all(), [
            'device_id' => [
                'required',
                Rule::unique('profiles')->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ],
            'email' => 'email|unique:profiles',
            'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $user = $this->profileRepository->create($request->only([
            'device_id',
            'name',
            'email',
            'image',
            'address',
            'phone',
            'facebook_id',
            'status'
        ]));

        return $this->respondCreated([
            'message' => __('api.profile.create.success')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id = null)
    {
        $profile = auth()->user()->role == ADMIN ?
            $this->profileRepository->find($id) : $this->profileRepository->find(auth()->user()->id);

        if (null == $profile) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        $profile = $profile->toArray();
        
        if (auth()->user()->role == ADMIN && count(auth()->user()->adminRoles) == 1 &&
                auth()->user()->adminRoles->contains('role', ADMIN_ROLE_REGULAR)) {
            $visibilities = isset($profile['visibilities']) ? $profile['visibilities'] : [];
            foreach ($visibilities as $field => $value) {
                if (!$value) {
                    $profile[$field] = '非公開';
                }
            }
        }

        return $this->respondWithData($profile);
    }

    /**
     * Update the specified resource
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $profile = $this->profileRepository->find($request->get('id'));
        if (null == $profile) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];
        $validation = Validator::make($request->all(), [
            'device_id' => [
                Rule::unique('profiles')->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ],
            'email' => 'email|unique:profiles',
            'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $this->profileRepository->update(
            $profile,
            $request->only(['device_id', 'name', 'email', 'image', 'address', 'phone', 'facebook_id', 'status'])
        );

        return $this->respondWithData([
            'message' => __('api.profile.update.success')
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
        if ($this->profileRepository->delete($request->get('id', 0))) {
            return $this->respondWithData([
                'message' => __('api.profile.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
