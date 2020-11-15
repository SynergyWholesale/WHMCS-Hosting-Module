<?php

namespace SynergyWholesale\WHMCS\Test;

use PHPUnit\Framework\TestCase;

/**
 * Synergy Wholesale Registrar Module Test
 *
 * PHPUnit test that asserts the fundamental requirements of a WHMCS
 * registrar module.
 *
 * Custom module tests are added in addtion.
 *
 * @copyright Copyright (c) Synergy Wholesale Pty Ltd 2020
 * @license https://github.com/synergywholesale/whmcs-domains-module/LICENSE
 */

class SynergyWholesaleDomainModuleTest extends TestCase
{
    public static function providerCoreFunctionNames()
    {
        return [
            ['CreateAccount'],
            ['SuspendAccount'],
            ['UnsuspendAccount'],
            ['TerminateAccount'],
            ['ChangePackage'],
            ['ChangePassword'],
            ['AdminCustomButtonArray'],
            ['ConfigOptions'],
            ['MetaData'],
            // Extra methods
            ['ClientArea'],
            ['LoginLink'],
            ['ClientAreaCustomButtonArray'],
            ['UsageUpdate']
        ];
    }

    /**
     * Test Core Module Functions Exist
     *
     * This test confirms that the functions recommended by WHMCS (and more)
     * are defined in this module.
     *
     * @param $method
     *
     * @dataProvider providerCoreFunctionNames
     */
    public function testCoreModuleFunctionsExist($method)
    {
        $this->assertTrue(function_exists('synergywholesale_hosting' . '_' . $method));
    }
}
