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
 * Product
 *
 * @package   Parasut
 * @author    Sercan Çakır <srcnckr@gmail.com>
 * @license   MIT License
 * @copyright 2015
 */
class Product extends Bundle
{
    /**
     * Retrieve all products with pagination.
     *
     * @param  int  $page
     * @param  int  $limit
     * @return array
     */
    public function get($page = 1, $limit = 25, $sort = '', $filters = [], $include = '')
    {
		$qsData = $this->makeListQueryStringArray($page, $limit, $sort, $filters);
		return $this->client->call('GET', "products", "products", [], [], $qsData, $this->makeIncludes($include));
    }

    /**
     * Create a new product.
     *
     * @param  array  $params
     * @return array
     */	
	public function create($attributes, $relationships = [], $include = '')
	{
		return $this->client->call('POST', 'products', 'products', $attributes, $relationships, $this->makeIncludes($include));
	}

    /**
     * Retrieve a product informations via its own id.
     *
     * @param  int   $id
     * @return array
     */
	public function show($id, $include = '')
    {
        return $this->client->call('GET', "products/{$id}", "products", [], [], $this->makeIncludes($include), $id);
    }

    /**
     * Update the product with given arguments.
     *
     * @param  int    $id
     * @param  array  $params
     * @return array
     */
	public function update($id, $attributes, $relationships = [], $include = '')
    {
        return $this->client->call('PUT', "products/{$id}", "products", $attributes, $relationships, $this->makeIncludes($include), $id );
    }

    /**
     * Delete an existing product.
     *
     * @param  int    $id
     * @return array
     */
	public function delete($id)
    {
        return $this->client->call('DELETE', "products/{$id}");
    }
}
