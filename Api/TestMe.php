<?php
namespace Api;

/**
 * Class TestMe
 *
 * Please write PHPUnit Tests to adequately cover the following functions
 *
 * @package Api
 */
class TestMe
{


    /**
     * Parses a social security number and returns the array of values
     *
     * @param $ssn
     * @return mixed
     */
    public function parseSocialSecurityNumber ($ssn)
    {
        preg_grep("/^(\d{3}).?(\d{2}).?(\d{4})$/", $ssn, $values);
        array_shift($values);
        return $values;
    }

} 