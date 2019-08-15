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

	    	//VERIFICA FRAMEWORK
	    	switch ($this->framework) {
	    		case 'laravel':
	    			
	    			return false;

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

			    	$data 						= $param['data'];

			    	$data['companies_tokens']	= $config['companies_tokens'];

			    	$data['session_id']			= session_id();
			    	$data['json_get']			= json_encode( $ci->input->get() );
			    	$data['json_post']			= json_encode( $ci->input->post() );
			    	$data['ip']					= $this->get_ip();
			    	
			    	$data['url']				= current_url();
			    	$data['uri']				= uri_string();
			    	$data['method']				= $_SERVER['REQUEST_METHOD'];

	    		break;
	    		default:

	    			return false;

	    		break;
	    	}

	    	//PREPARA OS DADS
	    	$url 			= $url . $param['url'];
  
	        switch ($param['method']) {

	            case 'post':

	                $curl 			= curl_init($url);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	                curl_setopt($curl, CURLOPT_POST, true);
	                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data, NULL, '&'));
	                $curl_response = curl_exec($curl);
	                curl_close($curl);
	                return json_decode($curl_response, false);

	            break;
	            default:

	            	return false;

	            break;
	        }

	   	} else return false;

    }

    //FUNCTION TO SEND A SMS
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