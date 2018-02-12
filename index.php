<?php
/**
 * API that shuffles a list of words
 *
 * This file is a Slim Framework plain implementation of a super simple API that exposes an endpoint
 * that feeds a list of shuffled word for playing Word's Bingo, the idea came up in LINC class
 * while the instructor struggled to shuffle manually the cue cards for the game
 *
 * @author Israel Simmons
 */

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

/**
 * Exposes the /words endpoint, reading a CSV containing words and definitions, shuffles
 * the resulted array and returns a JSON Object
 *
 * @api
 *
 * @author Israel Simmons
 * 
 */
$app->get('/words', function(Request $req, Response $res, $args = []) {

	$gestor = fopen('words.txt', 'r');

	while ($datos = fgetcsv($gestor, 0, ';')){
		$data[] = $datos; 
	}

	fclose($gestor);

	unlink('words.txt');

	shuffle($data);	

	$gestor = fopen('words.txt', 'w');

	foreach ($data as $key => $value) {
		fputcsv($gestor, $value, ';');
	}

	fclose($gestor);

	$newRes = $res->withJson(compact('data'));
	return $newRes;
});

$app->run();