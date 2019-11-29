<?php

namespace App\Repositories;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PortfolioRepository.
 */
class PortfolioRepository extends BaseRepository
{
    public function setModel()
    {
        return Portfolio::class;
    }

    /**
     * Get data for table
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->status()->query()->withStatus();
    }

    /**
     * @param array $input
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function create(array $input)
    {
        $portfolio = null;
        DB::transaction(function () use ($input, &$portfolio) {
            try {
                // Check image file
                if (!empty($input['thumbnail'])) {
                    $imageObject = $input['thumbnail'];
                    $imageName = time() . '.' . $imageObject->extension();
                    $input['thumbnail'] = $imageName;
                }

                // Insert to DB
                $portfolio = Portfolio::create($input);

                // Uploading Image
                if (isset($imageObject) && isset($imageName)) {
                    $uploadPath = PORTFOLIO_UPLOAD_PATH . $portfolio->id . DIRECTORY_SEPARATOR;
                    $this->uploadImage($imageObject, $uploadPath, $imageName);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.portfolio.create.error'));
            }
        });

        return $portfolio;
    }

    /**
     * Update portfolio.
     *
     * @param Portfolio $portfolio
     * @param array $input
     *
     * @return mixed
     */
    public function update(Portfolio $portfolio, array $input)
    {
        DB::transaction(function () use ($input, &$portfolio) {
            try {
                // Uploading Image
                if (!empty($input['thumbnail']) && array_key_exists('thumbnail', $input)) {
                    $uploadPath = PORTFOLIO_UPLOAD_PATH . $portfolio->id . DIRECTORY_SEPARATOR;
                    if ($input['thumbnail'] = $this->uploadImage($input['thumbnail'], $uploadPath)) {
                        $this->deleteOldFile($portfolio->thumbnail, $uploadPath);
                    }
                }

                $portfolio->fill($input)->save();
            } catch (\Exception $e) {
                throw new \Exception(__('api.portfolio.update.error'));
            }
        });

        return $portfolio;
    }

    /**
     * Delete record
     *
     * @param Portfolio $portfolio
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(Portfolio $portfolio, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$portfolio) {
            try {
                if (!empty($conditions)) {
                    $result = Portfolio::where($conditions)->delete($portfolio->id);
                } else {
                    $result = Portfolio::destroy($portfolio->id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.portfolio.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get list portfolio based on profile id
     *
     * @param int $profileId
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListPortfolioByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = Portfolio::where('profile_id', $profileId);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
