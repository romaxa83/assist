<?php

namespace Tests\Unit\Services\TextProcessingService;

use App\Services\Notes\TextProcessingService;
use Tests\TestCase;

class AddAnchorsToTextTest extends TestCase
{
    protected TextProcessingService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(TextProcessingService::class);
    }

    public function test_add_anchors_to_text(): void
    {
        $textOrigin = "some text <h2>nav 1</h2> another text <h2>nav 2</h2>  some text <h3>nav 3</h3> another text <h2>nav 4</h2>";

        $result = $this->service->addAnchorsToText($textOrigin);

        $expectedText = 'some text <h2 id="nav-1">nav 1</h2> another text <h2 id="nav-2">nav 2</h2>  some text <h3 id="nav-3">nav 3</h3> another text <h2 id="nav-4">nav 4</h2>';

        $expectedAnchors = [
            ['tag' => 'h2', 'id' => 'nav-1', 'content' => 'nav 1'],
            ['tag' => 'h2', 'id' => 'nav-2', 'content' => 'nav 2'],
            ['tag' => 'h3', 'id' => 'nav-3', 'content' => 'nav 3'],
            ['tag' => 'h2', 'id' => 'nav-4', 'content' => 'nav 4'],
        ];

        $this->assertEquals($expectedText, $result['text']);
        $this->assertEquals($expectedAnchors, $result['anchors']);
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