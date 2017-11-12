<?php

/*
 * This file is part of Parasut.
 *
 * (c) Sercan Çakır <srcnckr@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Parasut\Bundle;

use Parasut\Bundle;

/**
 * Sale
 *
 * @package   Parasut
 * @author    Sercan Çakır <srcnckr@gmail.com>
 * @license   MIT License
 * @copyright 2015
 */
class Sale extends Bundle
{
    /**
     * Retrieve all sales invoices with pagination.
     *
     * @param  int  $page
     * @param  int  $limit
     * @return array
     */	
	public function get($page = 1, $limit = 25, $sort = '', $filterName = '', $filterCode = '', $include = '')
    {
		$qsData = $this->makeListQueryStringArray($page, $limit, $sort, $filterName, $filterCode);
		return $this->client->call('GET', "sales_invoices", "sales_invoices", [], [], $qsData, $this->makeIncludes($include));
    }

    /**
     * Create a new sales invoice.
     *
     * @param  array  $params
     * @return array
     */
	public function create($attributes, $relationships = [], $include = '')
	{
		return $this->client->call('POST', 'sales_invoices', 'sales_invoices', $attributes, $relationships, $this->makeIncludes($include));
	}

    /**
     * Retrieve a sales invoice informations via its own id.
     *
     * @param  int   $id
     * @return array
     */
    public function show($id, $include = '')
    {
        return $this->client->call('GET', "sales_invoices/{$id}", "sales_invoices", [], [], $this->makeIncludes($include), $id);
    }

    /**
     * Update the sales invoice with given arguments.
     *
     * @param  int    $id
     * @param  array  $params
     * @return array
     */
    public function update($id, $attributes, $relationships = [], $include = '')
    {
        return $this->client->call('PUT', "sales_invoices/{$id}", "sales_invoices", $attributes, $relationships, $this->makeIncludes($include), $id );
    }

    /**
     * Marked paid the sales invoice with given arguments.
     *
     * @param  int    $id
     * @param  array  $params
     * @return array
     */
    public function pay($id, $attributes, $include = '')
    {
        return $this->client->call('POST', "sales_invoices/{$id}/payments", "payments", $attributes, [], $this->makeIncludes($include));
    }
	
	/**
     * Marked paid the sales invoice with given arguments.
     *
     * @param  int    $id
     * @param  array  $params
     * @return array
     */
    public function cancel($invoiceId, $attributes, $include = '')
    {
        return $this->client->call('DELETE', "sales_invoices/{$invoiceId}/cancel", '', [], [], $this->makeIncludes($include));
    }

    /**
     * Convert estimate to invoice.
     *
     * @param  int  $id
     * @return array
     */
    public function convertInvoice($invoiceId, $attributes = [], $relationships = [], $include = '')
    {
        return $this->client->call('PATCH', "sales_invoices/{$invoiceId}/convert_to_invoice", 'sales_invoices', $attributes, $relationships, $this->makeIncludes($include), $invoiceId);
    }

    /**
     * Delete an existing sales invoice.
     *
     * @param  int    $id
     * @return array
     */
    public function delete($id)
    {
        return $this->client->call('DELETE', "sales_invoices/{$id}");
    }

     /**
     * Convert estimate to invoice.
     *
     * @param  int  $id
     * @return array
     */
    public function recover($invoiceId, $attributes = [], $relationships = [], $include = '')
    {
        return $this->client->call('PATCH', "sales_invoices/{$invoiceId}/recover", 'sales_invoices', $attributes, $relationships, $this->makeIncludes($include), $invoiceId);
    }
	
	/**
     * Convert estimate to invoice.
     *
     * @param  int  $id
     * @return array
     */
    public function archive($invoiceId, $attributes = [], $relationships = [], $include = '')
    {
        return $this->client->call('PATCH', "sales_invoices/{$invoiceId}/archive", 'sales_invoices', $attributes, $relationships, $this->makeIncludes($include), $invoiceId);
    }
	
	/**
     * Convert estimate to invoice.
     *
     * @param  int  $id
     * @return array
     */
    public function unarchive($invoiceId, $attributes = [], $relationships = [], $include = '')
    {
        return $this->client->call('PATCH', "sales_invoices/{$invoiceId}/unarchive", 'sales_invoices', $attributes, $relationships, $this->makeIncludes($include), $invoiceId);
    }
}
