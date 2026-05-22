<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=tcc1", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $subtotal = $_SESSION['total_pedido'] ?? 0;
    $itens    = $_SESSION['itens_pedido'] ?? [];

    // Aqui recebe o pagamento, certifique-se que o 'value' no HTML bate com o que estamos checando lá no final
    $pagamento  = $_POST['pagamento']  ?? 'pix';
    $entrega    = $_POST['entrega']    ?? 'entrega';
    $observacao = $_POST['observacao'] ?? '';

    // id do usuário logado
    $id_cliente = $_SESSION['usuario_id'] ?? 0;

    // SE FOR ENTREGA, OBRIGA TER ENDEREÇO CADASTRADO
    if ($entrega === 'entrega' && $id_cliente) {
        $stmtEnd = $pdo->prepare("SELECT endereco FROM usuarios WHERE id = ?");
        $stmtEnd->execute([$id_cliente]);
        $endereco = $stmtEnd->fetchColumn();

        if (!$endereco || trim($endereco) === '') {
            header("Location: confirmacao.php?erro_endereco=1");
            exit;
        }
    }

    $taxa_entrega = ($entrega === 'entrega') ? 3.00 : 0.00;
    $total_final  = $subtotal + $taxa_entrega;

    if (empty($itens)) {
        echo "<script>alert('Nenhum item no pedido.');window.location='index.php';</script>";
        exit;
    }

    // 1) PEDIDO
    $stmt = $pdo->prepare(
        "INSERT INTO pedidos (usuario_id, valor_total, status, observacoes) 
         VALUES (?, ?, 'pendente', ?)"
    );
    $stmt->execute([$id_cliente, $total_final, $observacao]);
    
    $id_pedido = $pdo->lastInsertId();

    // 2) ITENS
    foreach ($itens as $id_produto => $dados) {
        $qtd   = (int)$dados['qtd'];
        $preco = (float)$dados['preco'];

        $stmt = $pdo->prepare(
            "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$id_pedido, $id_produto, $qtd, $preco]);
    }

    // 3) ENTREGA
    $stmt = $pdo->prepare(
        "INSERT INTO entregas (id_pedido, metodo_envio, status_envio, frete) 
         VALUES (?, ?, 'aguardando', ?)"
    );
    $stmt->execute([$id_pedido, $entrega, $taxa_entrega]);

    // 4) PAGAMENTO
    $stmt = $pdo->prepare(
        "INSERT INTO pagamentos (id_pedido, metodo_pagamento, status_pagamento, valor)
         VALUES (?, ?, 'pendente', ?)"
    );
    $stmt->execute([$id_pedido, $pagamento, $total_final]);

    // limpa sessão
    unset($_SESSION['itens_pedido'], $_SESSION['total_pedido']);

    // ==============================================================
    // AQUI ENTRA A LÓGICA DO PASSO 2 (O REDIRECIONAMENTO DO PIX)
    // ==============================================================
    
    // Verificamos se a string de pagamento tem "pix" nela (maiúsculo ou minúsculo)
    // Isso cobre se o value lá no form for 'pix', 'PIX' ou 'PIX (instantâneo)'
    if (strpos(strtolower($pagamento), 'pix') !== false) {
        // Redireciona para a página com a chave PIX e instrução pro WhatsApp
        header("Location: pagamento_pix.php?pedido_id=" . $id_pedido);
        exit;
    } else {
        // Redireciona para a tela de sucesso normal (cartão, etc)
        header("Location: pedido_sucesso.php?id_pedido=" . $id_pedido);
        exit;
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}