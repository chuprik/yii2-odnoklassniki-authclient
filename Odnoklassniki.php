<?php

namespace kotchuprik\authclient;

use yii\authclient\OAuth2;

class Odnoklassniki extends OAuth2
{
    /**
     * @var string
     */
    public $applicationKey;

    /**
     * @inheritdoc
     */
    public $authUrl = 'http://www.odnoklassniki.ru/oauth/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.odnoklassniki.ru/oauth/token.do';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'http://api.odnoklassniki.ru';

    /**
     * @inheritdoc
     */
    public $scope = 'VALUABLE_ACCESS';

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('api/users/getCurrentUser', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['access_token'] = $accessToken->getToken();
        $params['application_key'] = $this->application_key;
        $params['method'] = str_replace('/', '.', str_replace('api/', '', $url));

        $first = 'application_key=' . $this->application_key . 'method=' . $params['method'];
        $second = md5($params['access_token'] . $this->clientSecret);

        $params['sig'] = md5($first . $second);

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'odnoklassniki';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Odnoklassniki';
    }
}
