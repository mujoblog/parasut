<?php

/*
 * This file is part of Parasut.
 *
 * (c) Sercan Çakır <srcnckr@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Parasut;

/**
 * Bundle
 *
 * @package   Parasut
 * @author    Sercan Çakır <srcnckr@gmail.com>
 * @license   MIT License
 * @copyright 2015
 */
abstract class Bundle
{
    /**
     * The client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param  Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
	
	public function makeIncludes($include = '')
	{
		$includes = [];
		
		if( is_array( $include ) && count($include) > 0 )
			$includes = [ 'include'	=> implode(',', $include) ];
		else if( is_string($include) && strlen($include) > 0 )
			$includes = [ 'include' => $include ];
		
		return $includes;
	}
	
	public function makeListQueryStringArray( $page = 1, $limit = 25, $sort = '', $filterName = '', $filterCode = '' )
	{
		$qsData = [
			'page'	=> [
				'number' => $page,
				'size'	=> $limit
			]
		];
		
		if( strlen($sort) > 0 )
			$qsData["sort"] = $sort;
		
		if( strlen($filterName) > 0 || strlen($filterCode) > 0 ) {
			$qsData["filter"] = [];
			
			if( strlen($filterName) > 0 )
				$qsData["filter"]["name"] = $filterName;
			
			if( strlen($filterCode) > 0 )
				$qsData["filter"]["code"] = $filterCode;
		}
		
		return $qsData;
	}
}
