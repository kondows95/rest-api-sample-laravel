<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{
    const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
    
    use CreatesApplication;
    
    protected function setUp(): void
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        $this->disableExceptionHandling();
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}

            public function report(\Exception $e)
            {
                // no-op
            }

            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }
    
    protected static function toMySqlDateFromTime($time)
    {
        return date(self::MYSQL_DATE_FORMAT,$time);
    }
    
    protected static function toMySqlDateFromJson($strJsonDate)
    {
        return self::toMySqlDateFromTime(strtotime($strJsonDate));
    }
    
}
