<?php

namespace App\Repositories;

use App\Imports\AdminsImport;
use App\Models\Admin;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AdminRepository.
 */
class AdminRepository extends BaseRepository
{
    public function setModel()
    {
        return Admin::class;
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
     * @return Admin
     * @throws \Exception
     *
     */
    public function create(array $input)
    {
        $admin = null;
        DB::transaction(function () use ($input, &$admin) {
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

                // Insert to DB
                $admin = Admin::create($input);

                // Uploading Image
                if (isset($imageObject) && isset($imageName)) {
                    $uploadPath = ADMIN_UPLOAD_PATH . $admin->id . DIRECTORY_SEPARATOR;
                    $this->uploadImage($imageObject, $uploadPath, $imageName, ['width' => 300, 'height' => 300]);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.admin.create.error'));
            }
        });

        return $admin;
    }

    /**
     * Update Admin.
     *
     * @param Admin $admin
     * @param array $input
     * @param array $adminRoleNames Eg: ['regular', 'manager', 'editor']. See APp\Models\AdminRole.
     * @return Admin
     */
    public function update(Admin $admin, array $input, array $adminRoleNames = [ADMIN_ROLE_REGULAR])
    {
        if (!in_array(ADMIN_ROLE_REGULAR, $adminRoleNames)) {
            // admin roles must have at least regular role
            $adminRoleNames[] = ADMIN_ROLE_REGULAR;
        }
        DB::transaction(function () use (&$admin, $input, $adminRoleNames) {
            try {
                // Uploading Image
                if (array_key_exists('image', $input)) {
                    $uploadPath = ADMIN_UPLOAD_PATH . $admin->id . DIRECTORY_SEPARATOR;
                    if ($input['image'] = $this->uploadImage(
                        $input['image'],
                        $uploadPath,
                        null,
                        ['width' => 300, 'height' => 300]
                    )) {
                        $this->deleteOldFile($admin->image, $uploadPath);
                    }
                }

                // Encrypt password if have
                if (!empty($input['password'])) {
                    $input['password'] = bcrypt($input['password']);
                }

                // Save to DB
                $admin->fill($input)->save();

                (new AdminRoleRepository())->sync($admin->id, $adminRoleNames);
            } catch (\Exception $e) {
                throw new \Exception(__('api.admin.update.error'));
            }
        });

        return $admin;
    }

    /**
     * Delete record
     *
     * @param integer $id
     * @param array $conditions
     *
     * @return boolean
     * @throws \Exception
     *
     */
    public function delete(int $id, array $conditions = [])
    {
        $result = 0;
        DB::transaction(function () use ($id, &$result, $conditions) {
            try {
                if (!empty($conditions)) {
                    $result = Admin::where($conditions)->delete($id);
                } else {
                    $result = Admin::destroy($id);
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.admin.delete.error'));
            }
        });

        return ($result > 0);
    }

    /**
     * Get admin data by email
     *
     * @param $email
     *
     * @return mixed
     */
    public function getAdminByEmail($email)
    {
        $user = Admin::where('email', $email)->first();
        return $user;
    }

    /**
     * Get list admin
     *
     * @param $params
     * @param $limit
     *
     * @return mixed
     */
    public function getListAdmin($params, $limit)
    {
        try {
            $paginate = $this->generateParams(Admin::query(), $params)->paginate($limit);

            return $paginate;
        } catch (\Exception $e) {
            throw new BadRequestHttpException(__('api.messages.bad_request'));
        }
    }


    /**
     * Import admin list from csv file
     *
     * @param UploadedFile $csvFile
     * @return bool
     */
    public function importAdminList(UploadedFile $csvFile)
    {
        $fileName = date('YmdHis', time()) . '_' . $csvFile->getClientOriginalName();
        $result = true;
        $user = auth()->user();

        // Store file to server
        $storage = Storage::disk('local');
        $storage->put(IMPORT_LOGS_PATH . $fileName, file_get_contents($csvFile->getRealPath()));

        DB::transaction(function () use (&$result, $csvFile, $fileName, $user) {
            try {
                // Set delete flag before insert
                Admin::query()->update([
                    'deleted_at' => now()
                ]);

                // Execute import
                Excel::import(new AdminsImport($fileName), $csvFile);

                // Save to log when complete
                ImportLog::create([
                    'admin_name' => $user->name_kanji,
                    'admin_email' => $user->email,
                    'status' => 'SUCCESS',
                    'message' => __('api.management.import.success'),
                    'file_name' => $fileName
                ]);
            } catch (\Exception $e) {
                // Fix cannot rollback deleted flag when import wrong data.
                DB::rollBack();
                ImportLog::create([
                    'admin_name' => $user->name_kanji,
                    'admin_email' => $user->email,
                    'status' => 'ERROR',
                    'message' => $e->getMessage(),
                    'file_name' => $fileName
                ]);
                $result = false;
            }
        });

        return $result;
    }
}
