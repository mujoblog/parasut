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
class EArchive extends Bundle
{
    /**
     * Create a new E-Archives .
     *
     * @param  array  $params
     * @return array
     */
	public function create($attributes, $relationships = [], $include = '')
	{
		return $this->client->call('POST', 'e_archives', 'e_archives', $attributes, $relationships, $this->makeIncludes($include));
	}

    /**
     * Retrieve a E-Archives informations via its own id.
     *
     * @param  int   $id
     * @return array
     */
    public function show($id, $include = '')
    {
        return $this->client->call('GET', "e_archives/{$id}/pdf", "e_archives", [], [], $this->makeIncludes($include), $id);
    }

}
