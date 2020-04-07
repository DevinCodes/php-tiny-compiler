<?php declare(strict_types=1);


namespace TinyCompiler;


class Traverser
{
    private array $visitor;

    public function traverse($tree, $visitor)
    {
        $this->visitor = $visitor;

        $this->traverseNode($tree, []);
    }

    private function traverseArray($array, $parent)
    {
        foreach ($array as $child) {
            $this->traverseNode($child, $parent);
        }
    }

    private function traverseNode(&$node, $parent)
    {
        $methods = $this->visitor[$node['type']] ?? [];

        if (isset($methods['enter'])) {
            $methods['enter']($node, $parent);
        }

        switch ($node['type']) {
            case 'Program':
                $this->traverseArray($node['body'], $node);
                break;
            case 'CallExpression':
                $this->traverseArray($node['params'], $node);
                break;
            case 'NumberLiteral':
            case 'StringLiteral':
                break;
            default:
                throw new \TypeError(sprintf('unknown node type: %s', $node['type']));
        }

        if (isset($methods['exit'])) {
            $methods['exit']($node, $parent);
        }
    }
}