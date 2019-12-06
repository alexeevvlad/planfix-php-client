<?php
/**
 * A simple library made for easy access to account data of Planfix users.
 * Best suites for intergrating in CRM or administrator software for the sake of automation.
 *
 * You can use it absolutely free in commercial or non-commercial applications.
 *
 * Software provided AS IS without any warranty.
 *
 * @author Coding Hamster <admin@codinghamster.info>
 * @version 1.0.1
 */

/**
 * Main class with all the magic.
 */
class Planfix_API {

    /**
     * Version of the library
     */
    const VERSION = '1.0.1';

    /**
     * Maximum size of a page for *.getList requests
     */
    const MAX_PAGE_SIZE = 100;

    /**
     * Default Curl options
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT    => 10,
        CURLOPT_RETURNTRANSFER    => 1,
        CURLOPT_TIMEOUT           => 60,
        CURLOPT_SSL_VERIFYPEER    => 0,
        CURLOPT_SSL_VERIFYHOST    => 0,
        CURLOPT_FOLLOWLOCATION    => 1,
        CURLOPT_POSTREDIR         => 1
    );

    /**
     * Default API error codes
     */
    public static $ERORR_CODES = array(
      '0001' => '�������� API Key',
      '0002 - ���������� �������������',
      '0003' => '������ XML �������. ������������ XML',
      '0004' => '����������� �������',
      '0005' => '���� ������ �������������� (����� ����� ������ �������)',
      '0006' => '�������� �������',
      '0007' => '�������� ����� ������������� �������� (��������� ������������ ���������� �������� � �����)',
      '0008' => '����������� ��� �������',
      '0009' => '����������� ���� �� ������������ ���������� �������',
      '0010' => '������� ���������',
      '0011' => '�� �������� �������� ������������ ���������� ������������ �����������',
      '0012' => '����������� ������, �� ������� �������� ������ � ������',
      '0013' => '�������������� ������������',
      '0014' => '������������ ���������',
      '0015' => '������������ �������� ���������',
      '0016' => '� ������ ��������� �������� �� ����� ��������� ���������� ��������',
      '0017' => '����������� �������� ��� ���������� ���������',
      '0018' => '�������/���������� �� �����������',
      '0019' => '������ ������������� ����� ����� ���������',
      '0020' => '����� ������� ��������',
      '0021' => '����������� ���������� �������� ������ ����������� ������������ ��� ������ �������',
      '0022' => '������������� API ���������� ��� ����������� ��������',
      '0023' => '����������� �������� ���������� � ������ �������� ��������� ����� ��������',
      '0024' => '������� ���������� � ������ ����������, ��������� url �������',
      //��������������
      '1001' => '�������� ����� ��� ������',
      '1002' => '�� ���������� ������� ������� ����������� ����� (����������)',
      //������
      '2001' => '����������� ������ �� ����������',
      '2002' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '2003' => '������ ���������� �������',
      //������
      '3001' => '��������� ������ (��� ������ ������������� ������) �� ����������',
      '3002' => '��� ������� � ��� ������',
      '3003' => '������, � ������ �������� ��������� ������, �� ����������',
      '3004' => '������, � ������ �������� ��������� ������, �� ��������',
      '3005' => '������ ���������� ������',
      '3006' => '����� "���������� � ������" �� ����� ���� ������ ������� "��������� ������ ��"',
      '3007' => '�������������� �������������, ������ ����� ������ ��������� �����, ������� ����������� ���� � ������ ��� �� ������ �� ����',
      '3008' => '��� ������� � ������',
      '3009' => '��� ������� �� ��������� ������ ������',
      '3010' => '������ ������ ��������� ������ (������ �����, ��� ��� ������� ���� �������������)',
      '3011' => '������ ������ ������� ������ (������ �����, ��� ��� ������� ���� �������������)',
      '3012' => '������������, ����������� ������, �� �������� ������������ ������',
      '3013' => '������ �� ������� (��� ���������� ������ ������� ������ ������ ���� �������)',
      '3014' => '������ � �������� ��������� (��������� ��� ������ API ��������). ��������� ������ ����� ��������� �����.',
      //��������
      '4001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '4002' => '�������� �� ����������',
      '4003' => '������ ���������� ��������',
      '4004' => '������ ���������� ������',
      '4005' => '������ ���������� ������',
      '4006' => '������� �������� ������ �� �������������',
      '4007' => '� ������ �������� ��������� ������ ������',
      '4008' => '������ � �����������/�������� �����������',
      '4009' => '������ � ������ �����������',
      '4010' => '��������� ��������� �� ����������',
      '4011' => '��� ��������� ���� �������� �� ��� ����',
      '4012' => '������ �������������� �������� ��� ���������',
      '4013' => '���������� ������ �� ������������� ���� ����',
      '4014' => '��������� ���� ����������� ������ ������������',
      '4015' => '��������� ���� ����������� �� ����������',
      '4016' => '��������� ���� ������ ���� �� ����������� ��������� ���������',
      //������ �������������
      '5001' => '��������� ������ ������������� �� ����������',
      '5002' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '5003' => '������ ����������',
      //����������
      '6001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '6002' => '������ e-mail ��� ������������',
      '6003' => '������ ���������� ����������',
      '6004' => '������������ �� ����������',
      '6005' => '������ ���������� ������',
      '6006' => '������ ������������� �������������� ������ �������������',
      //�����������
      '7001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '7002' => '������ �� ����������',
      '7003' => '������ ���������� �������',
      '7004' => '������ ���������� ������',
      //��������
      '8001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '8002' => '������� �� ����������',
      '8003' => '������ ���������� ��������',
      '8004' => '������ ���������� ������',
      '8005' => '������� �� ����������� ������ � ��������',
      '8006' => '�������� �� ������������ ������ � ��������',
      '8007' => 'E-mail, ��������� ��� ������, �� ��������',
      '8008' => '������� ��������� ������ ��� ��������, �� ��������������� ������ � ��������',
      '8009' => '������ ���������� ������ ��� ����� � �������',
      //����
      '9001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '9002' => '������������� ���� �� ����������',
      '9003' => '������ �������� �����',
      '9004' => '������� ��������� ������ ������ ������',
      '9005' => '������������ ������ � ����� �����',
      '9006' => '��� ����� �� ���������',
      '9007' => '������ �������� �������',
      '9008' => '������ ��������� ��� ������� �������� ���� �� ������� ��� �������',
      '9009' => '����, ������� �������� �������� � ������, �������� ������ ������� �������',
      //���������
      '10001' => '�� ���������� ������� ������� ����������� ����� (����������)',
      '10002' => '��������� �� ����������',
      '10003' => '���������� �������� ������ ��������� �� ����������',
      '10004' => '���������� �������� ����������� ��������� �� ����������',
    );

    /**
     * Maximum simultaneous Curl handles in a Multi Curl session
     */
    public static $MAX_BATCH_SIZE = 10;

    /**
     * Api url
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Api key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Api secret
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Account name (*.planfix.ru)
     *
     * @var string
     */
    protected $account;

    /**
     * User login
     *
     * @var string
     */
    protected $userLogin;

    /**
     * User password
     *
     * @var string
     */
    protected $userPassword;

    /**
     * Session identifier
     *
     * @var string
     */
    protected $sid;

    /**
     * Error
     *
     * @var string
     */
    protected $error;

    /**
     * ErrorCode
     *
     * @var string
     */
    protected $errorCode;

    /**
     * hasError
     *
     * @var boolean
     */
    protected $hasError;

    /**
     * Initializes a Planfix Client
     *
     * Required parameters:
     *    - apiUrl - API Url
     *    - apiKey - Application Key
     *    - apiSecret - Application Secret
     *
     * @param array $config The array containing required parameters
     */
    public function __construct($config) {
        $this->clearError();
        $this->setApiUrl($config['apiUrl']);
        $this->setApiKey($config['apiKey']);
        $this->setApiSecret($config['apiSecret']);
    }

    /**
     * Set the Api url
     *
     * @param string $apiUrl Api url
     * @return Planfix_API
     */
    public function setApiUrl($apiUrl) {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * Get the Api url
     *
     * @return string the Api url
     */
    public function getApiUrl() {
        return $this->apiUrl;
    }

    /**
     * Set the Api key
     *
     * @param string $apiKey Api key
     * @return Planfix_API
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get the Api key
     *
     * @return string the Api key
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Set the Api secret
     *
     * @param string $apiKey Api secret
     * @return Planfix_API
     */
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    /**
     * Get the Api secret
     *
     * @return string the Api secret
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

    /**
     * Set the Account
     *
     * @param string $account Account
     * @return Planfix_API
     */
    public function setAccount($account) {
        $this->account = $account;
        return $this;
    }

    /**
     * Get the Account
     *
     * @return string the Account
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * Set User Credentials
     *
     * Required parameters:
     *    - login - User login
     *    - password - User password
     *
     * @param array $user The array containing required parameters
     */
    public function setUser($user) {
        $this->setUserLogin($user['login']);
        $this->setUserPassword($user['password']);
    }

    /**
     * Set the User login
     *
     * @param string $userLogin User login
     * @return Planfix_API
     */
    public function setUserLogin($userLogin) {
        $this->userLogin = $userLogin;
        return $this;
    }

    /**
     * Get the User login
     *
     * @return string the User login
     */
    public function getUserLogin() {
        return $this->userLogin;
    }

    /**
     * Set the User password
     *
     * @param string $userPassword User password
     * @return Planfix_API
     */
    public function setUserPassword($userPassword) {
        $this->userPassword = $userPassword;
        return $this;
    }

    /**
     * Get the User password
     * Private for no external use
     *
     * @return string the User password
     */
    private function getUserPassword() {
        return $this->userPassword;
    }

    /**
     * Set the Sid
     *
     * @param string $sid Sid
     * @return Planfix_API
     */
    public function setSid($sid) {
        $this->sid = $sid;
        return $this;
    }

    /**
     * Get the Sid
     *
     * @return string the Sid
     */
    public function getSid() {
        return $this->sid;
    }

    /**
     * Set the Error
     *
     * @param string $error Error
     * @return Planfix_API
     */
    public function setError($error) {
        $this->hasError = true;
        $this->errorCode = '';
        $this->error = $error;
        return $this;
    }

    /**
     * Get the Error
     *
     * @return string the Error
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Set the ErrorCode
     *
     * @param string $error ErrorCode
     * @return Planfix_API
     */
    public function setErrorCode($errorCode) {
        $this->hasError = true;
        if(!empty($errorCode)) {
          $this->errorCode = $errorCode;
          if(in_array($errorCode, array_keys(self::$ERORR_CODES))) $this->error = self::$ERORR_CODES[$errorCode];
        }
        return $this;
    }

    /**
     * Get the ErrorCode
     *
     * @return string the ErrorCode
     */
    public function getErrorCode() {
        return $this->errorCode;
    }

    /**
     * Get the hasError
     *
     * @return Planfix_API
     */
    public function hasError() {
        return $this->hasError;
    }

    /**
     * Clear the Error and ErrorCode
     *
     * @return Planfix_API
     */
    public function clearError() {
        $this->hasError = false;
        $this->error = '';
        $this->errorCode = '';
        return $this;
    }

    /**
     * Authenticate with previously set credentials
     *
     * @throws Planfix_API_Exception
     * @return Planfix_API
     */
    public function authenticate() {
        $userLogin = $this->getUserLogin();
        $userPassword = $this->getUserPassword();
        if(empty($userLogin) || empty($userPassword)) {
          $userLogin = $this->getApiKey();
          $userPassword = $this->getApiSecret();
        }

        if (!($userLogin && $userPassword)) {
            $this->setError('User credentials are not set');
            return $this;
        }

        $requestXml = $this->createXml();

        $requestXml['method'] = 'auth.login';

        $requestXml->login = $userLogin;
        $requestXml->password = $userPassword;

        //$requestXml->signature = $this->signXml($requestXml);

        $response = $this->makeRequest($requestXml);

        if (!$response['success']) {
          return $this;
        }

        $this->setSid($response['data']['sid']);

        return $this;
    }

    /**
     * Perform Api request
     *
     * @param string|array $method Api method to be called or group of methods for batch request
     * @param array $params (optional) Parameters for called Api method
     * @throws Planfix_API_Exception
     * @return array the Api response
     */
    public function api($method, $params = '') {
        $this->clearError();

        if (!$method) {
            $this->setError('No method specified');
            return $this;
        } elseif (is_array($method)) {
            if (isset($method['method'])) {
                $params = isset($method['params']) ? $method['params'] : '';
                $method = $method['method'];
            } else {
                foreach($method as $request) {
                    if (!isset($request['method'])) {
                        $this->setError('No method specified');
                        return $this;
                    }
                }
            }
        }

        $sid = $this->getSid();

        if($sid) {
          if (is_array($method)) {
            $batch = array();

            foreach($method as $request) {
                $requestXml = $this->createXml();

                $requestXml['method'] = $request['method'];
                $requestXml->sid = $sid;

                $params = isset($request['params']) ? $request['params'] : '';

                if (is_array($params) && $params) {
                    $this->importParams($requestXml, $params);
                }

                if (!isset($requestXml->pageSize)) {
                    $requestXml->pageSize = self::MAX_PAGE_SIZE;
                }

                //$requestXml->signature = $this->signXml($requestXml);

                $batch[] = $requestXml;
            }

            return $this->makeBatchRequest($batch);
          } else {
            $requestXml = $this->createXml();

            $requestXml['method'] = $method;
            $requestXml->sid = $sid;

            if (is_array($params) && $params) {
                $this->importParams($requestXml, $params);
            }

            if (!isset($requestXml->pageSize)) {
                $requestXml->pageSize = self::MAX_PAGE_SIZE;
            }

            //$requestXml->signature = $this->signXml($requestXml);

            return $this->makeRequest($requestXml);
          }
        }
    }

    /**
     * Create XML request
     *
     * @throws Planfix_API_Exception
     * @return SimpleXMLElement the XML request
     */
    protected function createXml() {
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request></request>');

        $account = $this->getAccount();

        if (!$account) {
            $this->setError('Account is not set');
            return $this;
        }

        $requestXml->account = $account;

        return $requestXml;
    }

    /**
     * Import parameters to XML request
     *
     * @param SimpleXMLElement The XML request
     * @param array Parameters
     * @return SimpleXMLElement the XML request
     */
    protected function importParams($requestXml, $params) {
        foreach($params as $key => $val) {
            if (is_array($val)) {
                $requestXml->$key = new SimpleXMLElement("<$key/>");
                foreach($val as $key2 => $val2) {
                    if (is_array($val2)) {
                        $this->importParams($requestXml->$key, $val2);
                    } else {
                        $requestXml->$key->addChild($key2, $val2);
                    }
                }
            } else {
                $requestXml->addChild($key, $val);
            }
        }
        return $requestXml;
    }

    /**
     * Sign XML request
     *
     * @param SimpleXMLElement The XML request
     * @throws Planfix_API_Exception
     * @return string the Signature
     */
    protected function signXml($requestXml) {
        return md5($this->normalizeXml($requestXml).$this->getApiSecret());
    }

    /**
     * Normalize the XML request
     *
     * @param SimpleXMLElement $node The XML request
     * @return string the Normalized string
     */
    protected function normalizeXml($node) {
        $node = (array) $node;
        ksort($node);

        $normStr = '';

        foreach ($node as $child) {
            if (is_array($child)) {
                $normStr .= implode('', array_map(array($this,'normalizeXml'), $child));
            } elseif (is_object($child)) {
                $normStr .= $this->normalizeXml($child);
            } else {
                $normStr .= (string) $child;
            }
        }

        return $normStr;
    }

    /**
     * Make the batch request to Api
     *
     * @param array $batch The array of XML requests
     * @return array the array of Api responses
     */
    protected function makeBatchRequest($batch) {
        $mh = curl_multi_init();

        $batchCnt = count($batch);
        $max_size = $batchCnt < self::$MAX_BATCH_SIZE ? $batchCnt : self::$MAX_BATCH_SIZE;

        $batchResult = array();

        for ($i = 0; $i < $max_size; $i++) {
            $requestXml = array_shift($batch);
            $ch = $this->prepareCurlHandle($requestXml);
            $chKey = (string) $ch;
            $batchResult[$chKey] = array();
            curl_multi_add_handle($mh, $ch);
        }

        do {
            do {
                $mrc = curl_multi_exec($mh, $running);
            } while($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($request = curl_multi_info_read($mh)) {
                $ch = $request['handle'];
                $chKey = (string) $ch;
                $batchResult[$chKey] = $this->parseApiResponse(curl_multi_getcontent($ch), curl_error($ch));

                if (count($batch)) {
                    $requestXml = array_shift($batch);
                    $ch = $this->prepareCurlHandle($requestXml);
                    $chKey = (string) $ch;
                    $batchResult[$chKey] = array();
                    curl_multi_add_handle($mh, $ch);
                }

                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

            if ($running) {
                curl_multi_select($mh);
            }

        } while($running && $mrc == CURLM_OK);

        return array_values($batchResult);
    }

    /**
     * Make the request to Api
     *
     * @param SimpleXMLElement $requestXml The XML request
     * @return array the Api response
     */
    protected function makeRequest($requestXml) {
        $ch = $this->prepareCurlHandle($requestXml);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        return $this->parseApiResponse($response, $error);
    }

    /**
     * Prepare the Curl handle
     *
     * @param SimpleXMLElement $requestXml The XML request
     * @return resource the Curl handle
     */
    protected function prepareCurlHandle($requestXml) {
        $ch = curl_init($this->getApiUrl());

        curl_setopt_array($ch, self::$CURL_OPTS);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getApiKey());

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml->asXML());

        return $ch;
    }

    /**
     * Parse the Api response
     *
     * @link http://goo.gl/GWa1c List of Api error codes
     *
     * @param string $response The Api response
     * @param string $error The Curl error if any
     * @return array the Curl handle
     */
    protected function parseApiResponse($response, $error) {
        $this->clearError();
        $result = array(
            'success'    => 1,
            'meta'       => null,
            'data'       => null
        );

        if ($error) {
            $result['success'] = 0;
            $this->setError($error);
            return $result;
        }

        try {
            $responseXml = new SimpleXMLElement($response);
        } catch (Exception $e) {
            $result['success'] = 0;
            $this->setError($e->getMessage());
            return $result;
        }

        if ($responseXml['status'] == 'error') {
            $result['success'] = 0;
            $this->setError((string)$responseXml->message);
            $this->setErrorCode((string)$responseXml->code);
            return $result;
        }

        if (isset($responseXml->sid)) {
            $result['data']['sid'] = (string) $responseXml->sid;
        } else {
            $responseXml = $responseXml->children();

            foreach($responseXml->attributes() as $key => $val) {
                $result['meta'][$key] = (int) $val;
            }

            if ($result['meta'] == null || $result['meta']['totalCount'] || $result['meta']['count']) {
                $result['data'] = $this->exportData($responseXml);
            }
        }

        return $result;
    }

    /**
     * Exports the Xml response to array
     *
     * @param SimpleXMLElement $responseXml The Api response
     * @return array the Exported data
     */
    protected function exportData($responseXml) {
        $root = $responseXml->getName();
        $data[$root] = array();

        $rootChildren = $responseXml->children();

        $names = array();
        foreach($rootChildren as $child) {
            $names[] = $child->getName();
        }

        $is_duplicate = count(array_unique($names)) != count($names) ? true : false;
        $grandchildren = $child->children();

        if ($grandchildren->count() || count($grandchildren) > 1) {
            if (count($child->children()) > 1) {
                $data[$root] = array_merge($data[$root], $is_duplicate ? array($this->exportData($child)) : $this->exportData($child));
            } else {
                $data[$root][$child->getName()] = (string) $child;
            }
        }

        return $data;
    }

}

/* EOF */