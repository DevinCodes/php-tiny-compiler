<?php declare(strict_types=1);


namespace TinyCompiler;

class CodeGenerator
{
    public function generate(array $node) : string
    {
        switch ($node['type']) {
            case 'Program':
                return implode(PHP_EOL, array_map([$this, 'generate'], $node['body']));

            case 'ExpressionStatement':
                return sprintf('%s;', $this->generate($node['expression']));

            case 'CallExpression':
                return sprintf(
                    '%s(%s)',
                    $this->generate($node['callee']),
                    implode(', ', array_map([$this, 'generate'], $node['arguments']))
                );

            case 'Identifier':
                return $node['name'];

            case 'NumberLiteral':
                return $node['value'];

            case 'StringLiteral':
                return sprintf('"%s"', $node['value']);

            default:
                throw new \TypeError(sprintf('Unkown type: %s', $node['type']));
        }
    }
}
