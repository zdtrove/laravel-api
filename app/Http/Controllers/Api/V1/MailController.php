<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\Notification;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends APIController
{
    protected $profileRepository;

    public function __construct(
        ProfileRepository $profileRepository
    ) {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Log the user in.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notify(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'subject' => 'required',
            'body' => 'required',
            'profile_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->jsonSerialize());
        }

        $input = $request->only([
            'email',
            'subject',
            'body',
            'profile_id'
        ]);

        try {
            $profile = $this->profileRepository->find($input['profile_id']);
            if ($profile == null) {
                return $this->throwValidation(__('api.mail.error'));
            }
            $loggedUser = auth()->user();
            $toProfileEmail = (auth()->user()->role == ADMIN) ? $profile->email : $input['email'];

            $profileNameKanji = $loggedUser->name_kanji;
            $profileEmail = $input['email'];
            $adminNameKanji = null;
            $adminEmail = null;

            if (auth()->user()->role == ADMIN) {
                $profileNameKanji = $profile->name_kanji;
                $profileEmail = $profile->email;
                $adminNameKanji = $loggedUser->name_kanji;
                $adminEmail = $input['email'];
            }

            // Send mail to profile
            Mail::to($profileEmail)->send(new Notification([
                'profile_name_kanji' => $profileNameKanji,
                'profile_email' => $profileEmail,
                'admin_name_kanji' => $adminNameKanji,
                'admin_email' => $adminEmail,
                'name_kanji' => auth()->user()->name_kanji,
                'email' => auth()->user()->role == ADMIN ? $input['email'] : $toProfileEmail,
                'subject' => $input['subject'],
                'body' => $input['body'],
                'cc' => !empty(env('MAIL_CC')) ? explode(',', env('MAIL_CC')) : null,
                'bcc' => !empty(env('MAIL_BCC')) ? explode(',', env('MAIL_BCC')) : null,
                'template' => PROFILE
            ]));

            if (auth()->user()->role == ADMIN) {
                // Send mail to admin
                Mail::to($adminEmail)->send(new Notification([
                    'profile_name_kanji' => $profileNameKanji,
                    'profile_email' => $profileEmail,
                    'admin_name_kanji' => $adminNameKanji,
                    'admin_email' => $adminEmail,
                    'subject' => 'GUILD CREATIONからお仕事の相談をお送りしました',
                    'title' => $input['subject'],
                    'body' => $input['body'],
                    'cc' => !empty(env('MAIL_ADMIN_CC')) ? explode(',', env('MAIL_ADMIN_CC')) : null,
                    'bcc' => !empty(env('MAIL_ADMIN_BCC')) ? explode(',', env('MAIL_ADMIN_BCC')) : null,
                    'template' => ADMIN
                ]));
            }

            return $this->respondWithData([
                'message' => __('api.mail.success'),
            ]);
        } catch (\Exception $e) {
            return $this->throwValidation(__('api.mail.error'));
        }
    }
}
