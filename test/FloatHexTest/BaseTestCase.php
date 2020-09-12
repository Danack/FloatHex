<?php

declare(strict_types = 1);

namespace FloatHexTest;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * Allows checking that no code has output characters, or left the output buffer in a bad state.
 *
 */
class BaseTestCase extends TestCase
{
    private $startLevel = null;

    public function setup(): void
    {
        $this->startLevel = ob_get_level();
        ob_start();
    }

    public function teardown(): void
    {
        if ($this->startLevel === null) {
            $this->assertEquals(0, 1, "startLevel was not set, cannot complete teardown");
        }
        $contents = ob_get_contents();
        ob_end_clean();

        $endLevel = ob_get_level();
        $this->assertEquals($endLevel, $this->startLevel, "Mismatched ob_start/ob_end calls....somewhere");
        $this->assertEquals(
            0,
            strlen($contents),
            "Something has directly output to the screen: [".substr($contents, 0, 500)."]"
        );
    }

    public function testPHPUnitApparentlyGetsConfused()
    {
        //Basically despite having:
        //<exclude>*/BaseTestCase.php</exclude>
        //in the phpunit.xml file it still thinks this file is a test class.
        //and then complains about it not having any tests.
        $this->assertTrue(true);
    }


//
//    public function assertNoErrors(ValidationResult $validationResult)
//    {
//        $validationProblems = $validationResult->getValidationProblems();
//
//        $message = '';
//
//        if (count($validationProblems) !== 0) {
//            foreach ($validationProblems as $validationProblem) {
//                $message .= $validationProblem->toString();
//            }
//
//            $this->fail("Unexpected problems: " . $message);
//        }
//    }
}
