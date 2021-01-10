<?php namespace NodCMS\Installer\Config;

class View extends \Config\View
{
	/**
         * NodCMS variable!
         * Path of view file. This path will attached before view files with
         * using Base::viewRender()
         *
         * @var string
     */
    public $viewPath = 'NodCMS\Installer';

    /**
         * NodCMS variable!
         * The frame view file name. This is because of different frames such
         * as: backend, frontend, membership, etc.
         *
         * @var string
     */
    public $frameFile = 'layout';
}
