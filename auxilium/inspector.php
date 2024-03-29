<?php
/**
 * Created by PhpStorm.
 * User: IngeniumIQ
 * Date: 11/21/2014
 * Time: 11:40 PM
 */

namespace Auxilium {

    use Auxilium\ArrayMethods as ArraMethods;
    use Auxilium\StringMethods as StringMethods;

    /**
     * Class Inspector
     * @package Auxilium
     */
    class Inspector
    {

        /**
         * @var
         */
        protected $_class;

        /**
         * @var array
         */
        protected $meta = array(
            "class" => array(),
            "properties" => array(),
            "methods" => array()
        );

        /**
         * @var array
         */
        protected $_properties = array();

        /**
         * @var array
         */
        protected $_methods = array();

        /**
         * @param $class
         */
        public function __construct($class)
        {

            $this->_class = $class;

        }

        /**
         * @return string
         */
        protected function _getClassComment()
        {

            $reflection = new \ReflectionClass($this->_class);
            return $reflection->getDocComment();

        }

        /**
         * @return \ReflectionProperty[]
         */
        protected function _getClassProperties()
        {

            $reflection = new \ReflectionClass($this->_class);
            return $reflection->getProperties();

        }

        /**
         * @return \ReflectionMethod[]
         */
        protected function _getClassMethods()
        {

            $reflection = new \ReflectionClass($this->_class);
            return $reflection->getMethods();

        }

        /**
         * @param $property
         * @return string
         */
        protected function _getPropertyComment($property)
        {

            $reflection = new \ReflectionProperty($this->_class, $property);
            return $reflection->getDocComment();

        }

        /**
         * @param $method
         * @return string
         */
        protected function _getMethodComment($method)
        {

            $reflection = new \ReflectionMethod($this->_class, $method);
            return $reflection->getDocComment();

        }

        /**
         * @param $comment
         * @return array
         */
        protected function _parse($comment)
        {

            $meta = array();
            $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
            $matches = StringMethods::match($comment, $pattern);

            if ($matches != null) {

                foreach ($matches as $match) {

                    $parts = ArraMethods::clean(
                        ArraMethods::trim(
                            StringMethods::split($match, "[\s]", 2)
                        )
                    );

                    $meta[$parts[0]] = true;

                    if (sizeof($parts) > 1)
                    {

                        $meta[$parts[0]] = ArraMethods::clean(
                            ArraMethods::trim(
                                StringMethods::split($parts[1], ",")
                            )
                        );

                    }

                }

            }

            return $meta;

        }

        /**
         * @return array|null
         */
        public function getClassMeta()
        {

            if (!isset($_meta["class"]))
            {

                $comment = $this->_getClassComment();

                if (!empty($comment))
                {

                    $_meta["class"] = $this->_parse($comment);

                } else
                {

                    $_meta["class"] = null;

                }

            }

            return $_meta["class"];

        }

    }

}