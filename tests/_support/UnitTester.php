<?php

use Codeception\Stub;

/**
 * Inherited Methods
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
    * Define custom actions here
    */

    /**
     * execute any function of a class (also private/protected) and return its return
     *
     * @param string|object $className name of the class (FQCN) or an instance of it
     * @param string        $functionName name of the function which will be executed
     * @param array         $methodParams params the function will be executed with
     * @param array         $constructParams params which will be called in constructor. Will be ignored if $className
     *     is already an instance of an object.
     * @param array         $mocks mock/stub overrides of methods and properties. Will be ignored if $className is
     *     already an instance of an object.
     *
     * @return mixed
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
        $I->comment('I execute function "'
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
