<?php

namespace App\Repositories;

use App\Models\Award;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AwardRepository.
 */
class AwardRepository extends BaseRepository
{
    public function setModel()
    {
        return Award::class;
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
        $award = null;
        DB::transaction(function () use ($input, &$award) {
            try {
                // Insert to DB
                $award = Award::create($input);
            } catch (\Exception $e) {
                throw new \Exception(__('api.award.create.error'));
            }
        });

        return $award;
    }

    /**
     * Update award.
     *
     * @param Award $award
     * @param array $input
     *
     * @return mixed
     */
    public function update(Award $award, array $input)
    {
        DB::transaction(function () use ($input, &$award) {
            try {
                // Uploading Image
                if (!empty($input['thumbnail']) && array_key_exists('thumbnail', $input)) {
                    $uploadPath = PORTFOLIO_UPLOAD_PATH . $award->id . DIRECTORY_SEPARATOR;
                    if ($input['thumbnail'] = $this->uploadImage($input['thumbnail'], $uploadPath)) {
                        $this->deleteOldFile($award->thumbnail, $uploadPath);
                    }
                }

                $award->fill($input)->save();
            } catch (\Exception $e) {
                throw new \Exception(__('api.award.update.error'));
            }
        });

        return $award;
    }

    /**
     * Delete record
     *
     * @param Award $award
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(Award $award, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$award) {
            try {
                if (!empty($conditions)) {
                    $result = Award::where($conditions)->delete($award->id);
                } else {
                    $result = Award::destroy($award->id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.award.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get list award based on profile id
     *
     * @param int $profileId
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListAwardByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = Award::where('profile_id', $profileId);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
