<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Test\Environment\Http\Controllers;


use ltoolkit\Http\Controllers\BaseAPIController;
use ltoolkit\Test\Environment\Models\MockModel;

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
