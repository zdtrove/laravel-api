<?php


namespace App\Helpers;

use GuzzleHttp\Client;

class Common
{
    /**
     * Get azure account data from azure token
     *
     * @param $azure_token
     * @return mixed
     */
    static function getAzureAccountData($azure_token, $register_type = MICROSOFT)
    {
        try {
            $guzzle = new Client();
            $endpoint = GRAPH_URL_MICROSOFT;
            $params = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $azure_token,
                ]
            ];
            switch ($register_type) {
                case FACEBOOK:
                    $endpoint = GRAPH_URL_FACEBOOK;
                    $params = [
                        'query' => [
                            'access_token' => $azure_token,
                            'fields' => 'id,name,email'
                        ]
                    ];
                    break;
                case GOOGLE:
                    $endpoint = GRAPH_URL_GOOGLE;
                    break;
                case TWITTER:
                    $endpoint = GRAPH_URL_TWITTER;
                    break;
            }
            
            $response = $guzzle->get($endpoint, $params)->getBody();
            
            
            $userData = json_decode($response, true);
           
            if (in_array($register_type, [GOOGLE])) {
                $userData['id'] = $userData['sub'];
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
            $userData = null;
        }

        return $userData;
    }

    /**
     * Based on email of azure account to get domain
     *
     * @param $userData
     * @return string
     */
    static function getUserDomainFromAzureUserData($userData)
    {
        $userEmail = !empty($userData['email']) ? $userData['email'] : $userData['userPrincipalName'];

        return substr($userEmail, strpos($userEmail, '@') + 1);
    }

    static function getUserEmail($userData)
    {
        return (!empty($userData['email']) ? $userData['email'] : $userData['userPrincipalName']);
    }
}
