<?php declare(strict_types=1);


namespace TinyCompiler;

class Compiler
{
    protected ?Tokenizer $tokenizer = null;
    protected ?Parser $parser = null;
    protected ?CodeGenerator $codeGenerator = null;
    protected ?Transformer $transformer = null;

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

    /**
     * Compile the provided LISP code into C code.
     *
     * @param string $content
     * @return mixed|string
     * @throws \Exception
     */
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

    /**
     * @param Tokenizer|null $tokenizer
     */
    public function setTokenizer(?Tokenizer $tokenizer): void
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @param Parser|null $parser
     */
    public function setParser(?Parser $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * @param CodeGenerator|null $codeGenerator
     */
    public function setCodeGenerator(?CodeGenerator $codeGenerator): void
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param Transformer|null $transformer
     */
    public function setTransformer(?Transformer $transformer): void
    {
        $this->transformer = $transformer;
    }
}
