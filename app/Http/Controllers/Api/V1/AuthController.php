<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Common;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Repositories\AdminRepository;
use App\Repositories\ProfileRepository;

class AuthController extends APIController
{
    protected $profileRepository;
    protected $adminRepository;

    public function __construct(
        ProfileRepository $profileRepository,
        AdminRepository $adminRepository
    ) {
        $this->profileRepository = $profileRepository;
        $this->adminRepository = $adminRepository;
    }
    /**
     * Log the user in.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        
        $validation = Validator::make($request->all(), [
            'type' => "required",
            'login_type' => "required"
        ]);

        $login_type = $request->input('login_type', null);
        if ($this->isSignUpAccount($login_type)) {
            $validation->addRules([
                'email' => 'required',
                'password' => 'required',
            ]);
        } else {
            $validation->addRules([
                'azure_token' => 'required',
            ]);
        }

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }


        $domainCanRegisterAdmin = explode(',', env('DOMAIN_CAN_REGISTER_FOR_ADMIN'));
        $domainCanRegisterProfile = explode(',', env('DOMAIN_CAN_REGISTER_FOR_PROFILE'));

        // Init variables
        $token = null;
        $role = null;
        $user = null;
        $type = $request->input('type', null);
        

        // Check login type if exist
        // if ($type && !in_array($type, [PROFILE, ADMIN])) {
        //     return $this->throwValidation(__('api.messages.access_denied'));
        // }

        try {
            $userData = [
                'id' => $request->input($login_type),
                'email' => $request->input('email')
            ];
            // Call to microsoft API to check user
            if (!$this->isSignUpAccount($login_type)) {
                $userData = Common::getAzureAccountData($request->input('azure_token'), $login_type);
            }

            $userDomain = Common::getUserDomainFromAzureUserData($userData);

            // Denied if cannot get information from social
            if (empty($userData['id'])) {
                return $this->throwValidation(__('api.messages.access_denied'));
            }

            // Check if the user is authorized to register
            if (!in_array($userDomain, $domainCanRegisterAdmin) && !in_array($userDomain, $domainCanRegisterProfile)) {
                return $this->throwValidation(__('api.messages.access_denied'));
            }

            $guard = $request->input('type').'-api';
            
            if ($this->isSignUpAccount($login_type)) {
                $credentials = $request->only(['email', 'password']);
                $credentials['status'] = 1;
                $token = auth()->guard($guard)->attempt($credentials);
            } else {
                $repository = $type == ADMIN ? $this->adminRepository : $this->profileRepository;
                
                $user = $repository->getDataByField('email', Common::getUserEmail($userData));

                if (null !== $user) {
                    $token = auth()->guard($guard)->login($user);
                }
            }

            if (!$token) {
                return $this->throwValidation(__('api.messages.login.error'));
            }
        } catch (JWTException $e) {
            return $this->respondInternalError($e->getMessage());
        }


        return $this->respondWithData([
            'message' => __('api.messages.login.success'),
            'token' => $token,
            'expires_in' => config('jwt.refresh_ttl'),
            'is_first_time' => 0,
            'role' => $role,
            'admin_roles' => $role == ADMIN && $user instanceof Admin ?
                $user->adminRoles->pluck('role')->all() : null,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->invalidate(true);
        } catch (JWTException $e) {
            return $this->respondInternalError($e->getMessage());
        }

        return $this->respondWithData([
            'message' => __('api.messages.logout.success'),
        ]);
    }
}
