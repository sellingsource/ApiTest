<?php

/**
 * Created by PhpStorm.
 * User: tpack
 * Date: 12/11/14
 * Time: 11:59 AM
 */
class TestMeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests parseSocialSecurityNumber()
     */
    public function testParseSocialSecurityNumber()
    {
        // GOOD INPUT
        $goodIn = array("123456789\n", "987.65.4321", '000-00-0000', '111-22-3333');
        //GOOD OUTPUT
        $goodOut = array(
            array('123', '45', '6789'),
            array('987', '65', '4321'),
            array('000', '00', '0000'),
            array('111', '22', '3333')
        );
        // BAD INPUT
        $bad = array('1', '2-1', '123-1-1234', 9999, 'bad-in-puts', 2 ^ 64 - 1, null);
        $testObj = new \Api\TestMe();
        $sNum = 0;

        foreach ($goodIn as $input) {
            $result = $testObj->parseSocialSecurityNumber($input);
            $this->assertTrue(is_array($result));
            $this->assertEquals(count($result), 3);
            for ($i = 0; $i < 3; $i++) {
                $this->assertEquals($result[$i], $goodOut[$sNum][$i]);
            }
            $sNum++;
        }

        foreach ($bad as $input) {
            $result = $testObj->parseSocialSecurityNumber($input);
            $this->assertTrue(is_array($result));
            $this->assertEquals(count($result), 0);
        }
    }
}