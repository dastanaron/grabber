<?php

namespace classes\grabber;

class Grabber {
    
    protected $url;
    
    protected $timeout;
    
    protected $response;
    
    protected $dom;
    
    protected $result_query;
    
    
    /*
     * Устанавливаем начальные параметры
     */
    public function __construct($url, $timeout=30) {
        
        $this->url = $url;
        $this->timeout = $timeout;
        
    }
    
    /*
     * Функция делающая запросы к адресу
     */
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
    
    /*
     * Установка Объекта дом
     */
    
    public function initDom() {
          
        $this->CurlUrl();
        
        $dom = new \DomDocument();
        @$dom->loadHTML($this->response);
        
        $this->dom = $dom;
        
        return $this;
          
    }
    
    /*
     * Вернуть дом-объект для целей минующих данную библиотеку
     */
    
    public function getDom() {
        
        return $this->dom;
        
    }
    /*
     * Вернуть просто HTML содержимое URL
     */
    
    public function getHTML() {
        
        $this->CurlUrl();
        return $this->response;
        
    }
    
    /*
     * Инициация объекта Класса DomXPath и выполнение запроса по параметрам
     */
    
    public function PathQuery($tag, $selector, $value) {
        
        $search = ".//{$tag}[@{$selector}='{$value}']";
        
        $xpath = new \DomXPath($this->dom);
        $res = $xpath->query($search);
        
        $this->result_query = $res;
        
        return $this;
        
    }
    
    public function getValueOne() {
        
        foreach ($this->result_query as $object) {
            return $object->nodeValue;
        }
        
    }
    
    public function getValueArray() {
        
        $array = array();
        
        foreach ($this->result_query as $object) {
            $array[] = $object->nodeValue;
        }
        
        return $array;
        
    }
    
    public function getHTMLOne() {
        
        foreach ($this->result_query as $object) {
            return $object->ownerDocument->saveHTML($object);
        }
        
    }
    
    public function getHTMLArray() {
        
        $array = array();
        
        foreach ( $this->result_query as $object) {
            
            $array[] = $object->ownerDocument->saveHTML($object);
            
        }
        
        return $array;
        
    }
    
    
    
}



