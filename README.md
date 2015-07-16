# yii2-odnoklassiniki-authclient

This extension adds Odnoklassniki OAuth2 supporting for [yii2-authclient](https://github.com/yiisoft/yii2-authclient).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kotchuprik/yii2-instagram-authclient "*"
```

or add

```json
"kotchuprik/yii2-odnoklassniki-authclient": "*"
```

to the `require` section of your composer.json.

## Usage

You must read the yii2-authclient [docs](https://github.com/yiisoft/yii2/blob/master/docs/guide/security-auth-clients.md)

Register your application [in Odnoklassniki](https://apiok.ru/wiki/pages/viewpage.action?pageId=42476486)

and add the Odnoklassniki client to your auth clients.

```php
'components' => [
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'instagram' => [
                'class' => 'kotchuprik\authclient\Odnoklassniki',
                    'applicationKey' => 'odnoklassniki_app_public_key',
                    'consumerKey' => 'odnoklassniki_app_id',
                    'consumerSecret' => 'odnoklassniki_client_secret',
                ],
            ],
            // other clients
        ],
    ],
    // ...
 ]
 ```
