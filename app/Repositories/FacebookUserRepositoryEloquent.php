<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
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

        $fbHelpers = new Common();

        $cookies = $data['cookies'];
        if (is_array($cookies)) {
            $cookies = $fbHelpers->getStrCookies($cookies);
        }

        $cookies = $fbHelpers->converCookiesStr2Arr($cookies);
        if (empty($cookies['c_user'])) {
            return false;
        }

        logger($cookies);
        $fbClient = new FacebookClient([
            'cookies' => $cookies,
            'userAgent' => $data['userAgent']
        ]);

        $htmlContent = $fbClient->getPageContent($cookies['c_user']);
        $dataUser = $this->crawlerInfoUser($htmlContent);
        $dataUser['fb_uid'] = $cookies['c_user'];
        $dataUser['cookies'] = $cookies;
        $this->updateOrCreate(['fb_uid' => $dataUser['fb_uid']], $dataUser);
        return $dataUser;
    }

    private function crawlerInfoUser($html) {
        $dom = str_get_html($html);
        $avatar = htmlspecialchars_decode($dom->find('.bq a img', 0)->src);
        $name =$dom->find('#m-timeline-cover-section strong', 0)->text();
        $dataInfo = [
            'avatar' => $avatar,
            'name'   => $name,
        ];
        return $dataInfo;
    }
}
