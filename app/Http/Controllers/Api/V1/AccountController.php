<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Common;
use App\Repositories\AdminRepository;
use App\Repositories\AdminRoleRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\Mail\MailFactory;
use Validator;
use Datetime;

class AccountController extends APIController
{
    protected $profileRepository;
    protected $adminRepository;
    protected $adminRoleRepository;
    protected $emailFactory;

    public function __construct(
        ProfileRepository $profileRepository,
        AdminRepository $adminRepository,
        AdminRoleRepository $adminRoleRepository,
        MailFactory $emailFactory
    ) {
        $this->profileRepository = $profileRepository;
        $this->adminRepository = $adminRepository;
        $this->adminRoleRepository = $adminRoleRepository;
        $this->emailFactory = $emailFactory;
    }

    /**
     * Create admin|profile
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in([ADMIN, PROFILE])],
            'register_type' => 'required'
        ], $messages);
        $type = $request->input('type');
        $register_type = $request->input('register_type');

        if ($type == ADMIN) {
            $validation->addRules([
                'name_kanji' => 'required',
                'name_furigana' => 'required',
                'uuid' => 'required',
                'azure_token' => 'required',
                'email' => 'required|email',
            ]);
            $input = $request->only([
                'name_kanji',
                'name_furigana',
                'uuid',
                'azure_token',
                'email'
            ]);
            $repository = $this->adminRepository;
        } else {
            $validation->addRules([
                'email' => 'required|email|unique:profiles',
                'name' => 'required'
            ]);
            if ($this->isSignUpAccount($register_type)) {
                $validation->addRules([
                    'password' => 'required|confirmed|min:6',
                ]);
            } else {
                $validation->addRules([
                    $register_type => 'required|unique:profiles',
                    'azure_token' => 'required',
                ]);
            }
            
            $input = $request->only([
                $register_type,
                'azure_token',
                'email',
                'name',
                'password'
            ]);
            $repository = $this->profileRepository;
        }

        
        

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }
        
        $userData = [
            'id' => $request->input($register_type),
            'email' => $request->input('email')
        ];
        // Call to social network API to check user
        if (!$this->isSignUpAccount($register_type)) {
            $userData = Common::getAzureAccountData($request->input('azure_token'), $register_type);
        }
        $userDomain = Common::getUserDomainFromAzureUserData($userData);

        
        // Check if the user is allowed to register on the system
        $domainCanRegister = (($type == PROFILE) ? explode(',', env('DOMAIN_CAN_REGISTER_FOR_PROFILE'))
            : explode(',', env('DOMAIN_CAN_REGISTER_FOR_ADMIN')));

        if (empty($userData) || $userData['id'] != $input[$register_type] || !in_array($userDomain, $domainCanRegister)) {
            return $this->throwValidation(__('api.messages.access_denied'));
        }
        
        if ($type == ADMIN) {
            $azureEmail = Common::getUserEmail($userData);
            $user = $repository->getAdminByEmail($azureEmail);
            if ($user != null) {
                if ($input['email'] != $azureEmail) {
                    // Valid exist email
                    $validation->addRules([
                        'email' => 'unique:admins,email,NULL,id,deleted_at,NULL',
                    ]);
                    if ($validation->fails()) {
                        return $this->throwValidation($validation->messages()->jsonSerialize());
                    }
                }
                
                // get admin role
                $currentAdminRole = [ADMIN_ROLE_REGULAR];
                $getRoleById = $this->adminRoleRepository->getRoleById($user->id)->pluck('role')->toArray();
                $currentAdminRole = array_values(array_unique(array_merge($currentAdminRole, $getRoleById)));
                
                $user = $repository->update($user, $input, $currentAdminRole);
            } else {
                return $this->throwValidation(__('api.messages.access_denied'));
            }
        } else {
            $tokenConfirm = $this->randomString(20);
            $input['token_confirm'] = $tokenConfirm;
            $input['status'] = ACCOUNT_NON_ACTIVE;
            $user = $repository->create($input);
            //Send mail active account
            if ($type == PROFILE) {
                $emailFactory = $this->emailFactory;
                $activeAccountMailObject = $emailFactory->generateMailType($emailFactory::ACTIVE_PROFILE);
                $activeAccountMailObject->name = $user->name;
                $urlActive = DOMAIN_CMS.'/active-account?email=' . $user->email . '&token=' . $tokenConfirm;
                $activeAccountMailObject->urlActive = $urlActive;
                $activeAccountMailObject->toEmail = $user->email ;
                
                $emailFactory->send($activeAccountMailObject);
            }
        }

        $token = auth()->login($user);
        $messages = $type == PROFILE ? __('api.profile.register.success') : __('api.admin.register.success');

        return $this->respondCreated([
            'message' => $messages,
            'token' => $token,
            'expires_in' => config('jwt.refresh_ttl')
        ]);
    }

    /**
     * Show profile
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function profile(Request $request)
    {
        $auth = auth()->user()->toArray();
        
        if (empty($auth['uuid']) && empty($auth['email']) && empty($auth['facebook']) && empty($auth['google']) ) {
            auth()->invalidate(true);
            return $this->respondUnauthorized(__('api.messages.access_denied'));
        }
        auth()->user()->visibilities();
        return $this->respondWithData($auth);
    }

    /**
     * Update profile
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function update(Request $request)
    {
        // Custom error message for upload file
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in([ADMIN, PROFILE])],
        ], $messages);
        $type = $request->input('type');
        
        // Check user_id, set profile_id
        if (!empty($request->get('user_id'))) {
            $user = $this->profileRepository->find($request->get('user_id'));
            if (!$user) {
                return $this->respondNotFound();
            }
        } else {
            $user = auth()->user();
        }
        
        if (auth()->user()->role == ADMIN || $type == PROFILE) {
            $validation->addRules([
                'pdf' => 'nullable|mimes:pdf,jpeg,jpg,png,gif|max:' . MAX_PDF_UPLOAD_SIZE,
                'name_kanji' => 'required',
                'name_furigana' => 'required',
                'sex' => 'required',
                'address' => 'required',
                'tel' => 'required',
                'email' => 'required|email|unique:profiles,email,'.$user->id,
                'appeal' => 'required',
                'job_title' => 'required',
                'birth' => 'date_format:"Y-m-d"'
            ]);

            $input = $request->only([
                'name_kanji',
                'name_furigana',
                'sex',
                'address',
                'tel',
                'email',
                'appeal',
                'job_title',
                'facebook',
                'twitter',
                'instagram',
                'birth',
                'site_url',
                'pdf',
                'visibilities',
                'deleted_fields'
            ]);
            if (Input::hasFile('image')) {
                $validation->addRules([
                    'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE,
                ]);
                $input['image'] = $request['image'];
            }
            $repository = $this->profileRepository;
        } elseif ($type == ADMIN) {
            $validation->addRules([
                'email' => 'required|email|unique:admins',
                'password' => 'required|confirmed',
                'image' => 'mimes:jpeg,jpg,png,gif|max:' . MAX_UPLOAD_SIZE
            ]);
            $input = $request->only([
                'name',
                'email',
                'password',
                'image'
            ]);
            $repository = $this->adminRepository;
        }
        
        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }
        $user = $repository->update($user, $input);
        if ($request->input('user_id') == 0) {
            auth()->setUser($user);
        }
        // Add message when update profile success
        if ($type == PROFILE) {
            return $this->respondWithData([
                'message' => __('api.profile.update.success')
            ]);
        }
        $user['message'] = __('api.admin.update.success');

        return $this->respondWithData($user);
    }

    public function active(Request $request) {
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in([PROFILE])],
            'email' => 'required',
            'token_confirm'=> 'required',
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $inputs = $request->only([
            'type',
            'email',
            'token_confirm'
        ]);

        $repository = $this->profileRepository;
        $params['status'] = ACCOUNT_NON_ACTIVE;
        $params['email'] = $inputs['email'];
        $params['token_confirm'] = $inputs['token_confirm'];
        $user = $repository->getDataByParam($params);

        if ($user) {
            $user = $repository->update($user, ['status' => STATUS_ACTIVED, 'token_confirm' => '']);
        } else {
            $this->setStatusCode(404);
            return $this->respondWithError([
                'message' => __('api.profile.active.error')
            ]);
        }
        return $this->respondCreated([
            'message' => __('api.profile.active.success')
        ]);
    }

    public function forgetPassword(Request $request) {
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in([PROFILE,ADMIN])],
            'email' => 'required',
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $input = $request->only([
            'type',
            'email'
        ]);
        $type = $input['type'];
        
        $repository = $type == ADMIN ? $this->adminRepository : $this->profileRepository;
        $params['email'] = $input['email'];
        $user = $repository->getDataByParam($params);
        if ($user) {
            $tokenConfirm = $this->getNowTimestamp();
            $input['token_confirm'] = $tokenConfirm;
            $user = $repository->update($user, $input);
            //send mail reset password
            $emailFactory = $this->emailFactory;
            $resetPasswordMailObject = $emailFactory->generateMailType($emailFactory::RESET_PASSWORD);
            $resetPasswordMailObject->name = $user->name;
            $urlActive = DOMAIN_CMS.'/account/create-new-password?email=' . $user->email . '&token=' . $tokenConfirm;
            $resetPasswordMailObject->urlActive = $urlActive;
            $resetPasswordMailObject->toEmail = $user->email ;
            
            $emailFactory->send($resetPasswordMailObject);
        } else {
            $this->setStatusCode(404);
            return $this->respondWithError([
                'message' => __('api.profile.forget_password.error')
            ]);
        }
        return $this->respondCreated([
            'message' => __('api.profile.forget_password.success')
        ]);
    }

    public function createNewPassword(Request $request) {
        $messages = [
            'max' => sprintf(__('validation.max.custom_file'), MAX_UPLOAD_SIZE / 1024)
        ];

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in([PROFILE,ADMIN])],
            'email' => 'required',
            'token_confirm' => 'required',
            'password' => 'required|confirmed|min:6'
        ], $messages);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $input = $request->only([
            'type',
            'email',
            'token_confirm',
            'password'
        ]);
        $type = $input['type'];

        $repository = $type == ADMIN ? $this->adminRepository : $this->profileRepository;
        //Get user
        $params['email'] = $input['email'];
        $params['token_confirm'] = $input['token_confirm'];
        $user = $repository->getDataByParam($params);
        
        if ($user) {
            $tokken_confirm = $user->token_confirm;
            $now = $this->getNowTimestamp();
            $checkExpired = ($now - $tokken_confirm)/60;
            if ($checkExpired > EXPIRE_CREATE_PASSWORD) {
                $this->setStatusCode(STATUS_NOT_FOUND);
                return $this->respondWithError([
                    'message' => __('api.profile.create_new_password.expired')
                ]);
            }
            $input['token_confirm'] = '';
            $input['password'] = bcrypt($input['password']);
            $user = $repository->update($user, $input);
            
            if ($user) {
                return $this->respondCreated([
                    'message' => __('api.profile.create_new_password.success')
                ]);
            }
        }
        $this->setStatusCode(STATUS_NOT_FOUND);
        return $this->respondWithError([
            'message' => __('api.profile.create_new_password.error')
        ]);
        
    }
}