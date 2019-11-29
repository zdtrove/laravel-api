<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

/**
 * Class CompanyRepository.
 */
class CompanyRepository extends BaseRepository
{
    public function setModel()
    {
        return Company::class;
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
        $company = null;
        DB::transaction(function () use ($input, &$company) {
            try {
                $company = Company::create($input);
            } catch (Exception $e) {
                throw new Exception(__('api.company.create.error'));
            }
        });

        return $company;
    }

    /**
     * Update company.
     *
     * @param Company $company
     * @param array $input
     *
     * @return mixed
     */
    public function update(Company $company, array $input)
    {
        DB::transaction(function () use ($input, &$company) {
            try {
                $company->fill($input)->save();
            } catch (Exception $e) {
                throw new Exception(__('api.company.update.error'));
            }
        });

        return $company;
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
                    $result = Company::where($conditions)->delete($id);
                } else {
                    $result = Company::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.company.delete.error'));
            }
        });

        return ($result > 0);
    }
}
