<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Clientes',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'E-mail: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'duplicate-phone' => 'Telefone: \'%s\' é encontrado mais de uma vez no arquivo de importação.',
                    'email-not-found' => 'E-mail: \'%s\' não encontrado no sistema.',
                    'invalid-customer-group' => 'O grupo de clientes é inválido ou não suportado',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produtos',

            'validation' => [
                'errors' => [
                    'duplicate-url-key' => 'Chave de URL: \'%s\' já foi gerada para um item com o SKU: \'%s\'.',
                    'image-not-file' => 'Imagem: \'%s\' é um endereço web, mas esta importação está configurada para usar imagens de arquivos. Escolha \'Links de imagem no arquivo\' como origem, ou substitua o valor por um nome de arquivo.',
                    'image-not-found' => 'Imagem: \'%s\' não foi encontrada onde esta importação espera suas imagens.',
                    'image-not-url' => 'Imagem: \'%s\' não é um endereço web, mas esta importação está configurada para buscar imagens em links do arquivo. Escolha outra origem de imagens ou substitua o valor por um endereço https:// completo.',
                    'invalid-attribute-family' => 'Valor inválido para a coluna da família de atributos (a família de atributos não existe?)',
                    'invalid-type' => 'O tipo de produto é inválido ou não suportado',
                    'sku-not-found' => 'Produto com o SKU especificado não encontrado',
                    'super-attribute-not-found' => 'Superatributo com código: \'%s\' não encontrado ou não pertence à família de atributos: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Taxas de Imposto',

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
            'column-name-invalid' => 'Nomes de colunas inválidos: "%s".',
            'column-not-found' => 'Colunas obrigatórias não encontradas: %s.',
            'column-numbers' => 'O número de colunas não corresponde ao número de linhas no cabeçalho.',
            'invalid-attribute' => 'O cabeçalho contém atributos inválidos: "%s".',
            'more-issues' => 'e mais :count problema(s) — baixe o relatório completo para ver a lista integral.',
            'more-rows' => '(+:count linhas a mais)',
            'system' => 'Ocorreu um erro inesperado do sistema.',
            'wrong-quotes' => 'Aspas curvas usadas em vez de aspas simples.',
        ],
    ],
];
