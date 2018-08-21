<?php
namespace Livissnack\Weather;

use GuzzleHttp\Client;
use Livissnack\Weather\Exceptions\HttpException;
use Livissnack\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    //百度地图创建的应用 AK
    protected $ak;

    //可选的 sn 验证模式下需要的加密密钥
    protected $sn;

    protected $guzzleOptions = [];

    public function __construct($ak, $sn = null)
    {
        $this->ak = $ak;
        $this->sn = $sn;
    }
    
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions($options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @param $location     地理位置-支持经纬度和城市名两种
     * @param string $format    输出的数据格式
     * @param null $coordType   请求参数坐标类型-允许的值为 bd09ll、bd09mc、gcj02、wgs84
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getWeather($location, $format = 'json', $coordType = null)
    {
        $url = 'http://api.map.baidu.com/telematics/v3/weather';

        if (!\in_array($format, ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: '. $format);
        }

        $query = array_filter([
            'ak' => $this->ak,
            'sn' => $this->sn,
            'location' => $location,
            'output' => $format,
            'coord_type' => $coordType,
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return $format === 'json' ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}