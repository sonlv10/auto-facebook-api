<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Container\Container as Application;
use App\Repositories\FacebookUserRepository;
use App\Entities\FacebookUser;
use App\Validators\FacebookUserValidator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Helpers\Common;
use App\Helpers\FacebookClient;
use simple_html_dom;


/**
 * Class FacebookUserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FacebookUserRepositoryEloquent extends BaseRepository implements FacebookUserRepository
{
    private $FbHelper;

    public function __construct(Application $app, Common $FbHelper)
    {
        $this->FbHelper = $FbHelper;
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FacebookUser::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FacebookUserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function loginGetCookie($data)
    {
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $headers = ['User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36'];
        $client = new Client(['cookies' => $jar]);
        try {
            $apiUrl = 'https://m.facebook.com/login.php';

            $response = $client->request('POST', $apiUrl, [
                'headers' => $headers,
                'form_params' => [
                    'email' => $data['email'],
                    'pass' => $data['pass'],
                ],
                'cookies' => $jar
            ]);
            $cookies = $jar->toArray();
            $this->updateOrCreate(['fb_email' => $data['email']], [
                'cookies' => $cookies
            ]);
            return $response;
        } catch (RequestException $e) {
            return
                array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
        }
    }

    public function fetchUserByCookie($data) {

        $cookies = $data['cookies'];
        if (is_array($cookies)) {
            $cookies = $this->FbHelper ->getStrCookies($cookies);
        }

        $cookies = $this->FbHelper ->converCookiesStr2Arr($cookies);
        if (empty($cookies['c_user'])) {
            return false;
        }

        $fbClient = new FacebookClient([
            'cookies' => $cookies,
            'headers' => [
                'userAgent' => $data['userAgent'],
                'Sec-Fetch-User' => '?1',
                "Content-type" => "application/x-www-form-urlencoded"
            ]
        ]);

        $response = $fbClient->callAPI('GET', config('facebook.mbasic_domain'). $cookies['c_user']);
        if (!$response['success']) {
            return false;
        }

        $htmlContent = $response['data'];
        $dataUser = $this->crawlerInfoUser($htmlContent);
        $dataUser['fb_uid'] = $cookies['c_user'];
        $dataUser['cookies'] = $cookies;

        $dataUser['access_token'] = $this->getToken($fbClient, $dataUser['params']['fb_dtsg']);

        $this->updateOrCreate(['fb_uid' => $dataUser['fb_uid']], $dataUser);
        return $dataUser;
    }

    private function getToken($client, $dtsg)
    {
        $url = config('facebook.domain') . 'v1.0/dialog/oauth/confirm';

        $params = [
            'body' => 'fb_dtsg=' . $dtsg . '&app_id=124024574287414&redirect_uri=fbconnect%3A%2F%2Fsuccess&display=page&access_token=&from_post=1&return_format=access_token&domain=&sso_device=ios&_CONFIRM=1&_user=100072773571604'
        ];

        $response = $client->callAPI('POST', $url, $params);
        if (!$response['success']) {
            return false;
        }
        $htmlToken = $response['data'];
        $token = $this->FbHelper ->get_string_between($htmlToken, 'access_token=', '&');

        return $token;
    }

    public function getUserFriends($data)
    {
        $user = $this->findWhere(['fb_uid' => $data['fb_uid']])->first();
        if (empty($user)) {
            return false;
        }
        $fbClient = new FacebookClient([
            'cookies' => $user->cookies,
            'headers' => [
                'userAgent' => $data['userAgent'],
                'Sec-Fetch-User' => '?1'
            ]
        ]);
        $path = 'friends/center/friends/';
        if (!empty($data['next_path'])) {
            $path = $data['next_path'];
        }
        $response = $fbClient->callAPI('GET', config('facebook.mbasic_domain') . $path);
        if (!$response['success']) {
            return false;
        }
        $htmlContent = $response['data'];
        $listFriends = $this->crawlerUserFriends($htmlContent);
        return $listFriends;
    }

    private function crawlerInfoUser($html) {
        $dom = str_get_html($html);
        $avatar = htmlspecialchars_decode($dom->find('.bq a img', 0)->src);
        $name =$dom->find('#m-timeline-cover-section strong', 0)->text();

        $params = [
            'fb_dtsg' => $dom->find('input[name=fb_dtsg]', 0)->value,
            'jazoest' => $dom->find('input[name=jazoest]', 0)->value,
            'privacyx' => $dom->find('input[name=privacyx]', 0)->value,
            'target' => $dom->find('input[name=target]', 0)->value,
            'c_src' => $dom->find('input[name=c_src]', 0)->value,
            'cwevent' => $dom->find('input[name=cwevent]', 0)->value,
            'referrer' => $dom->find('input[name=referrer]', 0)->value,
            'ctype' => $dom->find('input[name=ctype]', 0)->value,
            'cver' => $dom->find('input[name=cver]', 0)->value,

        ];
        $dataInfo = [
            'avatar' => $avatar,
            'name'   => $name,
            'params' => $params
        ];
        return $dataInfo;
    }

    private function crawlerUserFriends($html) {
        $listFriends = array();
        $dom = str_get_html($html);
        $list = $dom->find('#friends_center_main table');
        foreach($list as $friend) {
            $name = $friend->find('a', 0)->text();
            $avatar = $friend->find('img', 0)->src ?? '';
            $listFriends[] = ['name' => $name, 'avatar' => htmlspecialchars_decode($avatar)];
        }
        $next = $dom->find('#friends_center_main > div >a', 0)->href ?? '';
        $result = [
            'listFriends' => $listFriends,
            'next_path' => $next
        ];
        return $result;
    }

    public function post($data)
    {
        $user = $this->findWhere(['fb_uid' => $data['fb_uid']])->first();
        if (empty($user)) {
            return false;
        }
        $fbClient = new FacebookClient([
            'cookies' => $user->cookies,
            'headers' => [
                'userAgent' => $data['userAgent'],
                'Sec-Fetch-User' => '?1'
            ]
        ]);
        $path = 'composer/mbasic/';
        $dataForm = $user->params;
        $dataForm['xc_message'] = $data['message'];
        $dataForm['view_post'] = 'Post';

        $dataSend = [
            'form_params' => $dataForm
        ];
        $response = $fbClient->callAPI('POST', config('facebook.mbasic_domain') . $path, $dataSend);
        if (empty($response['success'])) {
            return false;
        }
        return true;
    }

    public function checkTokenValid($token)
    {
        $result = ['success' => false, 'data' => []];
        $fbClient = new FacebookClient();
        $endpoint = "https://graph.facebook.com/me?access_token=" . $token;
        $response = $fbClient->callGraphApi('GET', $endpoint);
        if ($response['success']) {
            return ['success' => true, 'data' => $response['data']];
        }
        return $result;
    }
}
