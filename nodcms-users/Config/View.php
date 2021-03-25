<?php namespace NodCMS\Users\Config;

class View extends \Config\View
{
	/**
         * NodCMS variable!
         * Path of view file. This path will attached before view files with
         *
         * @var string
     */
    public $namespacePieces = 'NodCMS\Users';

    /**
     * NodCMS variable!
     * Path of view namespace for layout view file.
     *
     * @var string
     */
    public $namespaceLayout = 'NodCMS\Users';
}
