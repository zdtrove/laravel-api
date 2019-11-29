<?php

namespace App\Repositories;

use App\Models\Occupation;
use Illuminate\Support\Facades\DB;

/**
 * Class OccupationRepository.
 */
class OccupationRepository extends BaseRepository
{
    public function setModel()
    {
        return Occupation::class;
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
        $occupation = null;
        DB::transaction(function () use ($input, &$occupation) {
            try {
                $occupation = Occupation::create($input);
            } catch (Exception $e) {
                throw new Exception(__('api.occupation.create.error'));
            }
        });

        return $occupation;
    }

    /**
     * Update occupation.
     *
     * @param Occupation $occupation
     * @param array $input
     *
     * @return mixed
     */
    public function update(Occupation $occupation, array $input)
    {
        DB::transaction(function () use ($input, &$occupation) {
            try {
                $occupation->fill($input)->save();
            } catch (Exception $e) {
                throw new Exception(__('api.occupation.update.error'));
            }
        });

        return $occupation;
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
                    $result = Occupation::where($conditions)->delete($id);
                } else {
                    $result = Occupation::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.occupation.delete.error'));
            }
        });

        return ($result > 0);
    }
}
