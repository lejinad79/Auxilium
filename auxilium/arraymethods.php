<?php
/**
 * Created by PhpStorm.
 * User: IngeniumIQ
 * Date: 11/21/2014
 * Time: 10:58 PM
 */

namespace Auxilium {

    /**
     * Class ArrayMethods
     * @package Auxilium
     */
    class ArrayMethods
    {

        /**
         *
         */
        private function __construct()
        {

        }

        /**
         *
         */
        private function __clone()
        {

        }

        /**
         * @param $array
         * @return array
         */
        public static function clean($array)
        {

            return array_filter($array, function($item) {

                return !empty($item);

            });

        }

        /**
         * @param $array
         * @return array
         */
        public static function trim($array)
        {

            return array_map(function($item) {

                return trim($item);

            }, $array);

        }

    }

}