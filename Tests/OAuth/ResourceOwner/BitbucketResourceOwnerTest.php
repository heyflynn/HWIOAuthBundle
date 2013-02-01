<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\Tests\OAuth\ResourceOwner;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\BitbucketResourceOwner;

class BitbucketResourceOwnerTest extends GenericOAuth1ResourceOwnerTest
{
    protected $userResponse = <<<json
{
    "user": {
        "username": "1",
        "display_name": "bar"
    }
}
json;
    protected $paths = array(
        'identifier' => 'user.username',
        'nickname'   => 'user.username',
        'realname'   => 'user.display_name',
    );

    public function testGetUserInformation()
    {
        $this->mockBuzz($this->userResponse, 'application/json; charset=utf-8');

        $accessToken  = array('oauth_token' => 'token', 'oauth_token_secret' => 'secret');
        $userResponse = $this->resourceOwner->getUserInformation($accessToken);

        $this->assertEquals('1', $userResponse->getUsername());
        $this->assertEquals('1', $userResponse->getNickname());
        $this->assertEquals('bar', $userResponse->getRealName());
        $this->assertEquals($accessToken, $userResponse->getAccessToken());
    }

    protected function setUpResourceOwner($name, $httpUtils, array $options)
    {
        $options = array_merge(
            array(
                'authorization_url' => 'https://bitbucket.org/!api/1.0/oauth/request_token?format=yaml',
                'request_token_url' => 'https://bitbucket.org/!api/1.0/oauth/authenticate?format=yaml',
                'access_token_url'  => 'https://bitbucket.org/!api/1.0/oauth/access_token?format=yaml',
                'infos_url'         => 'https://api.bitbucket.org/1.0/user?format=yaml',
            ),
            $options
        );

        return new BitbucketResourceOwner($this->buzzClient, $httpUtils, $options, $name, $this->storage);
    }
}
