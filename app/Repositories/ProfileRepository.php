<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\Visibility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ProfileRepository.
 */
class ProfileRepository extends BaseRepository
{
    public function setModel()
    {
        return Profile::class;
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
     * @throws Exception
     *
     * @return mixed
     */
    public function create(array $input)
    {
        $profile = null;
        DB::transaction(function () use ($input, &$profile) {
            try {
                // Encrypt password if have
                if (!empty($input['password'])) {
                    $input['password'] = bcrypt($input['password']);
                }
                
                // Check image file
                if (!empty($input['image'])) {
                    $imageObject = $input['image'];
                    $imageName = time() . '.' . $imageObject->extension();
                    $input['image'] = $imageName;
                }

                // Check pdf file
                if (!empty($input['pdf'])) {
                    $pdfObject = $input['pdf'];
                    $pdfName = time() . '.' . $pdfObject->getClientOriginalExtension();
                    $input['pdf'] = $pdfName;
                }



                // Insert to DB
                $profile = Profile::create($input);
                
                // Create visibilities
                if (isset($input['visibilities']) && is_array($input['visibilities'])) {
                    $input['visibilities']['profile_id'] = $profile->id;
                    $visibilities = $input['visibilities'];
                    Visibility::create($visibilities);
                }

                // Uploading Image
                if (isset($imageObject) && isset($imageName)) {
                    $uploadPath = PROFILE_UPLOAD_PATH . $profile->id . DIRECTORY_SEPARATOR;
                    $this->uploadImage($imageObject, $uploadPath, $imageName, ['width' => 300, 'height' => 300]);
                }

                // Uploading PDF
                if (isset($pdfObject) && isset($pdfName)) {
                    $uploadPath = PROFILE_UPLOAD_PATH . $profile->id . DIRECTORY_SEPARATOR;
                    $this->uploadImage($pdfObject, $uploadPath, $pdfName);
                }
            } catch (Exception $e) {
                throw new Exception(__('api.profile.create.error'));
            }
        });

        return $profile;
    }

    /**
     * Update profile.
     *
     * @param Profile $profile
     * @param array $input
     *
     * @return mixed
     */
    public function update(Profile $profile, array $input)
    {
        DB::transaction(function () use ($input, &$profile) {
            try {
                // Generate upload path
                $uploadPath = PROFILE_UPLOAD_PATH . $profile->id . DIRECTORY_SEPARATOR;

                // Uploading Image
                if (!empty($input['image']) && array_key_exists('image', $input)) {
                    if ($input['image'] = $this->uploadImage(
                        $input['image'],
                        $uploadPath,
                        null,
                        ['width' => 300, 'height' => 300]
                    )) {
                        $this->deleteOldFile($profile->image, $uploadPath);
                    }
                }

                // Uploading pdf
                if (!empty($input['pdf']) && array_key_exists('pdf', $input)) {
                    if ($input['pdf'] = $this->uploadImage($input['pdf'], $uploadPath, null)) {
                        $this->deleteOldFile($profile->pdf, $uploadPath);
                    }
                }

                // Delete or empty fields
                if (!empty($input['deleted_fields']) && is_array($input['deleted_fields'])) {
                    foreach ($input['deleted_fields'] as $field => $value) {
                        if ($value) {
                            // Force delete old file if this field is as file
                            @$this->deleteOldFile($profile->{$field}, $uploadPath);
                            $input[$field] = null;
                        }
                    }
                }

                // // Get old visibilities
                // $visibilities = $profile->visibilities->toArray();

                // // Update visibilities
                // if (!empty($visibilities) && !empty($input['visibilities']) && is_array($input['visibilities'])) {
                //     $visibilities = array_merge($visibilities, $input['visibilities']);
                //     Visibility::whereId($profile->visibilities->id)->update($visibilities);
                // }
                $profile->fill($input)->save();
            } catch (Exception $e) {
                throw new Exception(__('api.profile.update.error'));
            }
        });

        return $profile;
    }

    /**
     * Delete record
     *
     * @param integer $id
     * @param array $conditions
     *
     * @throws \Exception
     *
     * @return boolean
     */
    public function delete(int $id, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use ($id, &$result, $conditions) {
            try {
                if (!empty($conditions)) {
                    $result = Profile::where($conditions)->delete($id);
                } else {
                    $result = Profile::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.profile.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get list profile
     *
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListProfile($params, $limit)
    {
        try {
            $query = Profile::select(
                'profiles.id',
                'name_kanji',
                'appeal',
                'image',
                DB::raw('job_title as occupation')
            )
                ->with('applications', 'skills', 'occupations')
                ->orderBy('profiles.id', 'DESC');
            $keyword = !empty($params['query']) ? $params['query'] : null;
            if (!is_null($keyword)) {
                $words = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($words as $word) {
                    $query->where(function ($q) use ($word) {
                        $q->orWhere('name_kanji', 'LIKE', '%' . $word . '%')
                            ->orWhere('name_furigana', 'LIKE', '%' . $word . '%')
                            ->orWhere('appeal', 'LIKE', '%' . $word . '%')
                            ->orWhere('job_title', 'LIKE', '%' . $word . '%')
                            ->orWhereHas('skills', function ($q) use ($word) {
                                return $q->where('skills.title', 'LIKE', '%' . $word . '%');
                            })
                            ->orWhereHas('applications', function ($q) use ($word) {
                                return $q->where('applications.title', 'LIKE', '%' . $word . '%');
                            })
                            ->orWhereHas('occupations', function ($q) use ($word) {
                                return $q->where('occupations.title', 'LIKE', '%' . $word . '%');
                            });
                    });
                }
            }

            $paginate = $this->generateParams($query, $params)->paginate($limit);

            // Skip get visibilities to improve performance
            $data = $paginate->getCollection();
            $data->each(function ($item) {
                $item->setHidden(['visibilities']);
            });
            $paginate->setCollection($data);

            return $paginate;
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }


    /**
     * Get list profile after day
     *
     * @param int $days
     * @return mixed
     */
    public function getListProfileAfterDays($days = 0)
    {
        try {
            return Profile::select(
                'id',
                'name_kanji',
                'name_furigana',
                'image',
                'created_at',
                DB::raw('(
                    SELECT o.title FROM occupations o 
                        RIGHT JOIN careers c 
                        ON o.id = c.occupation_id 
                        WHERE profiles.id = c.profile_id 
                        ORDER BY c.work_to IS NULL DESC, c.work_to DESC 
                    LIMIT 1) as occupation')
            )
                ->where('created_at', '>=', Carbon::now()->startOfDay()->subDays($days))
                ->orderBy('created_at', 'desc')
                ->get()->each(function ($row) {
                    $row->setHidden(['visibilities']);
                });
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }

    public function getListProfileAfterDaysWithPaging($params, $limit, $days = 0)
    {
        try {
            $query = Profile::select(
                'id',
                'name_kanji',
                'name_furigana',
                'image',
                'created_at',
                DB::raw('(
                    SELECT o.title FROM occupations o 
                        RIGHT JOIN careers c 
                        ON o.id = c.occupation_id 
                        WHERE profiles.id = c.profile_id 
                        ORDER BY c.work_to IS NULL DESC, c.work_to DESC 
                    LIMIT 1) as occupation')
            )
                ->where('created_at', '>=', Carbon::now()->startOfDay()->subDays($days))
                ->orderBy('profiles.id', 'DESC');

            $paginate = $this->generateParams($query, $params)->paginate($limit);

            // Skip get visibilities to improve performance
            $data = $paginate->getCollection();
            $data->each(function ($item) {
                $item->setHidden(['visibilities']);
            });
            $paginate->setCollection($data);

            return $paginate;
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
