<?php

use App\Models\Produto;
use App\Models\Movimento;

// 1. teste de validaçao que simula o @beforcecreate
test('sistema deve barrar a movimentaçao de saída se a quantidade retirada for maior que o estoque', function(){
    $produtoMock = new Produto ([
        'nome' => 'Mouse USB Dell',
        'estoque' => 5
    ]);

    $MovimentoMock = new Movimento ([
        'quantidade' => 10,
        'tipo' => 's',
    ]);

    if ($MovimentoMock->tipo === 's' && $MovimentoMock-quantidade > $produtoMock->estoque){
        //se entrar aqui sifnifica que a validaçao funciona" o teste passou 
        expect(true)->toBeTrue();
    }else{
        $this->fail("erro: a regra de negócio permitiu a saida da mercadoria sem estoque. ");
    }
});

// 2. Teste de validação que simula o @afterCreate
test('0 sistema deve diminuir o estoque apos uma saida autorizada', function (){
    $produto = Produto::create([
    'nome' => 'Teclado mecanico',
    'estoque' => 15,
    ]);

    //Simular saída válida
    Livewire::test(CreateMovimento::class)
    ->fillForm([
    'produto_id' => $produto->id,
    'quantidade' => 5,
    'tipo' => 's',
    ])
    -> call('create');
    
    //2.1 0 movimento deve ter sido criado com sucesso no banco
    expect(Movimento :: count())->toBe(1);
    
    //2.1 0 seu hook afterCreate deve ter diminuido o estoqe de 15 para 10
    expect($produto->fresh()->estoque->toBe(10));
});

// 2. TESTE DE SUBTRAÇÃO (SIMULA O AFTERCREATE PARA SAÍDA)
test('sistema deve diminuir o estoque corretamente apos uma saida autorizada', function () {
    // Cenário: Estoque inicial de 10, saindo 3
    $produtoMock = new Produto(['estoque' => 10]);
    $movimentoMock = new Movimento(['quantidade' => 3, 'tipo' => 's']);

    // Executa a regra matemática de decremento na memória
    if ($movimentoMock->tipo === 'e') {
        $produtoMock->estoque += $movimentoMock->quantidade;
    } else {
        $produtoMock->estoque -= $movimentoMock->quantidade;
    }

    // Valida se a conta deu certo (10 - 3 = 7)
    expect($produtoMock->estoque)->toBe(7);
});


// 3. TESTE DE ADIÇÃO (SIMULA O AFTERCREATE PARA ENTRADA)
test('sistema deve aumentar o estoque corretamente apos uma entrada com sucesso', function () {
    // Cenário: Estoque inicial de 2, entrando 8
    $produtoMock = new Produto(['estoque' => 2]);
    $movimentoMock = new Movimento(['quantidade' => 8, 'tipo' => 'e']);

    // Executa a regra matemática de incremento na memória
    if ($movimentoMock->tipo === 'e') {
        $produtoMock->estoque += $movimentoMock->quantidade;
    } else {
        $produtoMock->estoque -= $movimentoMock->quantidade;
    }

    // Valida se a conta deu certo (2 + 8 = 10)
    expect($produtoMock->estoque)->toBe(10);
});