<?php declare(strict_types=1);


namespace TinyCompiler;

class Compiler
{
    protected Tokenizer $tokenizer;
    protected Parser $parser;
    protected CodeGenerator $codeGenerator;
    protected Transformer $transformer;

    public function __construct()
    {
        $this->setTokenizer(new Tokenizer());
        $this->setParser(new Parser());
        $this->setCodeGenerator(new CodeGenerator());
        $this->setTransformer(
            new Transformer(
                new Traverser()
            )
        );
    }

    public function compile(string $content) : string
    {
        return $this->codeGenerator->generate(
            $this->transformer->transform(
                $this->parser->parse(
                    $this->tokenizer->tokenize($content)
                )
            )
        );
    }

    public function setTokenizer(Tokenizer $tokenizer): void
    {
        $this->tokenizer = $tokenizer;
    }

    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    public function setCodeGenerator(CodeGenerator $codeGenerator): void
    {
        $this->codeGenerator = $codeGenerator;
    }

    public function setTransformer(Transformer $transformer): void
    {
        $this->transformer = $transformer;
    }
}
