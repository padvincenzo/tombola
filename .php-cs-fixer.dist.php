<?php

// Configurazione di PHP CS Fixer: docker compose run --rm php-tools php-cs-fixer fix

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(["docker"]);

return (new PhpCsFixer\Config())
    ->setRules(["@PSR12" => true])
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder);
