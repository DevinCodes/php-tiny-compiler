<?php declare(strict_types=1);


namespace TinyCompiler;


class Transformer
{
    private Traverser $traverser;

    public function __construct(Traverser $traverser)
    {
        $this->traverser = $traverser;

    }

    public function transform(array $tree)
    {
        $newAst = [
            'type' => 'Program',
            'body' => [],
        ];

        $tree['_context'] = &$newAst['body'];

        $this->traverser->traverse($tree, [

        ]);

        $this->traverser->traverse($tree, [
            'NumberLiteral' => [
                'enter' => function($node, &$parent) {
                    $parent['_context'][] = [
                        'type'  => 'NumberLiteral',
                        'value' => $node['value'],
                    ];
                }
            ],
            'StringLiteral' => [
                'enter' => function($node, &$parent) {
                    $parent['_context'][] = [
                        'type'  => 'StringLiteral',
                        'value' => $node['value'],
                    ];
                }
            ],
            'CallExpression' => [
                'enter' => function(&$node, &$parent) {
                    $expression = [
                        'type' => 'CallExpression',
                        'callee' => [
                            'type'  => 'Identifier',
                            'name'  => $node['name']
                        ],
                        'arguments' => [],
                    ];
                    $node['_context'] = &$expression['arguments'];

                    if ($parent['type'] !== 'CallExpression') {
                        $expression = [
                            'type'          => 'ExpressionStatement',
                            'expression'    => $expression
                        ];
                    }

                    $parent['_context'][] = &$expression;
                }
            ],
        ]);

        return $newAst;
    }
}