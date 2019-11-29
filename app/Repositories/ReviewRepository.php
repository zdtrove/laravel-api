<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ReviewRepository.
 */
class ReviewRepository extends BaseRepository
{
    public function setModel()
    {
        return Review::class;
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
        $review = null;
        DB::transaction(function () use ($input, &$review) {
            try {
                // Check image file
                if (!empty($input['refer_file'])) {
                    $imageObject = $input['refer_file'];
                    $imageName = time() . '.' . $imageObject->extension();
                    $input['refer_file'] = $imageName;
                }

                // Insert to DB
                $review = Review::create($input);

                // Uploading Image
                if (isset($imageObject) && isset($imageName)) {
                    $uploadPath = REVIEW_UPLOAD_PATH . $review->id . DIRECTORY_SEPARATOR;
                    $this->uploadImage($imageObject, $uploadPath, $imageName);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.review.create.error'));
            }
        });

        return $review;
    }

    /**
     * Update review.
     *
     * @param Review $review
     * @param array $input
     *
     * @return mixed
     */
    public function update(Review $review, array $input)
    {
        DB::transaction(function () use ($input, &$review) {
            try {
                // Uploading Image
                if (!empty($input['refer_file']) && array_key_exists('refer_file', $input)) {
                    $uploadPath = REVIEW_UPLOAD_PATH . $review->id . DIRECTORY_SEPARATOR;
                    if ($input['refer_file'] = $this->uploadImage($input['refer_file'], $uploadPath)) {
                        $this->deleteOldFile($review->refer_file, $uploadPath);
                    }
                }

                $review->fill($input)->save();
            } catch (\Exception $e) {
                throw new \Exception(__('api.review.update.error'));
            }
        });

        return $review;
    }

    /**
     * Delete record
     *
     * @param Review $review
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(Review $review, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$review) {
            try {
                if (!empty($conditions)) {
                    $result = Review::where($conditions)->delete($review->id);
                } else {
                    $result = Review::destroy($review->id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.review.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get list review based on profile id
     *
     * @param int $profileId
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListReviewByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = Review::where('profile_id', $profileId)->with(['admin' => function ($query) {
                $query->select(['id', 'name_kanji', 'name_furigana', 'image'])->withTrashed();
            }]);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
