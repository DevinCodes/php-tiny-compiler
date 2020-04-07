<?php declare(strict_types=1);


namespace TinyCompiler;


class Tokenizer
{
    public function tokenize(string $input) : array
    {
        $current = 0;
        $tokens = [];

        while ($current < mb_strlen($input)) {
            $character = $input[$current];

            if (in_array($character, [ Syntax::PARENTHESIS_OPEN, Syntax::PARENTHESIS_CLOSE ], true)) {
                $tokens[] = [
                    'type'  => 'parenthesis',
                    'value' => $character,
                ];

                $current++;
                continue;
            }

            if (\ctype_space($character)) {
                $current++;
                continue;
            }

            if (is_numeric($character)) {
                $value = '';

                while (is_numeric($character)) {
                    $value .= $character;
                    $current++;
                    $character = $input[$current];
                }

                $tokens[] = [
                    'type'  => 'number',
                    'value' => $value
                ];

                continue;
            }

            if ($character === Syntax::QUOTE_OPEN) {
                $value = '';
                $current++;
                $character = $input[$current];
                while ($character !== Syntax::QUOTE_CLOSE) {
                    $value .= $character;
                    $current++;
                    $character = $input[$current];
                }

                $current++; // Skip the closing quote

                $tokens[] = [
                    'type'  => 'string',
                    'value' => $value,
                ];

                continue;
            }

            if (\ctype_alpha($character)) {
                $value = '';

                while (\ctype_alpha($character)) {
                    $value .= $character;
                    $current++;
                    $character = $input[$current];
                }

                $tokens[] = [
                    'type'  => 'name',
                    'value' => $value
                ];

                continue;
            }

            throw new \Exception(sprintf("Syntax error: unexpected %s", $character));

        }

        return $tokens;
    }
}