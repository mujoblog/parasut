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
class TrackableJobs extends Bundle
{

    /**
     * Retrieve a Trackable Jobs informations via its own id.
     *
     * @param  int   $id
     * @return array
     */
    public function show($id, $include = '')
    {
        return $this->client->call('GET', "trackable_jobs/{$id}", "trackable_jobs", [], [], $this->makeIncludes($include), $id);
    }

}
