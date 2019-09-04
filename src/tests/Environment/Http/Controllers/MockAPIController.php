<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Http\Controllers;


use LToolkit\Http\Controllers\BaseAPIController;
use LToolkit\Test\Environment\Models\MockModel;

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
