<?php

namespace Epicsweb;

class PhpAccount
{
	private $framework;

	public function __construct($framework = 'ci')
	{
		$this->framework = $framework;
	}

	/**
	 * Realiza o CURL na API, verificando a conta do usuário
	 *
	 * @param array $param
	 * @return object
	 */
    private function executeCurl(array $param): ?object
	{
    	if ($param['url'] && $param['data'] && $param['method']) {
	    	switch ($this->framework) {
	    		case 'laravel':
	    			$url = env('AE_URL');
	    			$userpwd = env('AE_USER');
	    			$passpwd = env('AE_PASS');
	    			break;
	    		case 'ci':
	    			$ci =& get_instance();
	    			$config = (array) $ci->config->item('api_epics');
			    	$url = $config['server'];
	    			$userpwd = $config['http_user'];
	    			$passpwd = $config['http_pass'];
	    			break;
	    		default:
	    			return false;
	    			break;
	    	}

	    	$url = $url . $param['url'];
	    	$auth = $userpwd . ':' . $passpwd;
  
	        switch ($param['method']) {
	            case 'post':
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($curl, CURLOPT_USERPWD, $auth);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param['data'], null, '&'));
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					$curl_response = curl_exec($curl);
					curl_close($curl);
					return json_decode($curl_response, false);
	            break;
	            case 'get':
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($curl, CURLOPT_USERPWD, $auth);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					$curl_response = curl_exec($curl);
					curl_close($curl);
					return json_decode($curl_response, false);
	            break;
	            default:
	            	return null;
	            	break;
	        }
		}

		return null;
    }

    /**
	 * Realiza o cadastro de uma nova conta
	 */
    public function account_create($data): ?object
    {
        return $this->executeCurl([
            'url' => 'dashboard/contaEpics/create',
            'data' => $data,
            'method' => 'post'
        ]);
    }
    
	/**
	 * Realiza o login de uma conta
	 */
    public function account_login($data): ?object
    {
        return $this->executeCurl([
            'url' => 'dashboard/contaEpics/login',
            'data' => $data,
            'method' => 'post'
        ]);
    }

	/**
	 * Verifica se a conta existe
	 */
    public function account_verify($data): ?object
    {
        return $this->executeCurl([
            'url' => 'dashboard/contaEpics/verify',
            'data' => $data,
            'method' => 'post'
        ]);
    }

	/**
	 * Recuperação de conta
	 */
    public function forget_password($data): ?object
    {
        return $this->executeCurl([
            'url' => 'dashboard/contaEpics/forget_password',
            'data' => $data,
            'method' => 'post'
        ]);
    }
}
