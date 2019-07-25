<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment\Http\Controllers;


use l5toolkit\Http\Controllers\BaseAPIController;
use l5toolkit\Test\Environment\Models\MockModel;

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
