<?php

namespace Tests\Unit\Services\TextProcess\Handlers;

use App\Services\TextProcess\Handlers\GetLinksFromHtml;
use App\Services\TextProcess\TextPayload;
use Tests\TestCase;

class GetLinksFromHtmlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_links(): void
    {
        $html = '
<p><a href="https://www.php-fig.org/psr/psr-1/">PSR-1 (PHP Standard Recommendation)</a> — это стандарт, который определяет базовые принципы написания кода для  ▶
<h4>Правила PSR-1:</h4>
<p><strong>1. Файлы и пространства имён:</strong></p>
<ul>
<li>🔸Использовать только теги «<code>?php</code>» и «<code>?=</code>».</li>
<li>🔸Код должен быть записан в <a href="/notes/45">UTF-8</a>без BOM (<em>Byte Order Mark</em>).</li>
<li>🔸Файлы должны либо объявлять символы (классы, функции, константы), либо вызывать побочные эффекты (например, вывод на экран, изменение настроек, подключени ▶
    </ul>
' ;

        $payload = new TextPayload($html);
        $result = (new GetLinksFromHtml())->handle($payload);

        $this->assertCount(2, $result->links);

        $this->assertEquals('https://www.php-fig.org/psr/psr-1/', $result->links[0]['link']);
        $this->assertEquals('PSR-1 (PHP Standard Recommendation)', $result->links[0]['name']);
        $this->assertTrue($result->links[0]['is_external']);
        $this->assertNull($result->links[0]['to_id']);
        $this->assertEmpty($result->links[0]['attributes']);

        $this->assertEquals('/notes/45', $result->links[1]['link']);
        $this->assertEquals('UTF-8', $result->links[1]['name']);
        $this->assertFalse($result->links[1]['is_external']);
        $this->assertEquals('45', $result->links[1]['to_id']);
        $this->assertEquals($result->links[1]['attributes'], [
            "class" => "inner_link"
        ]);
    }

    public function test_trim_links_and_name(): void
    {
        $html = '
<p><a href=" https://www.php-fig.org/psr/psr-1/ "> PSR-1 (PHP Standard Recommendation) </a> — это стандарт, который определяет базовые принципы написания кода для  ▶
' ;

        $payload = new TextPayload($html);
        $result = (new GetLinksFromHtml())->handle($payload);

        $this->assertCount(1, $result->links);

        $this->assertEquals('https://www.php-fig.org/psr/psr-1/', $result->links[0]['link']);
        $this->assertEquals('PSR-1 (PHP Standard Recommendation)', $result->links[0]['name']);
    }

    public function test_trim_links_and_name_as_cyrillic(): void
    {
        $html = '
<p><a href=" https://www.php-fig.org/psr/psr-1/ "> Текст ссылки </a> — это стандарт, который определяет базовые принципы написания кода для  ▶
' ;

        $payload = new TextPayload($html);
        $result = (new GetLinksFromHtml())->handle($payload);

        $this->assertCount(1, $result->links);

        $this->assertEquals('https://www.php-fig.org/psr/psr-1/', $result->links[0]['link']);
        $this->assertEquals('Текст ссылки', $result->links[0]['name']);
    }

    public function test_no_links(): void
    {
        $html = '
<p>PSR-1 (PHP Standard Recommendation)— это стандарт, который определяет базовые принципы написания кода для  ▶
<h4>Правила PSR-1:</h4>
' ;

        $payload = new TextPayload($html);
        $result = (new GetLinksFromHtml())->handle($payload);

        $this->assertEmpty($result->links);
    }
}