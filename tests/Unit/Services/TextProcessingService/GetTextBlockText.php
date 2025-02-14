<?php

namespace Tests\Unit\Services\TextProcessingService;

use App\Services\Notes\TextProcessingService;
use Tests\TestCase;

class GetTextBlockText extends TestCase
{
    protected TextProcessingService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(TextProcessingService::class);
    }

    public function test_get_text_block(): void
    {
        $textOrigin = '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>
<pre><code>{{ php }}
if(count($dto-&gt;tags) &gt; 0){
    $model-&gt;tags()-&gt;sync($dto-&gt;tags);

    $model-&gt;tags-&gt;increaseWeights();
}</code></pre><p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p><p></p>
<pre><code>{{ css }}
.tiptap-toolbar {
  display: flex;
  gap: 8px;
  background-color: #f9f9f9;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}</code></pre><p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p>';

        $result = $this->service->getTextBlocks($textOrigin);

        $expectedBlocks = [
            [
                "type" => "text",
                "language" => "text/html",
                "content" => '<h3 id="avtomaticeskie-analizatory-koda-php">Автоматические анализаторы кода PHP</h3><p>Автоматические анализаторы кода
    PHP представляют собой инструменты, которые помогают разработчикам обнаруживать потенциальные проблемы, ошибки и
    несоответствия стандартам кодирования в их PHP-проектах</p><p><code>const toggleItalic</code></p><p></p>
',
            ],
            [
                "type" => "code",
                "language" => "php",
                "content" => 'if(count($dto->tags) > 0){
    $model->tags()->sync($dto->tags);

    $model->tags->increaseWeights();
}'
            ],
            [
                "type" => "text",
                "language" => "text/html",
                "content" => '<p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p><p></p>
'
            ],
            [
                "type" => "code",
                "language" => "css",
                "content" => '.tiptap-toolbar {
  display: flex;
  gap: 8px;
  background-color: #f9f9f9;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}'
            ],
            [
                "type" => "text",
                "language" => "text/html",
                "content" => '<p></p><p>Did we mention that you have full control over the rendering of the editor? Here is a barebones
    example without any styling, but packed with a whole set of common extensions.</p>'
            ]
        ];

        $this->assertEquals($result[0], $expectedBlocks[0]);
        $this->assertEquals($result[1], $expectedBlocks[1]);
        $this->assertEquals($result[2], $expectedBlocks[2]);
        $this->assertEquals($result[3], $expectedBlocks[3]);
        $this->assertEquals($result[4], $expectedBlocks[4]);
    }

    public function test_add_anchors_to_text_language_ru(): void
    {
        $textOrigin = "какой-то текст <h2>заголовок 1</h2> продолжение текста <h2>заголовок 2</h2>";

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = 'какой-то текст <h2 id="zagolovok-1">заголовок 1</h2> продолжение текста <h2 id="zagolovok-2">заголовок 2</h2>';

        $expectedAnchors = [
            ['tag' => 'h2', 'id' => 'zagolovok-1', 'content' => 'заголовок 1'],
            ['tag' => 'h2', 'id' => 'zagolovok-2', 'content' => 'заголовок 2'],
        ];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
    }

    public function test_ignore_inner_tags(): void
    {
        $textOrigin = "some text <h2><b><i>nav 1</i></b></h2> another text";

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = 'some text <h2 id="nav-1"><b><i>nav 1</i></b></h2> another text';

        $expectedAnchors = [
            ['tag' => 'h2', 'id' => 'nav-1', 'content' => 'nav 1'],
        ];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
    }

    public function test_ignore_h1(): void
    {
        $textOrigin = "some text <h1>nav 1</h1> another text";

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = "some text <h1>nav 1</h1> another text";

        $expectedAnchors = [];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
    }

    public function test_only_text(): void
    {
        $textOrigin = "<p>some text another text</p>";

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = "<p>some text another text</p>";

        $expectedAnchors = [];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
    }

    public function test_text_as_null(): void
    {
        $textOrigin = null;

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = null;

        $expectedAnchors = [];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
    }
}