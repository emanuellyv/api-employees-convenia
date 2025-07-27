<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    public function test_avaliador_deve_encontrar_maior_valor_em_ordem_crescente()
    {
        $leilao = new Leilao('Ford KA 0km');

        $emanuelly = new Usuario('emanuelly');
        $isabelle = new Usuario('Isabelle');

        $leilao->recebeLance(new Lance($emanuelly, 2000));
        $leilao->recebeLance(new Lance($isabelle, 2500));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        self::assertEquals(2500, $maiorValor);
    }

    public function test_avaliador_deve_encontrar_maior_valor_em_ordem_decrescente()
    {
        $leilao = new Leilao('Ford KA 0km');

        $emanuelly = new Usuario('emanuelly');
        $isabelle = new Usuario('Isabelle');

        $leilao->recebeLance(new Lance($isabelle, 2500));
        $leilao->recebeLance(new Lance($emanuelly, 2000));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        self::assertEquals(2500, $maiorValor);
    }

    public function test_avaliador_deve_encontrar_menor_valor_em_ordem_crescente()
    {
        $leilao = new Leilao('Ford KA 0km');

        $emanuelly = new Usuario('emanuelly');
        $isabelle = new Usuario('Isabelle');

        $leilao->recebeLance(new Lance($emanuelly, 2000));
        $leilao->recebeLance(new Lance($isabelle, 2500));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        self::assertEquals(2000, $menorValor);
    }

    public function test_avaliador_deve_encontrar_menor_valor_em_ordem_decrescente()
    {
        $leilao = new Leilao('Ford KA 0km');

        $emanuelly = new Usuario('emanuelly');
        $isabelle = new Usuario('Isabelle');

        $leilao->recebeLance(new Lance($isabelle, 2500));
        $leilao->recebeLance(new Lance($emanuelly, 2000));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        self::assertEquals(2000, $menorValor);
    }

    public function test_avaliador_deve_buscar_tres_maiores_valores()
    {
        $leilao = new Leilao('Ford KA 0km');
        $emanuelly = new Usuario('Emanuelly');
        $isabelle = new Usuario('Isabelle');
        $melissa = new Usuario('Melissa');
        $eryka = new Usuario('Eryka');

        $leilao->recebeLance(new Lance($melissa, 1500));
        $leilao->recebeLance(new Lance($isabelle, 1000));
        $leilao->recebeLance(new Lance($emanuelly, 2000));
        $leilao->recebeLance(new Lance($eryka, 1700));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiores = $leiloeiro->getMaioresLances();
        self::assertCount(3, $maiores);
        self::assertEquals(2000, $maiores[0]->getValor());
        self::assertEquals(1700, $maiores[1]->getValor());
        self::assertEquals(1500, $maiores[2]->getValor());
    }
}