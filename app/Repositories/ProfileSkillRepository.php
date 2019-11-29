<?php

namespace App\Repositories;

use App\Models\ProfileSkill;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ProfileSkillRepository.
 */
class ProfileSkillRepository extends BaseRepository
{
    public function setModel()
    {
        return ProfileSkill::class;
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
        $profileSkill = null;
        DB::transaction(function () use ($input, &$profileSkill) {
            try {
                if (isset($input['skill'])) {
                    $skill = Skill::updateOrCreate([
                        'title' => $input['skill']
                    ], [
                        'count' => DB::raw('count+1')
                    ]);
                    $input['skill_id'] = $skill->id;
                }
                // Insert to DB
                $profileSkill = ProfileSkill::create($input)->load('skill');
            } catch (\Exception $e) {
                throw new \Exception(__('api.profile_skill.create.error'));
            }
        });

        return $profileSkill;
    }

    /**
     * Update profile skill.
     *
     * @param ProfileSkill $profileSkill
     * @param array $input
     *
     * @return mixed
     */
    public function update(ProfileSkill $profileSkill, array $input)
    {
        DB::transaction(function () use ($input, &$profileSkill) {
            try {
                // Decrement count company, occupation
                Skill::where('id', $profileSkill->skill_id)->update([
                    'count' => DB::raw('count-1'),
                ]);

                // Change or update skill
                $skill = Skill::updateOrCreate([
                    'title' => $input['skill']
                ], [
                    'count' => DB::raw('count+1')
                ]);

                // Update profile skill
                $input['skill_id'] = $skill->id;
                $profileSkill->fill($input)->save();
                $profileSkill->load('skill');
            } catch (\Exception $e) {
                throw new \Exception(__('api.profile_skill.update.error'));
            }
        });

        return $profileSkill;
    }

    /**
     * Delete record
     *
     * @param ProfileSkill $profileSkill
     * @param array $conditions
     *
     * @return boolean
     */
    public function delete(ProfileSkill $profileSkill, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use (&$result, $conditions, &$profileSkill) {
            try {
                Skill::where('id', $profileSkill['skill_id'])->update([
                    'count' => DB::raw('count-1'),
                ]);
                $result = ProfileSkill::where($conditions)->where('id', $profileSkill->id)->delete();
            } catch (\Exception $e) {
                throw new \Exception(__('api.profile_skill.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get list skill based on profile id
     *
     * @param int $profileId
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListSkillByProfileId(int $profileId, $params, $limit)
    {
        try {
            $query = ProfileSkill::where('profile_id', $profileId)->with([
                'skill' => function ($query) {
                    $query->select('id', 'title');
                }
            ]);

            return $this->generateParams($query, $params)->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
