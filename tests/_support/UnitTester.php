<?php

use Codeception\Stub;

/**
 * Inherited methods.
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * Execute any function of a class (also private/protected) and return its return.
     *
     * @param  string|object  $className
     * @param  string  $functionName
     * @param  array  $methodParams
     * @param  array  $constructParams
     * @param  array  $mocks
     * @return mixed
     *
     * @throws \Exception
     */
    public function executeFunction(
        $className,
        string $functionName,
        array $methodParams = [],
        array $constructParams = [],
        array $mocks = []
    ) {
        $I = $this;

        $I->comment(
            'I execute function "'
            . $functionName
            . '" of class "'
            . (is_object($className) ? get_class($className) : $className)
            . '" with '
            . count($methodParams)
            . ' method-params, '
            . count($constructParams)
            . ' constuctor-params and '
            . count($mocks)
            . ' mocked class-methods/params'
        );

        $class = new \ReflectionClass($className);

        $method = $class->getMethod($functionName);

        $method->setAccessible(true);

        if (is_object($className)) {
            $reflectedClass = $className;
        } elseif (empty($constructParams)) {
            $reflectedClass = Stub::make($className, $mocks);
        } else {
            $reflectedClass = Stub::construct($className, $constructParams, $mocks);
        }

        return $method->invokeArgs($reflectedClass, $methodParams);
    }
}
