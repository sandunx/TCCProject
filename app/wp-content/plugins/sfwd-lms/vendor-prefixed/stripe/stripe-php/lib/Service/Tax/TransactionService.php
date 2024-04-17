<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Service\Tax;

class TransactionService extends \StellarWP\Learndash\Stripe\Service\AbstractService
{
    /**
     * Retrieves the line items of a committed standalone transaction as a collection.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Collection<\Stripe\Tax\TransactionLineItem>
     *
     * @license MIT
     * Modified by learndash on 14-February-2024 using Strauss.
     * @see https://github.com/BrianHenryIE/strauss
     */
    public function allLineItems($id, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/tax/transactions/%s/line_items', $id), $params, $opts);
    }

    /**
     * Creates a Tax <code>Transaction</code> from a calculation.
     *
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Tax\Transaction
     */
    public function createFromCalculation($params = null, $opts = null)
    {
        return $this->request('post', '/v1/tax/transactions/create_from_calculation', $params, $opts);
    }

    /**
     * Partially or fully reverses a previously created <code>Transaction</code>.
     *
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Tax\Transaction
     */
    public function createReversal($params = null, $opts = null)
    {
        return $this->request('post', '/v1/tax/transactions/create_reversal', $params, $opts);
    }

    /**
     * Retrieves a Tax <code>Transaction</code> object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Tax\Transaction
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/tax/transactions/%s', $id), $params, $opts);
    }
}
