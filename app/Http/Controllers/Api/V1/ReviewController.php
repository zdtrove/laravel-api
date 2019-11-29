<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AwardResource;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends APIController
{
    protected $reviewRepository;

    public function __construct(
        ReviewRepository $reviewRepository
    ) {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Create review
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
            'profile_id' => 'required|numeric',
            'project_name' => 'required',
            'project_detail' => 'required',
            'project_start' => 'required|date_format:"Y-m-d"',
            'project_end' => 'required|date_format:"Y-m-d"|after:project_start',
            'rating' => 'required',
            'comment' => 'required',
            'refer_file' => 'mimes:jpeg,jpg,png,gif,pdf,pptx|max:' . MAX_PDF_UPLOAD_SIZE,
        ], $messages);
        if (auth()->user()->role == ADMIN) {
            $input = $request->only([
                'profile_id',
                'project_name',
                'project_detail',
                'project_start',
                'project_end',
                'rating',
                'comment',
                'refer_url',
                'refer_file'
            ]);
            $input['admin_id'] = auth()->user()->id;

            if ($validation->fails()) {
                return $this->throwValidation($validation->messages()->jsonSerialize());
            }
            $this->reviewRepository->create($input);

            return $this->respondCreated([
                'message' => __('api.review.create.success')
            ]);
        }

        return $this->respondUnauthorized(__('api.messages.access_denied'));
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
        $limit = $request->get('limit', REVIEW_LIMIT_PER_PAGE);
        $params = $request->only(['page', 'title', 'order_by']);

        return AwardResource::collection(
            $this->reviewRepository->getListReviewByProfileId($id, $params, $limit)
        );
    }

    /**
     * Update review
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        // Check review exist or belong to current user
        $review = $this->reviewRepository->find($request->get('id'));
        if (null == $review || $review->admin_id != auth()->user()->id) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'project_name' => 'sometimes|required',
            'project_detail' => 'sometimes|required',
            'project_start' => 'sometimes|required|date_format:"Y-m-d"',
            'project_end' => 'sometimes|required|date_format:"Y-m-d"|after:project_start',
            'rating' => 'sometimes|required',
            'comment' => 'sometimes|required',
            'refer_file' => 'sometimes|mimes:jpeg,jpg,png,gif,pdf,pptx|max:' . MAX_PDF_UPLOAD_SIZE,
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Update to database
        $input = $request->only([
            'project_name',
            'project_detail',
            'project_start',
            'project_end',
            'rating',
            'comment',
            'refer_url',
            'refer_file'
        ]);

        $review = $this->reviewRepository->update($review, $input);

        return $this->respondWithData([
            'message' => __('api.review.update.success'),
            'data' => $review
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

        // Check review exist or belong to current user
        $review = $this->reviewRepository->find($request->get('id'));
        if (null == $review || $review->profile_id != auth()->user()->id) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->reviewRepository->delete($review)) {
            return $this->respondWithData([
                'message' => __('api.review.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
