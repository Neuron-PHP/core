<?php

/**
 * Webhook..
 */

$Dest = 	new \Neuron\Log\Destination\WebHookPost(
	new \Neuron\Log\Format\PlainText()
);

$Dest->open(
	[
		'endpoint' => 'http://example.org/test'
	]
);

$WebLog = new \Neuron\Log\Logger(
	$Dest
);

$WebLog->info( 'test' );

/**
 * Slack..
 */

$Slack = new \Neuron\Log\Destination\Slack(
	new \Neuron\Log\Format\PlainText()
);

$Slack->open(
	[
		'endpoint' => 'http://slackwebhookurl',
		'channel'  => 'general'
	]
);

$SlackLog = new \Neuron\Log\Logger(
	$Slack
);

$SlackLog->info( 'test' );
