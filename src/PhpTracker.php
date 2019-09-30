<?php

namespace Epicsweb;

class PhpTracker
{

	private $framework;

	//CONSTRUCT
	public function __construct($framework = 'ci') {

		$this->framework = $framework;

	}

    //FUNÇAO QUE EXECUTA O CURL
    private function executeCurl($param) {

    	if( is_array($param) && $param['url'] && $param['data'] && $param['method'] ) {

    		$data 								= $param['data'];

	    	//VERIFICA FRAMEWORK
	    	switch ($this->framework) {
	    		case 'laravel':
	    			
	    			$url 						= env('AET_URL');

			    	$data['companies_tokens']	= isset($data['companies_token']) ? $data['companies_token']: env('AET_TOKEN');

	    		break;
	    		case 'ci':
	    			
	    			$ci 						=& get_instance();

	    			//LOAD URL HELPER
	    			if ( ! function_exists('base_url'))
	    				$ci->load->helper('url');

	    			//LOAD THE CONFIG FILE
	    			$ci->config->load('epicsweb');

	    			$config  					= (array) $ci->config->item('tracker');

			    	$url 						= $config['server'];

			    	$data['companies_tokens']	= isset($data['companies_token']) ? $data['companies_token'] : $config['companies_tokens'];

	    		break;
	    		default:

	    			return false;

	    		break;
	    	}

	    	//PREPARA OS DADS
	    	$url 						= $url . $param['url'];

	    	$data['session_id']			= session_id();

	    	$data['ip']					= $this->get_ip();

	    	$data['json_get']			= json_encode( (isset($_GET) ? $_GET  : [])  );
	    	$data['json_post']			= json_encode( (isset($_GET) ? $_POST : [])  );
	    	
	    	$data['url']				= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	    	$data['uri']				= isset($_SERVER['REQUEST_URI']) 	? $_SERVER['REQUEST_URI'] : false;
	    	$data['method']				= isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
	    	$data['referer']			= isset($_SERVER["HTTP_REFERER"]) 	? $_SERVER["HTTP_REFERER"] : false;
  
	        switch ($param['method']) {

	            case 'post':

	                $curl 				= curl_init($url);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
	                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
	                curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	                curl_setopt($curl, CURLOPT_POST, true);
	                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data, NULL, '&'));
	                curl_exec($curl);
	                curl_close($curl);
	                
	                return true;

	            break;
	            default:

	            	return false;

	            break;
	        }

	   	} else return false;

    }

    //FUNCTION TO INSERT NEW LOG
    public function insert($data)
    {
        return $this->executeCurl([
            'url'   	=> 'tracker/insert',
            'data'    	=> $data,
            'method'    => 'post'
        ]);
    }

    //RETURN VALID IP
    private function get_ip()
    {

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;

    }
    
}