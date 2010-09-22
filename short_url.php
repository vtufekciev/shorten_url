<?php
/** * Short Url*
* @description Short url class for service 0.mk 
* @category Miscs
* @author Vladimir Tufekchiev
* @email vtufekciev@yahoo.com
* @link http://www.pelaphptutorials.com/article/short-url-0-mk-service-api-class.html */ 
    class Short_url extends Controller
    {
     
        private $username;
        private $api_key;
        private $api_domain = 'http://api.0.mk/v2';
        private $api;
        public $errors;
        
        public  function set_username ($username = null)
        {
            if ( $username == null)
            {
                show_error('$username is not set');
                die();
            }
            else
            {
                $this->username = $username;
            }
        }
        
        public function set_api_key( $api_key = null)
        {
            if ($api_key == null)
            {
                show_error('$api_key is not set');
                die();
            }
            else
            {
                $this->api_key = $api_key;
            }
        }
        
        public function set_api_domain( $api_domain = null)
        {
            if ($api_domain == null)
            {
                show_error('$api_domain is not set');
                die();
            }
            else
            {
                $this->api_domain = $api_domain;
            }
        }
        
        
        public function short_url($data = array())
        {
            if (count($data) > 0)
            {
                $this->set_username($data['username']);
                $this->set_api_key($data['api_key']);
                if (!empty($data['api_domain']))
                {
                    $this->set_api_domain($data['api_domain']);
                }
                unset($data);
                $this->api = $this->api_domain."/skrati?format=json&korisnik=".
                                $this->username."&apikey=".$this->api_key;
            }
            else
            {
                $this->api = $this->api_domain."/skrati?format=json";
            }
        }
        
        public function get_shorten_url($url = null)
        {
            if($url == null)
            {
               show_error('$url is not set');
               die();
            }
            $this->api .= '&link='.$url;
            $result = json_decode($this->get_url_content($this->api));
            if ($result->status == 1) { // sucessful, return the short link 
                return $result->kratok;
            } else { // error, return FALSE
                $this->errors = $result->greskaMsg;
                return FALSE;
            }		
        }
     
        private function get_url_content($url)
        {
            if (function_exists('curl_init'))
            {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_URL, $url);
                $content = curl_exec($curl);
                curl_close($curl);
                return $content;    
            }
            else
            {
                return file_get_contents($url);
            }
        } 
    }
    
/* End of file short_url.php */
/* Location: ./application/libraries/short_url.php */     