<?php

namespace App\Utils;

class LocationUtils
{
    private $apiKey;
    private $apiUrl = 'http://api.zip-codes.com/ZipCodesAPI.svc/1.0/FindZipCodesInRadius';

    public function __construct()
    {
        $this->apiKey = config('services.zipcode.key');
    }

    /**
     * @param $anchorZipcode
     * @param $radius
     * @return array|mixed
     */
    public function getIndexesByRadius($anchorZipcode, $radius)
    {
        if (!$anchorZipcode || !$radius)
            return [];

        $params = [
            'key'           => $this->apiKey,
            'zipcode'       => $anchorZipcode,
            'maximumradius' => $radius
        ];

        $url = "$this->apiUrl?" .  http_build_query($params);
        $response= preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents($url));
        $data = json_decode($response, true);
        return array_get($data, 'DataList', []);
    }

}

