<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Support\Facades\DB;

/**
 * Class ApplicationRepository.
 */
class ApplicationRepository extends BaseRepository
{
    public function setModel()
    {
        return Application::class;
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
        $application = null;
        DB::transaction(function () use ($input, &$application) {
            try {
                $application = Application::create($input);
            } catch (Exception $e) {
                throw new Exception(__('api.application.create.error'));
            }
        });

        return $application;
    }

    /**
     * Update application.
     *
     * @param Application $application
     * @param array $input
     *
     * @return mixed
     */
    public function update(Application $application, array $input)
    {
        DB::transaction(function () use ($input, &$application) {
            try {
                $application->fill($input)->save();
            } catch (Exception $e) {
                throw new Exception(__('api.application.update.error'));
            }
        });

        return $application;
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
                    $result = Application::where($conditions)->delete($id);
                } else {
                    $result = Application::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.application.delete.error'));
            }
        });

        return ($result > 0);
    }
}
