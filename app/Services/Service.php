<?php

namespace App\Services;

interface Service
{

	public static function store($object);

	public static function findAll();

	public static function findOneById($id);

	public static function numRow($var);	

}