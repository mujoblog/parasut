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

use Parasut\Exception\InternalServerErrorException;
use Parasut\Exception\NotFoundException;
use Parasut\Exception\UnauthorizedException;
use Parasut\Exception\UnprocessableEntityException;

/**
 * Client
 *
 * @package      Parasut
 * @author      Sercan Çakır <srcnckr@gmail.com>
 * @license      MIT License
 * @copyright 2015
 */
class Client
{
    /**
     * The base api url.
     *
     * @return string
     */
    const API_URL = 'https://api.parasut.com/v4';

    /**
     * The oAuth token url.
     *
     * @var string
     */
    const API_TOKEN_URL = 'https://api.parasut.com/oauth/token';

    /**
     * All of the configuration items.
     *
     * @var array
     */
    protected $config;

    /**
     * The value of access token.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The registered type aliases.
     *
     * @var array
     */
    protected $aliases = [
        'account' => Bundle\Account::class,
        'category' => Bundle\Category::class,
        'contact' => Bundle\Contact::class,
        'expense' => Bundle\Expense::class,
        'product' => Bundle\Product::class,
        'purchase' => Bundle\Purchase::class,
        'sale' => Bundle\Sale::class,
        'e-archive' => Bundle\EArchive::class,
        'e-invoice' => Bundle\EInvoice::class,
        'trackable-job' => Bundle\TrackableJobs::class

    ];

    /**
     * An array of the types that have been resolved.
     *
     * @var array
     */
    protected $resolved = [];

    /**
     * Constructor.
     *
     * @param  array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Generate a new endpoint url.
     *
     * @param  string $prefix
     * @param  bool $includeId
     * @return string
     */
    public function endpointUrl($prefix = null, $includeId = true)
    {
        $url = self::API_URL;

        // push company id
        if ($includeId) {
            $url = $url . '/' . $this->getCompanyId();
        }

        // push prefix
        if ($prefix = trim($prefix, '/')) {
            $url = $url . '/' . $prefix;
        }

        return $url;
    }

    /**
     * Send a new authorization request.
     *
     * @return $this
     */
    public function authorize()
    {
        $url = self::API_TOKEN_URL . $this->BuildQueryStringText(array_only($this->config, ['client_id', 'redirect_uri', 'username', 'password', 'grant_type']));

        $response = $this->send('POST', $url, false);

        if ($token = array_get($response, 'access_token')) {
            $this->setAccessToken($token);
        }

        return $this;
    }

    /**
     * Set the access token.
     *
     * @param  string $token
     * @return $this
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the company id.
     *
     * @param  int $companyId
     * @return $this
     */
    public function setCompanyId($companyId)
    {
        $this->config['company_id'] = $companyId;

        return $this;
    }

    /**
     * Get the company id.
     *
     * @return int
     */
    public function getCompanyId()
    {
        return array_get($this->config, 'company_id');
    }

    /**
     * Retrieve the resolved instance or build an instance of the type.
     *
     * @param  string $type
     * @return mixed
     */
    public function make($type)
    {
        if ($instance = array_get($this->resolved, $type)) {
            return $instance;
        }

        return $this->build($type);
    }

    /**
     * Build an instance of the type.
     *
     * @param  string $type
     * @return mixed
     */
    protected function build($type)
    {
        if ($concrete = array_get($this->aliases, $type)) {
            return $this->resolved[$type] = new $concrete($this);
        }

        throw new InternalServerErrorException;
    }

    /**
     * Retrieve an account information.
     *
     * @return array
     */
    public function me()
    {
        return $this->send('GET', $this->endpointUrl('me', false), true);
    }

    /**
     * Create a new request with autorization.
     *
     * @param  string $function
     * @param  array $params
     * @param  string $method
     * @return mixed
     */
    public function call($method, $function, $type = '', $attributes = [], $relationships = [], $querystring = [], $id = '')
    {
        $url = $this->endpointUrl($function);
        $url .= $this->buildQueryStringText($querystring);

        $dataArr = $this->buildDataArray($id, $type, $attributes, $relationships);

        return $this->send($method, $url, true, $dataArr);
    }

    /**
     * Builds data array.
     *
     * @param  string $id
     * @param  string $type
     * @param  array $attributes
     * @param  array $relationships
     * @return array
     */
    public function buildDataArray($id = '', $type = '', $attributes = [], $relationships = [])
    {
        $dataArr = [];

        if ($id !== '')
            $dataArr["id"] = $id;

        if ($type !== '')
            $dataArr["type"] = $type;

        if (is_array($attributes) && count($attributes) > 0)
            $dataArr["attributes"] = $attributes;

        if (is_array($relationships) && count($relationships) > 0)
            $dataArr["relationships"] = $relationships;

        return $dataArr;
    }

    /**
     * Builds querystring.
     *
     * @param  array $querystringArray
     * @return string
     */
    public function buildQueryStringText($querystringArray = [])
    {
        if (is_array($querystringArray) && count($querystringArray) > 0)
            return '?' . http_build_query($querystringArray);

        return '';
    }

    /**
     * Create a new request.
     *
     * @param  string $method
     * @param  string $url
     * @param  bool $auth
     * @param  array $dataArray
     * @return mixed
     */
    public function send($method, $url, $auth = false, $dataArray = [])
    {
        $headers = [
            "cache-control: no-cache",
            "content-type: application/json",
            "Accept: application/json"
        ];

        if ($auth)
            $headers[] = 'Authorization: Bearer ' . $this->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (in_array($method, ["PUT", "POST", "PATCH"]))
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["data" => $dataArray]));

        switch ($method) {
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $jsonData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = json_decode($jsonData, true);
        curl_close($ch);

        switch ($httpCode) {
            case '401':
                throw new UnauthorizedException;
                break;
            case '404':
                throw new NotFoundException;
                break;
            case '422':
                throw new UnprocessableEntityException($jsonData);
                break;
            case '500':
                throw new InternalServerErrorException;
                break;
            default:
                return $response;
                break;
        }
    }
}
