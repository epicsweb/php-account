<?php

namespace Epicsweb;

class PhpTracker
{
	private $framework;

	public function __construct($framework = 'ci')
	{
		$this->framework = $framework;
	}

    //FUNÇAO QUE EXECUTA O CURL
    private function executeCurl(array $param): ?object
	{
    	if (empty($param['url']) || empty($param['data']) || empty($param['method'])) {
			return null;
		}

		$data = $param['data'];

		//VERIFICA FRAMEWORK
		switch ($this->framework) {
			case 'laravel':
				$url = env('AET_URL');
				$data['companies_tokens'] = $data['companies_token'] ?? env('AET_TOKEN');
				break;
			case 'ci':
				$ci =& get_instance();
				if (!function_exists('base_url')) {
					$ci->load->helper('url');
				}
				$ci->config->load('epicsweb');
				$config = (array) $ci->config->item('tracker');
				$url = $config['server'];
				$data['companies_tokens'] = $data['companies_token'] ?? $config['companies_tokens'];
				break;
			default:
				return null;
				break;
		}

		//PREPARA OS DADS
		$url = $url . $param['url'];

		$data['session_id'] = session_id();
		$data['ip'] = $this->get_ip();

		$data['json_get'] = json_encode($_GET ?? []);
		$data['json_post'] = json_encode($_GET ?? []);
		
		$data['url'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
		$data['url'] .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$data['uri'] = $_SERVER['REQUEST_URI'] ?? false;
		$data['method'] = $_SERVER['REQUEST_METHOD'] ?? false;
  
		switch ($param['method']) {
			case 'post':
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
				curl_setopt($curl, CURLOPT_TIMEOUT, 1);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
				curl_exec($curl);
				$curl_response = curl_exec($curl);
				var_dump($curl_response);
				curl_close($curl);
				$return = json_decode($curl_response, false);
				break;
			case 'get':
				$curl = curl_init($url . '?' . http_build_query($data, '', '&'));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
				curl_setopt($curl, CURLOPT_TIMEOUT, 1);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_exec($curl);
				$curl_response = curl_exec($curl);
				curl_close($curl);
				$return = json_decode($curl_response, false);
				break;
			default:
				$return = null;
				break;
		}

		return $return;
    }

	/**
	 * Insere um novo rastreio no sistema de tracker
	 *
	 * @param array $data
	 * @return object|null
	 */
    public function insert(array $data): ?object
    {
        return $this->executeCurl([
            'url'   	=> 'tracker/insert',
            'data'    	=> $data,
            'method'    => 'post'
        ]);
    }

	/**
	 * Captura rastreios no sistema de trackers e gera um relatório resumido
	 *
	 * @param array $data
	 * @return object|null
	 */
    public function report(array $data): ?object
    {
        return $this->executeCurl([
            'url'   	=> 'tracker/report',
            'data'    	=> $data,
            'method'    => 'get'
        ]);
    }

	/**
	 * Retorna o IP válido do usuário
	 *
	 * @return string
	 */
    private function get_ip(): string
    {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}

		return (string) $ip;
    }
}
