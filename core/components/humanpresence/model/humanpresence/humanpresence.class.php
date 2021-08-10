<?php

class HumanPresence
{
    /**
     * @var modX|null $modx
     */
    public $modx = null;
    /**
     * @var array
     */
    public $config = array();
    /**
     * @var string
     */
    public $namespace = 'humanpresence';

    public function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('humanpresence.core_path', $config, $this->modx->getOption('core_path') . 'components/humanpresence/');
        $this->config = array_merge(array(
            'basePath' => $this->modx->getOption('base_path'),
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'snippetPath' => $corePath . 'elements/snippets/',
            'pluginPath' => $corePath . 'elements/plugin/',
        ), $config);
        $this->modxUserId = $this->getOption('use_modx_id', $config, true);
        $this->modx->addPackage('humanpresence', $this->config['modelPath']);
    }

    /**
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     * @param $url
     * @return array|mixed
     */
    public function checkSession($url)
    {
// Check Human Presence via an API request.
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);
        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
// If we got an OK response from the API...
        if (!empty($status_code) && 200 == $status_code) {
// Return the data array.
            return json_decode($response, true);
        } else {
// Otherwise return an empty array.
            return [];
        }
    }

    /**
     *
     */
    public function checkHumanPresence()
    {
        if(!$this->getOption('apikey')){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[HumanPresence] No API Key set!');
            return false;
        }
        $response = $this->checkSession($this->url());
        if (isset($response['signal']) && $response['signal'] == 'HUMAN') {
// Check confidence level
            if (isset($response['confidence']) && $response['confidence'] >
                $this->getOption('confidence')) {
// Passes the human test, let's process the form
                return true;
            }
        }
        return false;
    }

    private function url()
    {
        return 'https://api.humanpresence.io/v2/checkhumanpresence/' .
            $this->getSessionId() . '?apikey=' . $this->getOption('apikey');

    }

    private function getSessionId()
    {
        return !empty($_COOKIE['ellipsis_sessionid']) ? $this->modx->sanitizeString(
            $_COOKIE['ellipsis_sessionid']) : '';
    }

}