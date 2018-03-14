<?php

require __DIR__ . '/vendor/autoload.php';

use Maclof\Kubernetes\Client;
use Maclof\Kubernetes\Models\ConfigMap;

$client = new Client([
	'master'  => 'https://' . $_ENV['KUBERNETES_SERVICE_HOST'] . ':' . $_ENV['KUBERNETES_SERVICE_PORT'],
	'ca_cert' => '/var/run/secrets/kubernetes.io/serviceaccount/ca.crt',
	'token'   => '/var/run/secrets/kubernetes.io/serviceaccount/token',
]);

while (true) {
	$sampleMap = new ConfigMap([
		'metadata' => [
			'name' => 'sample-configmap',
		],
		'data' => [
			'key1' => 'value1',
		],
	]);
	if (!$client->configMaps()->exists($sampleMap->getMetadata('name'))) {
		$client->configMaps()->create($sampleMap);
	}

	$client->configMaps()->update($sampleMap);
	$sampleMap = $client->configMaps()->setFieldSelector([
		'metadata.name' => 'sample-configmap'
	])->first();
	print_r($sampleMap);

	$client->configMaps()->delete($sampleMap);
	sleep(1);
}
