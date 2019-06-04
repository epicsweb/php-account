<?php

namespace Epicsweb;

class PhpAccount
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
	    			
	    			$url 			= env('AE_URL');
	    			$userpwd 		= env('AE_USER');
	    			$passpwd 		= env('AE_PASS');

	    		break;
	    		case 'ci':
	    			
	    			//LOAD THE CONFIG FILE
	    			$ci 			=& get_instance();
	    			$config  		= (array) $ci->config->item('api_epics');

			    	$url 			= $config['server'];
	    			$userpwd 		= $config['http_user'];
	    			$passpwd 		= $config['http_pass'];

	    		break;
	    		default:

	    			return false;

	    		break;
	    	}

	    	//PREPARA OS DADS
	    	$url 			= $url . $param['url'];
	    	$auth			= $userpwd . ':' . $passpwd;
  
	        switch ($param['method']) {

	            case 'post':

	                $curl 			= curl_init($url);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	                curl_setopt($curl, CURLOPT_USERPWD, $auth);
	                curl_setopt($curl, CURLOPT_POST, true);
	                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param['data'], NULL, '&'));
	                $curl_response = curl_exec($curl);
	                curl_close($curl);
	                return json_decode($curl_response, false);

	            break;
	            case 'get':

	                $curl = curl_init($url);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	                curl_setopt($curl, CURLOPT_USERPWD, $auth);
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
    public function account_create($data)
    {
        return $this->executeCurl([
            'url'   	=> 'dashboard/contaEpics/create',
            'data'    	=> $data,
            'method'    => 'post'
        ]);
    }
    
    //FUNCTION TO SEND A EMAIL
    public function account_login($data)
    {
        return $this->executeCurl([
            'url'   	=> 'dashboard/contaEpics/login',
            'data'    	=> $data,
            'method'    => 'post'
        ]);
    }

    //FUNCTION - VERIFICA SE EXISTE CONTA
    public function account_verify($data)
    {
        return $this->executeCurl([
            'url'   	=> 'dashboard/contaEpics/verify',
            'data'    	=> $data,
            'method'    => 'post'
        ]);
    }
    
}