<?php


namespace App\JsonRpc;


class Client extends \Lightbulb\Json\Rpc2\Client
{
    protected $_callstack = [];

    /**
     * The magic getter in order to make class.method calls possible
     */
    public
    function __get(
        $name
    ) {
        $this->_callstack[] = $name;
        return $this;
    }

    /**
     * The RPC actual caller
     */
    public function __call($method, $args)
    {
        // use callstack or not?
        if (strpos($method, '.') === false && count($this->_callstack) > 0) {
            $method = implode('.', $this->_callstack) . '.' . $method;
        }
        // Empty callstack, construct cURL object, call and return
        $this->_callstack = [];
        if (count($args) == 1) {
            $request = $this->_requestFactory($method, $args[0]);
            $curl = $this->_curlFactory($request);
            $raw = curl_exec($curl);
            $return = json_decode($raw);
            curl_close($curl);

            // Debugging?
            if ($this->_debug === true) {
                $this->_debugRequest = $request;
                $this->_debugResponse = $raw;
            }
            return $return;
        }
        return parent::__call($method, $args[0]);
    }
}
