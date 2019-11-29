<?php

namespace App\Repositories;

use App\Models\Application;
use App\Models\ProfileApplication;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ProfileApplicationRepository.
 */
class ProfileApplicationRepository extends BaseRepository
{
    public function setModel()
    {
        return ProfileApplication::class;
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
        $profileApplication = null;
        DB::transaction(function () use ($input, &$profileApplication) {
            try {
                if (isset($input['application'])) {
                    $application = Application::updateOrCreate([
                        'title' => $input['application']
                    ], [
                        'count' => DB::raw('count+1')
                    ]);
                    $input['application_id'] = $application->id;
                }
                // Insert to DB
                $profileApplication = ProfileApplication::create($input)->load('application');
            } catch (\Exception $e) {
                throw new \Exception(__('api.profile_application.create.error'));
            }
        });

        return $profileApplication;
    }

    /**
     * Update profile_application.
     *
     * @param ProfileApplication $profileApplication
     * @param array $input
     *
     * @return mixed
     */
    public function update(ProfileApplication $profileApplication, array $input)
    {
        DB::transaction(function () use ($input, &$profileApplication) {
            try {
                // Decrement count company, occupation
                Application::where('id', $profileApplication->application_id)->update([
                    'count' => DB::raw('count-1'),
                ]);

                // Change or update application
                $application = Application::updateOrCreate([
                    'title' => $input['application']
                ], [
                    'count' => DB::raw('count+1')
                ]);

                // Update profile application
                $input['application_id'] = $application->id;
                $profileApplication->fill($input)->save();
                $profileApplication->load('application');
            } catch (\Exception $e) {
                throw new \Exception(__('api.career.update.error'));
            }
        });

        return $profileApplication;
    }

    /**
     * Delete record
     *
     * @param ProfileApplication $profileApplication
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(ProfileApplication $profileApplication, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$profileApplication) {
            try {
                Application::where('id', $profileApplication['application_id'])->update([
                    'count' => DB::raw('count-1'),
                ]);
                $result = ProfileApplication::where($conditions)->where('id', $profileApplication->id)->delete();
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
    public function getListApplicationByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = ProfileApplication::where('profile_id', $profileId)->with([
                'application' => function ($query) {
                    $query->select('id', 'title');
                }
            ]);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
