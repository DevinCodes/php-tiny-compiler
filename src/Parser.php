<?php declare(strict_types=1);


namespace TinyCompiler;

class Parser
{
    private array $tokens;
    private int $current;

    public function parse(array $tokens = []) : array
    {
        $this->tokens = $tokens;
        $this->current = 0;

        $tree = [
            'type'  => 'Program',
            'body'  => [],
        ];

        while ($this->current < \count($this->tokens)) {
            $tree['body'][] = $this->walk();
        }

        return $tree;
    }

    private function walk()
    {
        $token = $this->tokens[$this->current];

        if ($token['type'] === 'number') {
            $this->current++;
            return [
                'type'  => 'NumberLiteral',
                'value' => $token['value'],
            ];
        }

        if ($token['type'] === 'string') {
            $this->current++;
            return [
                'type'  => 'StringLiteral',
                'value' => $token['value'],
            ];
        }

        if (
            $token['type'] === 'parenthesis' &&
            $token['value'] === Syntax::PARENTHESIS_OPEN
        ) {
            $this->current++;
            $token = $this->tokens[$this->current];

            $node = [
                'type'      => 'CallExpression',
                'name'      => $token['value'],
                'params'    => [],
            ];

            $this->current++;
            $token = $this->tokens[$this->current];


            while (
                $token['type'] !== 'parenthesis' ||
                (
                    $token['type'] === 'parenthesis' &&
                    $token['value'] !== Syntax::PARENTHESIS_CLOSE
                )
            ) {
                $node['params'][] = $this->walk();
                $token = $this->tokens[$this->current];
            }

            $this->current++;

            return $node;
        }

        throw new \TypeError($token['type']);
    }
}
