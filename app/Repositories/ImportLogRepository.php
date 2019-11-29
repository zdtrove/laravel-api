<?php

namespace App\Repositories;

use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ImportLogRepository.
 */
class ImportLogRepository extends BaseRepository
{
    public function setModel()
    {
        return ImportLog::class;
    }

    /**
     * Get data for table
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()->withStatus();
    }

    /**
     * @param array $input
     *
     * @throws \Exception
     *
     * @return ImportLog
     */
    public function create(array $input)
    {
        $importLog = null;
        DB::transaction(function () use ($input, &$importLog) {
            try {
                // Insert to DB
                $importLog = ImportLog::create($input);
            } catch (\Exception $e) {
                throw new \Exception(__('api.import_log.create.error'));
            }
        });

        return $importLog;
    }


    /**
     * Get list import log
     *
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListImportLog($params, $limit)
    {
        try {
            $query = ImportLog::get();

            $paginate = $this->generateParams($query, $params)->paginate($limit);

            return $paginate;
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }
}
