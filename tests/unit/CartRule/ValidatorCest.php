<?php

namespace Tests\Unit\CartRule;

use Codeception\Example;
use UnitTester;
use Webkul\Rule\Helpers\Validator;

class ValidatorCest
{
    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @dataProvider getScenariosForTestValidateArrayValues
     */
    public function testValidateArrayValues(UnitTester $I, Example $scenario): void
    {
        $result = $I->executeFunction(Validator::class, 'validateArrayValues', [
            'attributeValue' => $scenario['inputArray'],
            'conditionValue' => $scenario['conditionValue']
        ]);

        $I->assertEquals($scenario['expectResult'], $result);
    }

    protected function getScenariosForTestValidateArrayValues(): array
    {
        return [
            [
                'inputArray' => [],
                'conditionValue' => '',
                'expectResult' => false,
            ],
            [
                'inputArray' => ['firstDimension' => 'firstValue'],
                'conditionValue' => 'anotherValue',
                'expectResult' => false,
            ],
            [
                'inputArray' => ['firstDimension' => 'firstValue'],
                'conditionValue' => 'firstValue',
                'expectResult' => true,
            ],
            [
                'inputArray' => [
                    'firstDimension' => 'firstValue',
                    'secondDimension' => [
                        'secondKey' => 'secondValue'
                    ]
                ],
                'conditionValue' => 'anotherValue',
                'expectResult' => false,
            ],
            [
                'inputArray' => [
                    'firstDimension' => 'firstValue',
                    'secondDimension' => [
                        'secondKey' => 'secondValue'
                    ]
                ],
                'conditionValue' => 'secondValue',
                'expectResult' => true,
            ],
            [
                'inputArray' => [
                    'firstDimension' => 'firstValue',
                    'secondDimension' => [
                        'secondKey' => 'secondValue',
                        'thirdDimension' => [
                            'thirdKey' => 'thirdValue'
                        ]
                    ]
                ],
                'conditionValue' => 'thirdValue',
                'expectResult' => true,
            ],
            [
                'inputArray' => [
                    'firstDimension' => 'firstValue',
                    'secondDimension' => [
                        'secondKey' => 'secondValue',
                        'thirdDimension' => [
                            'thirdKey' => 'thirdValue'
                        ]
                    ],
                    'secondDimension2' => [
                        'secondKey2' => 'secondValue2',
                        'thirdDimension2' => [
                            'thirdKey2' => 'thirdValue2'
                        ]
                    ]
                ],
                'conditionValue' => 'thirdValue2',
                'expectResult' => true,
            ],
        ];
    }
}