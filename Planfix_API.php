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
      '0001' => 'Неверный API Key',
      '0002' => 'Приложение заблокировано',
      '0003' => 'Ошибка XML разбора. Некорректный XML',
      '0004' => 'Неизвестный аккаунт',
      '0005' => 'Ключ сессии недействителен (время жизни сессии истекло)',
      '0006' => 'Неверная подпись',
      '0007' => 'Превышен лимит использования ресурсов (превышено максимальное количество запросов в сутки)',
      '0008' => 'Неизвестное имя функции',
      '0009' => 'Отсутствует один из обязательных параметров функции',
      '0010' => 'Аккаунт заморожен',
      '0011' => 'На площадке аккаунта производится обновление программного обеспечения',
      '0012' => 'Отсутствует сессия, не передан параметр сессии в запрос',
      '0013' => 'Неопределенный пользователь',
      '0014' => 'Пользователь неактивен',
      '0015' => 'Недопустимое значение параметра',
      '0016' => 'В данном контексте параметр не может принимать переданное значение',
      '0017' => 'Отсутствует значение для зависящего параметра',
      '0018' => 'Функции/функционал не реализована',
      '0019' => 'Заданы конфликтующие между собой параметры',
      '0020' => 'Вызов функции запрещен',
      '0021' => 'Запрошенное количество объектов больше максимально разрешенного для данной функции',
      '0022' => 'Использование API недоступно для бесплатного аккаунта',
      '0023' => 'Запрошенное действие невозможно в рамках текущего тарифного плана аккаунта',
      '0024' => 'Аккаунт расположен в другом датацентре, проверьте url запроса',
      //Аутентификация
      '1001' => 'Неверный логин или пароль',
      '1002' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      //Проект
      '2001' => 'Запрошенный проект не существует',
      '2002' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '2003' => 'Ошибка добавления проекта',
      //Задача
      '3001' => 'Указанная задача (или другой запрашиваемый объект) не существует',
      '3002' => 'Нет доступа к над задаче',
      '3003' => 'Проект, в рамках которого создается задача, не существует',
      '3004' => 'Проект, в рамках которого создается задача, не доступен',
      '3005' => 'Ошибка добавления задачи',
      '3006' => 'Время "Приступить к работе" не может быть больше времени "Закончить работу до"',
      '3007' => 'Неопределенная периодичность, скорее всего задано несколько узлов, которые конфликтуют друг с другом или не указан ни один',
      '3008' => 'Нет доступа к задаче',
      '3009' => 'Нет доступа на изменение данных задачи',
      '3010' => 'Данную задачу отклонить нельзя (скорее всего, она уже принята этим пользователем)',
      '3011' => 'Данную задачу принять нельзя (скорее всего, она уже принята этим пользователем)',
      '3012' => 'Пользователь, выполняющий запрос, не является исполнителем задачи',
      '3013' => 'Задача не принята (для выполнения данной функции задача должна быть принята)',
      '3014' => 'Задача в процессе изменения (сценарием или другим API запросом). Повторите запрос через некоторое время.',
      //Действие
      '4001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '4002' => 'Действие не существует',
      '4003' => 'Ошибка добавления действия',
      '4004' => 'Ошибка обновления данных',
      '4005' => 'Ошибка обновления данных',
      '4006' => 'Попытка изменить статус на недозволенный',
      '4007' => 'В данном действии запрещено менять статус',
      '4008' => 'Доступ к комментария/действию отсутствует',
      '4009' => 'Доступ к задаче отсутствует',
      '4010' => 'Указанная аналитика не существует',
      '4011' => 'Для аналитики были переданы не все поля',
      '4012' => 'Указан несуществующий параметр для аналитики',
      '4013' => 'Переданные данные не соответствуют типу поля',
      '4014' => 'Указанный ключ справочника нельзя использовать',
      '4015' => 'Указанный ключ справочника не существует',
      '4016' => 'Указанный ключ данных поля не принадлежит указанной аналитике',
      //Группа пользователей
      '5001' => 'Указанная группа пользователей не существует',
      '5002' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '5003' => 'Ошибка добавления',
      //Сотрудники
      '6001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '6002' => 'Данный e-mail уже используется',
      '6003' => 'Ошибка добавления сотрудника',
      '6004' => 'Пользователь не существует',
      '6005' => 'Ошибка обновления данных',
      '6006' => 'Указан идентификатор несуществующей группы пользователей',
      //Контрагенты
      '7001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '7002' => 'Клиент не существует',
      '7003' => 'Ошибка добавления клиента',
      '7004' => 'Ошибка обновления данных',
      //Контакты
      '8001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '8002' => 'Контакт не существует',
      '8003' => 'Ошибка добавления контакта',
      '8004' => 'Ошибка обновления данных',
      '8005' => 'Контакт не активировал доступ в ПланФикс',
      '8006' => 'Контакту не предоставлен доступ в ПланФикс',
      '8007' => 'E-mail, указанный для логина, не уникален',
      '8008' => 'Попытка установки пароля для контакта, не активировавшего доступ в ПланФикс',
      '8009' => 'Ошибка обновления данных для входа в систему',
      //Файл
      '9001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '9002' => 'Запрашиваемый файл не существует',
      '9003' => 'Ошибка загрузки файла',
      '9004' => 'Попытка загрузить пустой список файлов',
      '9005' => 'Недопустимый символ в имени файла',
      '9006' => 'Имя файла не уникально',
      '9007' => 'Ошибка файловой системы',
      '9008' => 'Ошибка возникает при попытке добавить файл из проекта для проекта',
      '9009' => 'Файл, который пытаются добавить к задаче, является файлом другого проекта',
      //Аналитика
      '10001' => 'На выполнение данного запроса отсутствуют права (привилегии)',
      '10002' => 'Аналитика не существует',
      '10003' => 'Переданный параметр группы аналитики не существует',
      '10004' => 'Переданный параметр справочника аналитики не существует',
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
      $array = array();

      foreach ($responseXml as $name => $element) {
          ($node = & $array[$name])
              && (1 === count($node) ? $node = array($node) : 1)
              && $node = & $node[];

          $node = $element->count() ? $this->exportData($element) : trim($element);
      }

      return $array;
    }

}


/* EOF */