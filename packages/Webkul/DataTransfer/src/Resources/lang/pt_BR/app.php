<?php

return [
    'importers'  => [
        'products'  => [
            'title'      => 'Produtos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'        => 'Chave de URL: \'%s\' já foi gerada para um item com o SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Valor inválido para a coluna da família de atributos (a família de atributos não existe?)',
                    'invalid-type'             => 'O tipo de produto é inválido ou não suportado',
                    'sku-not-found'            => 'Produto com o SKU especificado não encontrado',
                ],
            ],
        ],

        'customers' => [
            'title'      => 'Clientes',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'E-mail: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'duplicate-phone'        => 'Telefone: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'invalid-customer-group' => 'O grupo de clientes é inválido ou não suportado',
                    'email-not-found'        => 'E-mail: \'%s\' não encontrado no sistema.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'As colunas número "%s" têm cabeçalhos vazios.',
            'column-name-invalid'  => 'Nomes de colunas inválidos: "%s".',
            'column-not-found'     => 'Colunas obrigatórias não encontradas: %s.',
            'column-numbers'       => 'O número de colunas não corresponde ao número de linhas no cabeçalho.',
            'invalid-attribute'    => 'O cabeçalho contém atributos inválidos: "%s".',
            'system'               => 'Ocorreu um erro inesperado do sistema.',
            'wrong-quotes'         => 'Aspas curvas usadas em vez de aspas simples.',
        ],
    ],
];
