<?php

namespace classes\grabber;

class GetUrl {
    
    protected $url;
    
    protected $timeout;
    
    protected $response;
    
    public function __construct($url, $timeout=30) {
        
        $this->url = $url;
        $this->timeout = $timeout;
        
    }
    
    private function CurlUrl() {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => $this->timeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          //CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return $err;
        } else {
            
          $this->response = $response;
          
        }  
        
    }
    
    public function getObject() {
          
        $this->CurlUrl();
        
        return new \classes\grabber\GetDOM($this->response);
          
    }
    
    public function getHTML() {
        
        $this->CurlUrl();
        return $this->response;
        
    }
    
    
    
    
}



