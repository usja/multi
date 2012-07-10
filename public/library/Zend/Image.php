<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Image
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** @see Zend_Loader_Autoloader */
require_once 'Zend/Loader/Autoloader.php';

/**
 * @category   Zend
 * @package    Zend_Image
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Image
{
    /**
     * Adapter: GD
     */
    const ADAPTER_GD = 'Gd';

    /**
     * Adapter: Imagemick
     */
    const ADAPTER_IMAGEMAGICK = 'ImageMagick';

    /**
     * The Adapter to use
     */
    protected static $_adapterToUse;

    /**
     * Factory class; no constructor
     *
     * @return void
     */
    public function __construct ()
    {
           self::setAdapter(Zend_Image::ADAPTER_IMAGEMAGICK, true);

    }

    /**
     * Set adapter to use
     *
     * @param  mixed $adapters
     * @param  bool $force
     * @return void
     */
    public static function setAdapter ($adapters = null, $force = false)
    {
        $adapters = (array) $adapters;
        if (!$force) {
            $adapters = array_unique(array_merge(
                $adapters,
                array(self::ADAPTER_GD , self::ADAPTER_IMAGEMAGICK)
            ));
        }

        $name = null;
        foreach ($adapters as $adapter) {
            if (Zend_Loader_Autoloader::autoload($adapter)) {
                if (call_user_func($adapter . '::isAvailable')) {
                    $name = $adapter;
                    break;
                }
            } elseif (Zend_Loader_Autoloader::autoload('Zend_Image_Adapter_' . $adapter)) {
                if (call_user_func('Zend_Image_Adapter_' . $adapter . '::isAvailable')) {
                    $name = 'Zend_Image_Adapter_' . $adapter;
                    break;
                }
            } else {
                require_once 'Zend/Image/Exception.php';
                throw new Zend_Image_Exception("Could not find adapter '" . $adapter . "'");
            }
        }

        if ($name) {
            self::$_adapterToUse = $name;
            return $name;
        }

        require_once 'Zend/Image/Exception.php';
	    throw new Zend_Image_Exception('Was not able to detect an available adapter');
    }

    /**
     * Factory: build an adapter
     *
     * @param  string|array|Zend_Config $options
     * @return Zend_Image_Adapter_AdapterInterface
     */
    public static function factory ($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif(!is_array($options)) {
            $options = array('path' => (string) $options);
        }

        if (isset($options['adapters'])) {
            if (isset($options['adapters_force'])) {
                self::setAdapter($options['adapters'], (bool) $options['adapters_force']);
                unset($options['adapters_force']);
            } else {
                self::setAdapter($options['adapters']);
            }

            unset($options['adapters']);
        } elseif (self::$_adapterToUse == null) {
            self::setAdapter();
        }

        return new self::$_adapterToUse($options);
    }
}
