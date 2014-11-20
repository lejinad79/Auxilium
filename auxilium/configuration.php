<?php

namespace Auxilium {

    use Auxilium\Base as Base;
    use Auxilium\Configuration as Configuration;
    use Auxilium\Configuration\Exception as Exception;

    class Configuration extends Base {

        protected $_type;
        protected $_options;

        protected function _getExceptionForImplementation($method) {

            return new Exception\Implementation("{$method} method not implemented");
        }

        public function initialize() {

            if ($this->_type) {

                throw new Exception\Argument("Invalid type");
            }

            switch ($this->_type) {

                case "ini": {

                        return new Configuration\Driver\Ini($this->_options);
                        break;
                    }

                default : {

                        throw new Exception\Argument("Invalid type");
                        break;
                    }
            }
        }

    }

}

