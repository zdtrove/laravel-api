<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AdminResource;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Validator;

class AdminController extends APIController
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', ADMIN_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'name', 'email', 'order_by', 'created_range', 'status']);

        return AdminResource::collection(
            $this->adminRepository->getPaginated($limit, $params)
        );
    }

    /**
     * Creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed',
            'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE
        ], $messages);


        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $this->adminRepository->create($request->only([
            'name',
            'email',
            'password',
            'image',
            'status'
        ]));

        return $this->respondCreated([
            'message' => __('api.admin.create.success'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $admin = $this->adminRepository->find($request->get('id'));

        if (null == $admin) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        return $this->respondWithData($admin);
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
        $admin = $this->adminRepository->find($request->get('id'));
        if (null == $admin) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'email' => 'email|unique:admins,email,' . $admin->id,
            'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $this->adminRepository->update($admin, $request->only(['name', 'email', 'password', 'image', 'status']));

        return $this->respondWithData([
            'message' => __('api.admin.update.success')
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
        if ($this->adminRepository->delete($request->get('id', 0))) {
            return $this->respondWithData([
                'message' => __('api.admin.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
