<?php

namespace Auxilium {

    class Inspector {

        protected $_class;
        protected $meta = [
            'class' => [],
            'properties' => [],
            'methods' => []
        ];
        protected $_properties = [];
        protected $_methods = [];

        public function __construct($class) {

            $this->_class = $class;
        }

        protected function _getClassComment() {

            $r = new \ReflectionClass($this->_class);
            return $r->getDocComment();
        }

        protected function _getClassProperties() {

            $r = new \ReflectionClass($this->_class);
            return $r->getProperties();
        }

        protected function _getClassMethods() {

            $r = new \ReflectionClass($this->_class);
            return $r->getMethods();
        }

        protected function _getPropertyComment($p) {

            $r = new \ReflectionProperty($this->_class, $p);
            return $r->getDocComment();
        }

        protected function _getMethodComment($m) {

            $r = new \ReflectionMethod($this->_class, $m);
            return $r->getDocComment();
        }

        protected function _parse($c) {

            $meta = [];
            $pattern = '(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)';
            $matches = StringMethods::match($c, $pattern);

            if ($matches != NULL) {

                foreach ($matches as $m) {

                    $p = ArrayMethods::clean(
                                    ArrayMethods::trim(
                                            StringMethods::split($m, '[\s]', 2)
                                    )
                    );

                    $meta[$p[0]] = TRUE;

                    if (sizeof($p) > 1) {

                        $meta[$p[0]] = ArrayMethods::clean(
                                        ArrayMethods::trim(
                                                StringMethods::split($p[1], ',')
                                        )
                        );
                    }
                }
            }

            return $meta;
        }

        public function getClassMeta() {

            if (!isset($_meta['class'])) {

                $comment = $this->_getClassComment();

                if (!empty($c)) {

                    $_meta['class'] = $this->_parse($c);
                } else {

                    $_meta['class'] = NULL;
                }
            }

            return $_meta['class'];
        }

        public function getClassProperties() {

            if (!isset($_properties)) {

                $p = $this->_getClassProperties();

                foreach ($p as $i) {

                    $_properties[] = $i->getName();
                }
            }

            return $_properties;
        }

        public function getClassMethods() {

            if (!isset($_methods)) {

                $m = $this->_getClassMethods();

                foreach ($m as $i) {

                    $_methods[] = $i->getName();
                }
            }

            return $_methods;
        }

        public function getPropertyMeta($p) {

            if (!isset($_meta['properties'][$p])) {

                $c = $this->_getPropertyComment($p);

                if (!empty($c)) {

                    $_meta['properties'][$p] = $this->_parse($c);
                } else {

                    $_meta['properties'][$p] = NULL;
                }
            }

            return $_meta['properties'][$p];
        }
        
        public function getMethodMeta($m) {
            
            if (!isset($_meta['actions'][$m])) {
                
                $c = $this->_getMethodComment($m);
                
                if (!empty($m)) {
                    
                    $_meta['methods'][$m] = $this->_parse($c);
                    
                } else {
                
                    $_meta['methods'][$m] = NULL;
                    
                }
                
            }
            
            return $_meta['methods'][$m];
            
        }
    }

}

