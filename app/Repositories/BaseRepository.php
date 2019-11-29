<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->setModel();
    }

    abstract public function setModel();

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->query()->get();
    }

    /**
     * Get Paginated.
     *
     * @param int $limit
     * @param array $params
     *
     * @return mixed
     */
    public function getPaginated($limit, $params)
    {
        try {
            $query = $this->generateParams($this->query(), $params);

            return $query->paginate($limit);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }

    /**
     * Add search and order by to query builder
     *
     * @param object $query
     * @param array $params
     *
     * @return mixed
     */
    protected function generateParams($query, $params)
    {
        // Using table prefix to fix error when using join
        $tablePrefix = (new $this->model)->getTable() . '.';

        // Multi order by and sort
        $orderBy = json_decode(data_get($params, 'order_by'), true);
        if (is_array($orderBy)) {
            foreach ($orderBy as $key => $sort) {
                $query->orderBy($tablePrefix . $key, $sort);
            }
        }

        // Filter with status
        if (isset($params['status'])) {
            $query->where($tablePrefix . 'status', $params['status']);
        }

        // Search column
        $columns = array_except($params, ['page', 'order_by', 'status', 'created_range', 'query', 'with_or']);
        $searchWithOr = (isset($params['with_or']) && $params['with_or'] == 1);
        foreach ($columns as $column => $value) {
            if (is_array($value)) {
                $relationTable = $column . '.';
                foreach ($value as $subColumn => $subValue) {
                    if (is_numeric($subValue)) {
                        if ($searchWithOr) {
                            $query->orWhere($relationTable . $subColumn, $subValue);
                        } else {
                            $query->where($relationTable . $subColumn, $subValue);
                        }
                    } else {
                        if ($searchWithOr) {
                            $query->orWhere($relationTable . $subColumn, 'LIKE', '%' . $subValue . '%');
                        } else {
                            $query->where($relationTable . $subColumn, 'LIKE', '%' . $subValue . '%');
                        }
                    }
                }
            } else {
                if (is_numeric($value)) {
                    if ($searchWithOr) {
                        $query->orWhere($tablePrefix . $column, $value);
                    } else {
                        $query->where($tablePrefix . $column, $value);
                    }
                } else {
                    if ($searchWithOr) {
                        $query->orWhere($tablePrefix . $column, 'LIKE', '%' . $value . '%');
                    } else {
                        $query->where($tablePrefix . $column, 'LIKE', '%' . $value . '%');
                    }
                }
            }
        }

        // Search created range
        if (isset($params['created_range'])) {
            $dateRange = json_decode($params['created_range'], true);
            if (isset($dateRange['start-date']) && isset($dateRange['end-date'])) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dateRange['start-date'])->startOfDay()->toDateTimeString(),
                    Carbon::parse($dateRange['end-date'])->endOfDay()->toDateTimeString()
                ]);
            }
        }

        return $query;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->query()->count();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->query()->find($id);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return call_user_func($this->model . '::query');
    }

    /**
     * Upload image function
     *
     * @param $imageObject       Image object
     * @param string $uploadPath Path for upload
     * @param null $imageName Image name
     * @param array $resizeSize Only accept ['width' => xx, 'height' => xx] format
     *
     * @return string
     */
    public function uploadImage($imageObject, $uploadPath, $imageName = null, $resizeSize = array())
    {
        if (!empty($imageObject)) {
            $storage = Storage::disk('public');
            $imageName = $imageName ?: time() . '.' . $imageObject->extension();
            if (!empty($resizeSize) && isset($resizeSize['width']) && isset($resizeSize['height'])) {
                $resizedPhoto = Image::make($imageObject)
                    ->resize($resizeSize['width'], $resizeSize['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode($imageObject->extension(), 100);
                $photo = Image::make($resizedPhoto)
                    ->resizeCanvas($resizeSize['width'], $resizeSize['height'], 'center', false, 'ffffff')
                    ->encode($imageObject->extension(), 100);
                $storage->put($uploadPath . $imageName, $photo->__toString());
            } else {
                $storage->put($uploadPath . $imageName, file_get_contents($imageObject->getRealPath()));
            }
        }

        return $imageName;
    }

    /**
     * Remove old image
     *
     * @param string $imageName
     * @param string $uploadPath
     *
     * @return boolean
     */
    public function deleteOldFile($imageName, $uploadPath)
    {
        if (!empty($imageName)) {
            $storage = Storage::disk('public');

            return $storage->delete($uploadPath . $imageName);
        }

        return false;
    }

    public function getDataByField($field, $val)
    {
        $data = $this->query()->where($field, $val)->first();
        return $data;
    }

    public function getDataByParam($params) {
        $query = $this->query();
        if (!isset($params['status'])) {
            $params['status'] = STATUS_ACTIVED;
        }
        foreach ($params as $k => $val) {
            $query->where($k,$val);
        }
        return $query->first();
    }
}
