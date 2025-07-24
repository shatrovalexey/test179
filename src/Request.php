<?php
namespace Alexe\Test179;

/**
* HTTP-запрос
*/
class Request extends Base
{
    /**
    * HTTP-ответ
    *
    * @param string $url - URL
    *
    * @return ?string
    */
    protected function _getResponse(string $url): ?string
    {
        $ch = curl_init( "{$this->_config['origin']}{$url}");
        curl_setopt_array($ch, $this->_config['opts']);

        for ($i = 0; $i < $this->_config['tryes']; $i ++) {
            $resp = curl_exec($ch);

            if (!curl_error($ch)) break;

            $resp = null;
        }

        curl_close($ch);

        return $resp ?? file_get_contents('data' . $url . '.json');
    }

    /**
    * Раскодированный HTTP-ответ
    *
    * @param string $url - URL
    *
    * @return ?array
    */
    public function getData(string $url): ?array
    {
        foreach ((array)$this->_getResponse($url) as $resp)
            return Codec::getDecodeJson($resp);

        return null;
    }
}