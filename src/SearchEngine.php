<?php

/**
 * Criando a classe responsável por buscar cursos nas plataformas e exibir
 */

namespace App\BuscadorCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class SearchEngine
{
    /**
     * @var ClientInterface
     * Criando a variável que vai retornar os dados acessados pela requisição
     */
    private ClientInterface $clientHttp;

    /**
     * @var Crawler
     * Criando a variável que vai pegar os dados do DOM da plataforma e retornar em string para nossa aplicação
     */
    private Crawler $crawler;

    /**
     * Criando o construtor da classe
     */
    public function __construct(ClientInterface $clientHttp, Crawler $crawler)
    {
        $this->clientHttp = $clientHttp;
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * Criando o método que vai servir para buscar os cursos na plataforma
     */
    public function search(string $url): array
    {
        $response = $this->clientHttp->request('GET', $url);
        $html = $response->getBody();

        $this->crawler->addHtmlContent($html);

        $coursesElements = $this->crawler->filter('span.card-curso__nome');
        $courses = [];

        foreach ($coursesElements as $courseElement) {
            $courses[] = $courseElement->textContent;
        }

        return $courses;
    }
}
