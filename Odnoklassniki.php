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
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $params = [];
        $params['access_token'] = $this->accessToken->getToken();
        $params['application_key'] = $this->applicationKey;
        $params['sig'] = $this->sig($params, $params['access_token'], $this->clientSecret);
        return $this->api('api/users/getCurrentUser', 'GET', $params);
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['access_token'] = $accessToken->getToken();
        $data['application_key'] = $this->applicationKey;
        $data['method'] = str_replace('/', '.', str_replace('api/', '', $request->url));
        $data['sig'] = $this->sig($data, $data['access_token'], $this->clientSecret);
        $request->setData($data);
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
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'uid'
        ];
    }
}
