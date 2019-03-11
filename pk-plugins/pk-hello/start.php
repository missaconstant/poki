<?php

    class Main extends Pluger
    {
        public function __construct()
        {

        }

        public function create($params)
        {
            var_dump($params);
        }

        public function edit($params)
        {

        }

        public function delete($params)
        {
            
        }

        public function read($params)
        {
            return (object) [ "time" => $this->getWastedTime() ];
        }
    }