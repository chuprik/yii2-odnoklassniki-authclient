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
    public $authUrl = 'https://connect.ok.ru/oauth/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.ok.ru/oauth/token.do';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.ok.ru';

    /**
     * @inheritdoc
     */
    public $scope = 'VALUABLE_ACCESS';

    /**
     * @var string Fields to fetch (https://apiok.ru/en/dev/methods/rest/users/users.getCurrentUser)
     */
    public $fields = '';

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $params = [];
        if (!empty($this->fields)) {
            $params['fields']  = $this->fields;
        }
        $params['access_token'] = $this->accessToken->getToken();
        $params['application_key'] = $this->applicationKey;
        $params['sig'] = $this->sig($params, $params['access_token'], $this->clientSecret);
        return $this->api('api/users/getCurrentUser', 'GET', $params);
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['access_token'] = $accessToken->getToken();
        $params['application_key'] = $this->applicationKey;
        $params['method'] = str_replace('/', '.', str_replace('api/', '', $url));
        $params['sig'] = $this->sig($params, $params['access_token'], $this->clientSecret);

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /**
     * Generates a signature
     * @param $vars array
     * @param $accessToken string
     * @param $secret string
     * @return string
     */
    protected function sig($vars, $accessToken, $secret)
    {
        ksort($vars);
        $params = '';
        foreach ($vars as $key => $value) {
            if (in_array($key, ['sig', 'access_token'])) {
                continue;
            }
            $params .= "$key=$value";
        }
        return md5($params . md5($accessToken . $secret));
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

    /**
     * @inheritdoc
     */
    protected function defaultReturnUrl()
    {
        $params = $_GET;
        unset($params['code']);
        unset($params['state']);
        unset($params['permissions_granted']);
        $params[0] = \Yii::$app->controller->getRoute();

        return \Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }
}
