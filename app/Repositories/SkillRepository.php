<?php

namespace App\Repositories;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;

/**
 * Class SkillRepository.
 */
class SkillRepository extends BaseRepository
{
    public function setModel()
    {
        return Skill::class;
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
        $skill = null;
        DB::transaction(function () use ($input, &$skill) {
            try {
                $skill = Skill::create($input);
            } catch (Exception $e) {
                throw new Exception(__('api.skill.create.error'));
            }
        });

        return $skill;
    }

    /**
     * Update skill.
     *
     * @param Skill $skill
     * @param array $input
     *
     * @return mixed
     */
    public function update(Skill $skill, array $input)
    {
        DB::transaction(function () use ($input, &$skill) {
            try {
                $skill->fill($input)->save();
            } catch (Exception $e) {
                throw new Exception(__('api.skill.update.error'));
            }
        });

        return $skill;
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
                    $result = Skill::where($conditions)->delete($id);
                } else {
                    $result = Skill::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.skill.delete.error'));
            }
        });

        return ($result > 0);
    }
}
