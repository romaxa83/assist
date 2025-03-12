<?php

namespace Tests\Unit\Services\TextProcess\Handlers;

use App\Services\TextProcess\Handlers\GetBlocksFromHtml;
use App\Services\TextProcess\TextPayload;
use Tests\TestCase;

class GetBlocksFromHtmlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_blocks(): void
    {
        $html = '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>
<pre><code class="language-php">
if(count($dto-&gt;tags) &gt; 0){
    $model-&gt;tags()-&gt;sync($dto-&gt;tags);

    $model-&gt;tags-&gt;increaseWeights();
}</code></pre><p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p><p></p>
<pre><code class="language-css">
.tiptap-toolbar {
  display: flex;
  gap: 8px;
  background-color: #f9f9f9;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}</code></pre><p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p>
' ;

        $payload = new TextPayload($html);
        $result = (new GetBlocksFromHtml())->handle($payload);

        $this->assertCount(5, $result->blocks);

        $this->assertEquals($result->blocks[0]['type'], 'text');
        $this->assertEquals($result->blocks[0]['language'], 'html/text');
        $this->assertEquals($result->blocks[0]['content'], '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>');

        $this->assertEquals($result->blocks[1]['type'], 'code');
        $this->assertEquals($result->blocks[1]['language'], 'php');
        $this->assertEquals($result->blocks[1]['content'], 'if(count($dto-&gt;tags) &gt; 0){
    $model-&gt;tags()-&gt;sync($dto-&gt;tags);

    $model-&gt;tags-&gt;increaseWeights();
}');

        $this->assertEquals($result->blocks[2]['type'], 'text');
        $this->assertEquals($result->blocks[2]['language'], 'html/text');
        $this->assertEquals($result->blocks[2]['content'], '<p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p><p></p>');

        $this->assertEquals($result->blocks[3]['type'], 'code');
        $this->assertEquals($result->blocks[3]['language'], 'css');
        $this->assertEquals($result->blocks[3]['content'], '.tiptap-toolbar {
  display: flex;
  gap: 8px;
  background-color: #f9f9f9;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}');

        $this->assertEquals($result->blocks[4]['type'], 'text');
        $this->assertEquals($result->blocks[4]['language'], 'html/text');
        $this->assertEquals($result->blocks[4]['content'], '<p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p>');
    }

    public function test_get_blocks_without_lang(): void
    {
        $html = '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>
<pre><code>
if(count($dto-&gt;tags) &gt; 0){
    $model-&gt;tags()-&gt;sync($dto-&gt;tags);

    $model-&gt;tags-&gt;increaseWeights();
}</code></pre>
' ;

        $payload = new TextPayload($html);
        $result = (new GetBlocksFromHtml())->handle($payload);

        $this->assertCount(2, $result->blocks);

        $this->assertEquals($result->blocks[0]['type'], 'text');
        $this->assertEquals($result->blocks[0]['language'], 'html/text');
        $this->assertEquals($result->blocks[0]['content'], '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>');

        $this->assertEquals($result->blocks[1]['type'], 'code');
        $this->assertEquals($result->blocks[1]['language'], 'plaintext');
        $this->assertEquals($result->blocks[1]['content'], 'if(count($dto-&gt;tags) &gt; 0){
    $model-&gt;tags()-&gt;sync($dto-&gt;tags);

    $model-&gt;tags-&gt;increaseWeights();
}');

    }

    public function test_get_blocks_without_code(): void
    {
        $html = '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>
' ;

        $payload = new TextPayload($html);
        $result = (new GetBlocksFromHtml())->handle($payload);

        $this->assertCount(1, $result->blocks);

        $this->assertEquals($result->blocks[0]['type'], 'text');
        $this->assertEquals($result->blocks[0]['language'], 'html/text');
        $this->assertEquals($result->blocks[0]['content'], '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>');
    }
}