<?php
namespace Worldpay;

use yii\helpers\VarDumper;

class Connection {

    private $service_key = "";
    private $timeout = 65;
    private $ssl_check = true;
    private $endpoint = 'https://api.worldpay.com/v1/';
    private $client_user_agent = "";

    private function __construct()
    {

    }

     /**
     * Call this method to get singleton
     *
     * @return Connection
     */
    public static function getInstance()
    {
        if (!function_exists("curl_init")) {
            Error::throwError("cine");
        }

        static $inst = null;
        if ($inst === null) {
            $inst = new Connection();
            $inst->client_user_agent = $inst->getBaseClientUserAgent();
        }
        return $inst;
    }

    public function setServiceKey($serviceKey)
    {
        $this->service_key = $serviceKey;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function setSSLCheck($ssl)
    {
        $this->ssl_check = $ssl;
    }

    private function getBaseClientUserAgent()
    {
        $arch = (bool)((1<<32)-1) ? 'x64' : 'x86';
        $clientUA = 'os.name=' . php_uname('s') . ';os.version=' . php_uname('r') . ';os.arch=' .
        $arch . ';lang.version='. phpversion() . ';lib.version=2.1.0;' . 'api.version=v1;lang=php;owner=worldpay';
        return $clientUA;
    }

    public function setClientUserAgentWithPluginData($pluginName, $pluginVersion)
    {
        $this->client_user_agent = $this->getBaseClientUserAgent();
        if ($pluginName) {
             $this->client_user_agent .= ';plugin.name=' . $pluginName;
        }
        if ($pluginVersion) {
             $this->client_user_agent .= ';plugin.version=' . $pluginVersion;
        }
    }

     /**
     * Sends request to Worldpay API
     * @param string $action
     * @param string $json
     * @param bool $expectResponse
     * @param string $method
     * @return string JSON string from Worldpay
     * */
    public function sendRequest($action, $json = false, $expectResponse = false, $method = 'POST', $debug=false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint.$action);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: $this->service_key",
                "Content-Type: application/json",
                "X-wp-client-user-agent: $this->client_user_agent",
                "Content-Length: " . strlen($json)
            )
        );
        // Disabling SSL used for localhost testing
        if ($this->ssl_check === false) {
            if (substr($this->service_key, 0, 1) != 'T') {
                Error::throwError('ssl');
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);;
        $err = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if($debug){
            $file = (\Yii::$app->basePath . '/archivos/');
            $random = rand(0, 100);
            file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
            file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($result, true));
        }

        // Curl error
        if ($result === false) {
            $file = (\Yii::$app->basePath . '/archivos/');
            $random = rand(0, 100);
            file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
            file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($err, true));
            if ($errno === 60) {
                Error::throwError('sslerror', false, $errno, null, $err);
            } elseif ($errno === 28) {
                Error::throwError('timeouterror', false, $errno, null, $err);
            } else {
                Error::throwError('uanv', false, $errno, null, $err);
            }
        }

        if (substr($result, -1) != '}') {
            $result = substr($result, 0, -1);
        }

        // Decode JSON
        $response = self::handleResponse($result);

        // Check JSON has decoded correctly
        if ($expectResponse && ($response === null || $response === false )) {
            $file = (\Yii::$app->basePath . '/archivos/');
            $random = rand(0, 100);
            file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
            file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($err, true));
            Error::throwError('uanv', Error::$errors['json'], 503);
        }

        // Check the status code exists
        if (isset($response["httpStatusCode"])) {

            if ($response["httpStatusCode"] != 200) {
                $file = (\Yii::$app->basePath . '/archivos/');
                $random = rand(0, 100);
                file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
                file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($response, true));
                Error::throwError(
                    false,
                    $response["message"],
                    $info['http_code'],
                    $response['httpStatusCode'],
                    $response['description'],
                    $response['customCode']
                );

            }

        } elseif ($expectResponse && $info['http_code'] != 200) {
            $file = (\Yii::$app->basePath . '/archivos/');
            $random = rand(0, 100);
            file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
            file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($info, true));
            // If we expect a result and we have an error
            Error::throwError('uanv', Error::$errors['json'], 503);

        } elseif (!$expectResponse) {

            if ($info['http_code'] != 200) {
                $file = (\Yii::$app->basePath . '/archivos/');
                $random = rand(0, 100);
                file_put_contents($file . "Request-$random-'worldpay.json", print_r($json, true));
                file_put_contents($file . "ResponseH-$random-worlpay.json", print_r($result, true));
                Error::throwError('apierror', $result, $info['http_code']);
            } else {
                $response = true;
            }
        }

        return $response;
    }

    /**
     * Handle response object
     * @param string $response
     * */
    private static function handleResponse($response)
    {
        return json_decode($response, true);
    }
}
