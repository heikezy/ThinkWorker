<?php
/**
 *  ThinkWorker - THINK AND WORK FAST
 *  Copyright (c) 2017 http://thinkworker.cn All Rights Reserved.
 *  Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  Author: Dizy <derzart@gmail.com>
 */

namespace think\exception;


class RouteNotFoundException extends HttpException
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * RouteNotFoundException constructor.
     *
     * @param \Exception $origin
     * @param string $uri
     * @param string $message
     */
    public function __construct($origin, $uri, $message = "")
    {
        $message = "Uri Not Matched: ".$uri;
        parent::__construct(404, $message, true, $origin);
        $this->uri = $uri;
        if(config("think.debug")==true) {
            $this->setHttpBody($this->getDebugHttpBody());
        }else{
            $this->setHttpBody($this->getProHttpBody());
        }
    }

    /**
     * Get Http Return in Debug Mode
     *
     * @return string
     */
    private function getDebugHttpBody(){
        return $this->loadTemplate("TracingPage", [
            'title' => think_core_lang("tracing page route not found"),
            'main_msg' => think_core_lang("tracing page route not found"),
            'main_msg_detail' => $this->uri,
            'main_error_pos' => $this->formErrorPos(),
            'main_error_detail' => think_core_lang("tracing page route not found error detail"),
            'lang_tracing' => think_core_lang("tracing page tracing"),
            'lang_src' => think_core_lang("tracing page src file"),
            'lang_line' => think_core_lang('tracing page line num'),
            'lang_call' => think_core_lang("tracing page call"),
            'tracing_table' => $this->formTracingTable(),
            'request_table' => $this->formRequestTable(),
            'env_table' => $this->formEnvTable(),
            'lang_key' => think_core_lang("tracing page key"),
            'lang_value' => think_core_lang("tracing page value"),
            'lang_request' => think_core_lang("tracing page request detail"),
            'lang_env' => think_core_lang("tracing page env")
        ]);
    }

    /**
     * Get Http Return in Production Mode
     *
     * @return string
     */
    private function getProHttpBody(){
        return $this->loadTemplate("ErrorPage", [
            'title'=>think_core_lang("page not found title"),
            'code'=>404,
            'msg'=>think_core_lang("page not found msg")
        ]);
    }
}