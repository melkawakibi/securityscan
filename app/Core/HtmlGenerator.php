<?php

namespace App\Core;


class HtmlGenerator
{

	private $filename;

	public function __construct()
	{

		$this->createHtmlFile();

	}

	public function createHtmlFile()
	{
		$this->filename = 'rapport';

		if($file = fopen('resources/views/' . $this->filename . '.blade.php', 'w')){

			$this->generateContent($file);

		}

	}

	public function generateContent($file)
	{

		$content = "
		<!doctype html>
			<html>
				<head>
					<title>Rapport</title>
					</head>
					<body>
						<h1>TEST</h1>
						<p>Dit is een test</p>
					</body>
			</html>
			";

		fwrite($file, $content);
		fclose($file);
	}

	public function getFileName()
	{
		return $this->filename;
	}


}