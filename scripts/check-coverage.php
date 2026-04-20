<?php

declare(strict_types=1);

if ($argc < 3) {
    fwrite(STDERR, "Uso: php scripts/check-coverage.php <clover.xml> <umbral_porcentaje>\n");
    fwrite(STDERR, "::error::Uso invalido de check-coverage.php\n");
    exit(2);
}

$cloverPath = $argv[1];
$threshold = (float) $argv[2];

if (!is_file($cloverPath)) {
    fwrite(STDERR, "No se encontro el archivo de cobertura: {$cloverPath}\n");
    fwrite(STDERR, "::error::No se encontro el archivo Clover ({$cloverPath})\n");
    exit(2);
}

$xml = simplexml_load_file($cloverPath);
if ($xml === false) {
    fwrite(STDERR, "No se pudo leer el XML de cobertura: {$cloverPath}\n");
    fwrite(STDERR, "::error::No se pudo parsear el XML de cobertura\n");
    exit(2);
}

$metrics = $xml->project->metrics;
if ($metrics === null) {
    fwrite(STDERR, "El archivo Clover no contiene metricas de proyecto.\n");
    fwrite(STDERR, "::error::El Clover no contiene metricas de proyecto\n");
    exit(2);
}

$statements = (int) ($metrics['statements'] ?? 0);
$coveredStatements = (int) ($metrics['coveredstatements'] ?? 0);

if ($statements === 0) {
    fwrite(STDERR, "No hay sentencias instrumentadas para calcular cobertura.\n");
    fwrite(STDERR, "::error::No hay sentencias instrumentadas (statements=0)\n");
    exit(2);
}

$coverage = ($coveredStatements / $statements) * 100;
$coverageRounded = round($coverage, 2);

fwrite(STDOUT, sprintf("Cobertura de lineas global: %.2f%% (umbral: %.2f%%)\n", $coverageRounded, $threshold));

if ($coverage + 1e-9 < $threshold) {
    fwrite(STDERR, sprintf("Fallo: cobertura %.2f%% menor al umbral %.2f%%\n", $coverageRounded, $threshold));
    fwrite(STDERR, sprintf("::error::Cobertura %.2f%% menor al umbral %.2f%%\n", $coverageRounded, $threshold));
    exit(1);
}

fwrite(STDOUT, "OK: cobertura cumple el umbral minimo.\n");
