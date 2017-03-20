<?php

namespace classes\grabber;

class GetDOM {
    
    protected $dom;


    public function __construct($html) {
        
        $dom = new \DomDocument();
        @$dom->loadHTML($html);
        
        $this->dom = $dom;
        
    }
    
    public function getDOM() {
        return $this->dom;
    }
    
    public function getPath($tag, $selector, $class) {
        
        $search = ".//{$tag}[@{$selector}='{$class}']";
        
        $xpath = new \DomXPath($this->dom);
        $res = $xpath->query($search);
        
        return $res;
        
    }
    
    public function getValueOne($tag, $selector, $class) {
        
        foreach ( $this->getPath($tag, $selector, $class) as $object) {
            return $object->nodeValue;
        }
        
    }
    
    public function getValueArray($tag, $selector, $class) {
        
        $array = array();
        
        foreach ( $this->getPath($tag, $selector, $class) as $object) {
            $array[] = $object->nodeValue;
        }
        
        return $array;
        
    }
    
    public function getHTMLOne($tag, $selector, $class) {
        
        foreach ( $this->getPath($tag, $selector, $class) as $object) {
            return $object->ownerDocument->saveHTML($object);
        }
        
    }
    
    public function getHTMLArray($tag, $selector, $class) {
        
        $array = array();
        
        foreach ( $this->getPath($tag, $selector, $class) as $object) {
            
            $array[] = $object->ownerDocument->saveHTML($object);
            
        }
        
        return $array;
        
    }
    
}

