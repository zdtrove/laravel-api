<?php

namespace App\Repositories;

use App\Models\Career;
use App\Models\Company;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class CareerRepository.
 */
class CareerRepository extends BaseRepository
{
    public function setModel()
    {
        return Career::class;
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
        $career = null;
        DB::transaction(function () use ($input, &$career) {
            try {
                if (isset($input['company'])) {
                    $company = Company::updateOrCreate([
                        'title' => $input['company']
                    ], [
                        'count' => DB::raw('count+1')
                    ]);
                    $input['company_id'] = $company->id;
                }
                if (isset($input['occupation'])) {
                    $occupation = Occupation::updateOrCreate([
                        'title' => $input['occupation']
                    ], [
                        'count' => DB::raw('count+1')
                    ]);
                    $input['occupation_id'] = $occupation->id;
                }
                // Insert to DB
                $career = Career::create($input);
            } catch (\Exception $e) {
                throw new \Exception(__('api.career.create.error'));
            }
        });

        return $career;
    }

    /**
     * Update Career.
     *
     * @param Career $career
     * @param array $input
     *
     * @return mixed
     */
    public function update(Career $career, array $input)
    {
        DB::transaction(function () use ($input, &$career) {
            try {
                // Decrement count company, occupation
                Company::where('id', $career->company_id)->update([
                    'count' => DB::raw('count-1'),
                ]);
                Occupation::where('id', $career->occupation_id)->update([
                    'count' => DB::raw('count-1'),
                ]);

                // Change or update again company, occupation
                $company = Company::updateOrCreate([
                    'title' => $input['company']
                ], [
                    'count' => DB::raw('count+1')
                ]);
                $occupation = Occupation::updateOrCreate([
                    'title' => $input['occupation']
                ], [
                    'count' => DB::raw('count+1')
                ]);

                // Update career
                $input['company_id'] = $company->id;
                $input['occupation_id'] = $occupation->id;
                $career->fill($input)->save();
            } catch (\Exception $e) {
                throw new \Exception(__('api.career.update.error'));
            }
        });

        return $career;
    }

    /**
     * Delete record
     *
     * @param Career $career
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(Career $career, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$career) {
            try {
                Company::where('id', $career['company_id'])->update([
                    'count' => DB::raw('count-1'),
                ]);
                Occupation::where('id', $career['occupation_id'])->update([
                    'count' => DB::raw('count-1'),
                ]);
                $result = Career::where($conditions)->where('id', $career->id)->delete();
            } catch (\Exception $e) {
                throw new \Exception(__('api.career.delete.error'));
            }
        });

        return ($result > 0);
    }


    /**
     * Get list career based on profile id
     *
     * @param int $profileId
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListCareerByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = Career::where('profile_id', $profileId)->with([
                'company' => function ($query) {
                    $query->select('id', 'title');
                }
            ])->with([
                'occupation' => function ($query) {
                    $query->select('id', 'title');
                }
            ]);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
