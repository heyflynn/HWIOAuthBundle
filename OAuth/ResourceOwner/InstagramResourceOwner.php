<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;

/**
 * InstagramResourceOwner
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class InstagramResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * @var array
     */
    private $userData;

    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url'   => 'https://api.instagram.com/oauth/authorize/',
        'access_token_url'    => 'https://api.instagram.com/oauth/access_token',
        // This option is never used as Instagram returns user data with access token
        'infos_url'           => '',
        'scope'               => null,
        'user_response_class' => '\HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse',
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier' => 'id',
        'nickname'   => 'username',
        'realname'   => 'full_name',
    );

    /**
     * {@inheritDoc}
     */
    public function getUserInformation($accessToken)
    {
        $response = $this->getUserResponse();
        $response->setResponse($this->userData);
        $response->setResourceOwner($this);
        $response->setAccessToken($accessToken);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = array())
    {
        $parameters = array_merge($extraParameters, array(
             'code'          => $request->query->get('code'),
             'grant_type'    => 'authorization_code',
             'client_id'     => $this->getOption('client_id'),
             'client_secret' => $this->getOption('client_secret'),
             'redirect_uri'  => $redirectUri,
        ));

        $response = $this->doGetAccessTokenRequest($this->getOption('access_token_url'), $parameters);
        $response = $this->getResponseContent($response);

        if (isset($response['error'])) {
            throw new AuthenticationException(sprintf('OAuth error: "%s"', $response['error']));
        }

        if (!isset($response['access_token'])) {
            throw new AuthenticationException('Not a valid access token.');
        }

        $this->userData = $response['user'];

        return $response['access_token'];
    }
}
