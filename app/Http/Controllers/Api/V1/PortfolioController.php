<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\PortfolioResource;
use App\Repositories\PortfolioRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Validator;

class PortfolioController extends APIController
{
    protected $portfolioRepository;
    protected $profileRepository;

    public function __construct(
        PortfolioRepository $portfolioRepository,
        ProfileRepository $profileRepository
    ) {
        $this->portfolioRepository = $portfolioRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create portfolio
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
            'position' => 'required',
            'description' => 'required',
            'thumbnail' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_PORTFOLIO_THUMBNAIL_UPLOAD_SIZE,
            'work_from' => 'required|date_format:"Y-m-d"',
            'work_to' => 'nullable|date_format:"Y-m-d"|after:work_from',
            'user_id' => 'numeric|min:0'
        ]);

        $input = $request->only([
            'title',
            'position',
            'ref_url',
            'description',
            'thumbnail',
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

        $portfolio = $this->portfolioRepository->create($input);

        return $this->respondCreated([
            'message' => __('api.portfolio.create.success'),
            'data' => $portfolio
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

        return PortfolioResource::collection(
            $this->portfolioRepository->getListPortfolioByProfileId(
                $isAdmin ? $id : auth()->user()->id,
                $params,
                $limit
            )
        );
    }

    /**
     * Update portfolio
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        // Check portfolio exist
        $portfolio = $this->portfolioRepository->find($request->get('id'));
        if (null == $portfolio) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        $validation = Validator::make($request->all(), [
            'title' => 'sometimes|required',
            'position' => 'sometimes|required',
            'description' => 'sometimes|required',
            'thumbnail' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_PORTFOLIO_THUMBNAIL_UPLOAD_SIZE,
            'work_from' => 'sometimes|required|date_format:"Y-m-d"',
            'work_to' => 'nullable|date_format:"Y-m-d"|after:work_from'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        // Update to database
        $input = $request->only([
            'id',
            'title',
            'position',
            'ref_url',
            'description',
            'thumbnail',
            'work_from',
            'work_to'
        ]);

        $portfolio = $this->portfolioRepository->update($portfolio, $input);

        return $this->respondWithData([
            'message' => __('api.portfolio.update.success'),
            'data' => $portfolio
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

        // Check portfolio exist
        $portfolio = $this->portfolioRepository->find($request->get('id'));
        if (null == $portfolio) {
            return $this->respondNotFound(__('api.messages.not_found'));
        }

        if ($this->portfolioRepository->delete($portfolio)) {
            return $this->respondWithData([
                'message' => __('api.portfolio.delete.success')
            ]);
        }

        return $this->respondNotFound(__('api.messages.not_found'));
    }
}
