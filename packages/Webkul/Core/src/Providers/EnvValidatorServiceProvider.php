<?php

namespace Webkul\Core\Providers;

use Dotenv\Exception\InvalidFileException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

class EnvValidatorServiceProvider extends ServiceProvider
{
    /**
     * Set environment variable rules.
     *
     * @var array
     */
    protected $rules = [
        'DB_PREFIX' => 'not_regex:/[^A-Za-z0-9_]/',
    ];

    /**
     * Set environment variable error messages.
     *
     * @var array
     */
    protected $messages = [
        'not_regex' => 'DB_PREFIX ENV is not valid.',
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->validateEnvVariables();
    }

    /**
     * Validate environment variables.
     *
     * @return void
     */
    private function validateEnvVariables()
    {
        $validator = Validator::make($_ENV, $this->rules, $this->messages);

        if ($validator->fails()) {
            $errorKey = collect($validator->errors()->keys())->first();
            $errorValue = env($errorKey);

            $this->writeErrorAndDie(new InvalidFileException(
                $this->getErrorMessage('some invalid values', $errorValue)
            ));
        }
    }

    /**
     * Generate a friendly error message.
     *
     * @param  string  $cause
     * @param  string  $subject
     * @return string
     */
    private function getErrorMessage($cause, $subject)
    {
        return sprintf(
            'Failed to parse dotenv file due to %s. Failed at [%s].',
            $cause,
            strtok($subject, "\n")
        );
    }

    /**
     * Write the error information to the screen and exit.
     *
     * @return void
     */
    private function writeErrorAndDie(InvalidFileException $e)
    {
        $output = (new ConsoleOutput)->getErrorOutput();

        $output->writeln('The environment file is invalid!');
        $output->writeln($e->getMessage());

        exit(1);
    }
}
