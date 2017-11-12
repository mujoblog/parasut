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
 * Contact
 *
 * @package	  Parasut
 * @author	  Sercan Çakır <srcnckr@gmail.com>
 * @license	  MIT License
 * @copyright 2015
 */
class Contact extends Bundle
{
	/**
	 * Retrieve all contacts with pagination.
	 *
	 * @param  int	$page
	 * @param  int	$limit
	 * @return array
	 */	
	public function get($page = 1, $limit = 25, $sort = '', $filterName = '', $filterCode = '', $include = '')
    {
		$qsData = $this->makeListQueryStringArray($page, $limit, $sort, $filterName, $filterCode)
		return $this->client->call('GET', "contacts", "contacts", [], [], $qsData, $this->makeIncludes($include));
    }

	/**
	 * Create a new contact.
	 *
	 * @param  array  $attributes
	 * @param  array  $relationships
	 * @return array
	 */
	public function create($attributes, $relationships = [], $include = '')
	{
		return $this->client->call('POST', 'contacts', 'contacts', $attributes, $relationships, $this->makeIncludes($include));
	}

	/**
	 * Retrieve a contact informations via its own id.
	 *
	 * @param  int	 $id
	 * @param  bool	 $payments
	 * @param  bool	 $transactions
	 * @param  bool	 $stats
	 * @return array
	 */
	public function show($id, $include = '')
	{
		return $this->client->call('GET', "contacts/{$id}", 'contacts', [], [], $this->makeIncludes($include));
	}

	/**
	 * Update the contact with given arguments.
	 *
	 * @param  int	  $id
	 * @param  array  $attributes
	 * @param  array  $relationships
	 * @return array
	 */
	public function update($id, $attributes = [], $relationships = [], $include = '')
	{
		return $this->client->call('PUT', "contacts/{$id}", 'contacts', $attributes, $relationships, $this->makeIncludes($include), $id);
	}

	/**
	 * Delete an existing contact.
	 *
	 * @param  int	  $id
	 * @return array
	 */
	public function delete($id)
	{
		return $this->client->call('DELETE', "contacts/{$id}");
	}
}
