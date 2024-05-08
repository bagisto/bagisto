<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clientes',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'E-mail: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'duplicate-phone'        => 'Telefone: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'email-not-found'        => 'E-mail: \'%s\' não encontrado no sistema.',
                    'invalid-customer-group' => 'O grupo de clientes é inválido ou não suportado',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produtos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'Chave de URL: \'%s\' já foi gerada para um item com o SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Valor inválido para a coluna da família de atributos (a família de atributos não existe?)',
                    'invalid-type'              => 'O tipo de produto é inválido ou não suportado',
                    'sku-not-found'             => 'Produto com o SKU especificado não encontrado',
                    'super-attribute-not-found' => 'Superatributo com código: \'%s\' não encontrado ou não pertence à família de atributos: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title'  => 'Taxas de Imposto',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'O identificador: \'%s\' foi encontrado mais de uma vez no arquivo de importação.',
                    'identifier-not-found' => 'O identificador: \'%s\' não foi encontrado no sistema.',
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
