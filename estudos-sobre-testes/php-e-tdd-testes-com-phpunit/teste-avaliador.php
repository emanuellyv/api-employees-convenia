<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

// Configuração - ARRANGE/GIVEN
$leilao = new Leilao('Ford KA 0km');

$emanuelly = new Usuario('emanuelly');
$isabelle = new Usuario('Isabelle');

$leilao->recebeLance(new Lance($emanuelly, 2000));
$leilao->recebeLance(new Lance($isabelle, 2500));

// Execução - ACT/WHEN
$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

// Verificação - ASSERT/THEN
$valorEsperado = 2500;

if ($valorEsperado == $maiorValor) {
    echo "Teste passou";
} else {
    echo "Teste falhou";
}