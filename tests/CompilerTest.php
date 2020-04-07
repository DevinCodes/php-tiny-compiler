<?php

namespace TinyCompiler\Tests;

use PHPUnit\Framework\TestCase;

final class CompilerTest extends TestCase
{
    protected string $input = '(add 2 (subtract 4 2))';

    protected string $output = 'add(2, subtract(4, 2));';

    protected array $tokens = [
        [ 'type' => 'parenthesis', 'value' => '(' ],
        [ 'type' => 'name', 'value' => 'add' ],
        [ 'type' => 'number', 'value' => '2' ],
        [ 'type' => 'parenthesis', 'value' => '(' ],
        [ 'type' => 'name', 'value' => 'subtract' ],
        [ 'type' => 'number', 'value' => '4' ],
        [ 'type' => 'number', 'value' => '2' ],
        [ 'type' => 'parenthesis', 'value' => ')' ],
        [ 'type' => 'parenthesis', 'value' => ')' ],
    ];

    protected array $abstractSyntaxTree = [
        'type'  => 'Program',
        'body'  => [
            [
                'type'   => 'CallExpression',
                'name'   => 'add',
                'params' => [
                    [
                        'type'  => 'NumberLiteral',
                        'value' => '2'
                    ], [
                        'type'   => 'CallExpression',
                        'name'   => 'subtract',
                        'params' => [
                            [
                                'type'  => 'NumberLiteral',
                                'value' => '4'
                            ], [
                                'type'  => 'NumberLiteral',
                                'value' => '2'
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ];

    protected array $transformedSyntaxTree = [
        'type'  => 'Program',
        'body'  => [
            [
                'type'          => 'ExpressionStatement',
                'expression'    => [
                    'type'   => 'CallExpression',
                    'callee' => [
                        'type'  => 'Identifier',
                        'name'  => 'add'
                    ],
                    'arguments' => [
                        [
                            'type'  => 'NumberLiteral',
                            'value' => '2'
                        ], [
                            'type'   => 'CallExpression',
                            'callee' => [
                                'type'  => 'Identifier',
                                'name'  => 'subtract'
                            ],
                            'arguments' => [
                                [
                                    'type'  => 'NumberLiteral',
                                    'value' => '4'
                                ], [
                                    'type'  => 'NumberLiteral',
                                    'value' => '2'
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];


    public function testTokenizerCreatesCorrectTokens()
    {
        $tokenizer = new \TinyCompiler\Tokenizer();

        $this->assertEquals(
            $this->tokens,
            $tokenizer->tokenize($this->input)
        );
    }

    public function testParserCreatesCorrectAbstractSyntaxTree()
    {
        $parser = new \TinyCompiler\Parser();

        $this->assertEquals(
            $this->abstractSyntaxTree,
            $parser->parse(
                $this->tokens
            )
        );
    }

    public function testTransformerCreatesCorrectSyntaxTree()
    {
        $transformer = new \TinyCompiler\Transformer(
            new \TinyCompiler\Traverser()
        );

        $this->assertEquals(
            $this->transformedSyntaxTree,
            $transformer->transform(
                $this->abstractSyntaxTree
            )
        );
    }


    public function testGeneratorFormatsCodeIntoNewSyntax()
    {
        $generator = new \TinyCompiler\CodeGenerator();

        $this->assertEquals(
            $this->output,
            $generator->generate(
                $this->transformedSyntaxTree
            )
        );
    }

    public function testCompilerTurnsInputIntoOutput()
    {
        $compiler = new \TinyCompiler\Compiler();

        $this->assertEquals(
            $this->output,
            $compiler->compile($this->input)
        );
    }
}
