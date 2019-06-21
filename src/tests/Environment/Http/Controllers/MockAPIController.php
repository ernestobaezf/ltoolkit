<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Http\Controllers;


use ErnestoBaezF\L5CoreToolbox\Http\Controllers\BaseAPIController;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Models\MockModel;

class MockAPIController extends BaseAPIController
{
    /**
     * Get the main entity used in the current controller to get the associated repository
     *
     * @return string
     */
    protected function getEntity(): string
    {
        return MockModel::class;
    }
}
