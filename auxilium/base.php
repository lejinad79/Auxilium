<?php

namespace Auxilium {

    use Auxilium\Inspector as Inspector;
    use Auxilium\ArrayMethods as ArrayMethods;
    use Auxilium\StringMethods as StringMethods;
    use Auxilium\Core\Exception as Exception;

    class Base {

        private $_inspector;

        public function __construct($o = []) {

            $this->_inspector = new \Inspector($this);

            if (is_array($o) || is_object($o)) {

                foreach ($o as $key => $value) {

                    $key = ucfirst($key);
                    $method = "set($key)";
                    $this->method($value);
                }
            }
        }

        public function __call($name, $arguments) {

            if (!empty($this->_inspector)) {

                throw new Exception("Call parent::__construct()");
            }

            $getMatches = \StringMethods::match($name, "^get([a-zA-Zo-9]+)$");

            if (sizeof($getMatches) > 0) {

                // lcfirst ---> Returns a string with the first character of str ,
                //lowercased if that character is alphabetic

                $normalized = lcfirst($getMatches[0]);
                $p = "_{$normalized}";

                if (property_exists($this, $property)) {

                    $meta = $this->_inspector->getPropertyMeta($p);

                    if (empty($meta["@readwrite"]) && empty($meta["@read"])) {

                        throw $this->_getExceptionForWriteOnly($normalized);
                    }

                    if (isset($this->$property)) {

                        return $this->$property;
                    }

                    return NULL;
                }
            }

            $setMatches = \StringMethods::match($name, "^set([a-zA-Zo-9]+)$");

            if (sizeof($setMatches) > 0) {

                $normalized = lcfirst($setMatches[0]);
                $p = "_{$normalized}";

                if (property_exists($this, $property)) {

                    $meta = $this->_inspector->getPropertyMeta($p);

                    if (empty($meta["@readwrite"]) && empty($meta["@write"])) {

                        throw $this->_getExceptionForWriteOnly($normalized);
                    }

                    $this->$property = $arguments[0];

                    return $this;
                }
            }
            
            throw $this->_getExceptionForImplementation($name);
        
        }
        
        public function __get($name)
        {
            $function = "get".ucfirst($name);
            return $this->$function();
        }
        
        public function __set($name, $value)
        {
            $function = "set".ucfirst($name);
            return $this->$function($value);
        }
        
        protected function _getExceptionForReadonly($property) {
            
            return new \Exception\Readonly("{$property} is read only!!!");
            
        }
        
        protected function _getExceptionForWriteonly($property) {
            
            return new \Exception\Writeonly("{$property} is write only!!!");
            
        }
        
        protected function _getExceptionForProperty() {
            
            return new \Exception\Property("Invalid property");
            
        }
        
        protected function _getExceptionForImplementation() {
            
            return new Exception\Argument("{$method} method not implemented");
            
        }
    
        }

}

