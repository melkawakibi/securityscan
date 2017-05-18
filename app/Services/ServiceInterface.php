<?php

namespace App\Services;

interface ServiceInterface{


	public function findAll();


	public function findOneById($var);


	public function findOneByName($var);


	public function numRowByName($var);


}