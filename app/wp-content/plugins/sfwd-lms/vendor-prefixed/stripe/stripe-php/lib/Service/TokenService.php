<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Service;

class TokenService extends \StellarWP\Learndash\Stripe\Service\AbstractService
{
    /**
     * Creates a single-use token that represents a bank account’s details. You can use
     * this token with any API method in place of a bank account dictionary. You can
     * only use this token once. To do so, attach it to a <a href="#accounts">Custom
     * account</a>.
     *
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Token
     *
     * @license MIT
     * Modified by learndash on 14-February-2024 using Strauss.
     * @see https://github.com/BrianHenryIE/strauss
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/tokens', $params, $opts);
    }

    /**
     * Retrieves the token with the given ID.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Token
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/tokens/%s', $id), $params, $opts);
    }
}