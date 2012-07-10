<?php

/**
 * @see Zend_Image_Driver_Exception
 */
require_once 'Zend/Image/Driver/Exception.php';

/**
 * Abstract class for drivers
 *
 * @category    Zend
 * @package     Zend_Image
 * @subpackage  Zend_Image_Driver
 * @author      Stanislav Seletskiy <s.seletskiy@office.ngs.ru>
 * @author      Leonid A Shagabutdinov <leonid@shagabutdinov.com>
 */
abstract class Zend_Image_Driver_Abstract
{
    /**
     * Save image to filename
     *
     * @throws Zend_Image_Driver_Exception
     * @param string $filename
     * @return bool
     */
    public abstract function save( $filename );


    /**
     * Get image contents
     *
     * @throws Zend_Image_Driver_Exception
     * @return string
     */
    public abstract function getBinary();


    /**
     * Get image size as array(width, height)
     *
     * @throws Zend_Image_Driver_Exception
     * @return array Format: array(width, height)
     */
    public abstract function getSize();


    /**
     * File to load image from
     *
     * @throws Zend_Image_Driver_Exception
     * @param string $fileName
     */
    public function load( $fileName )
    {
        $this->_type = $this->_getFileType( $fileName );
    }


    /**
     *
     * @throws Zend_Image_Driver_Exception
     * @param string $fileName
     * @return string jpg | png | gif
     */
    protected function _getFileType( $fileName )
    {
        if ( !is_readable( $fileName ) ) {
            throw new Zend_Image_Driver_Exception(
                'Cannot read file "' . $fileName . '"'
            );
        }

        $fileHandle = fopen( $fileName, 'r' );
        $bytes = fread( $fileHandle, 20 );
        fclose( $fileHandle );

        $jpegMatch = "\xff\xd8\xff\xe0";

        if ( mb_strstr( $bytes, $jpegMatch ) ) {
            return 'jpg';
        }

        $jpegMatch = "\xff\xd8\xff\xe1";

        if ( mb_strstr( $bytes, $jpegMatch ) ) {
            return 'jpg';
        }

        $pngMatch = "\x89PNG\x0d\x0a\x1a\x0a";

        if ( mb_strstr( $bytes, $pngMatch ) ) {
            return 'png';
        }

         $gifMatch = "GIF8";

         if ( mb_strstr( $bytes, $gifMatch ) ) {
             return 'gif';
         }

         return false;
    }

    /**
     * Resize image to specified coordinats
     *
     * @throws Zend_Image_Driver_Exception
     * @param int $width
     * @param int $height
     */
    public function resize( $width, $height )
    {
        if ( $width <= 0 ) {
            throw new Zend_Image_Driver_Exception(
                'Width can not be null or negative'
            );
        }

        if ( $height <= 0 ) {
            throw new Zend_Image_Driver_Exception(
                'Height can not be null or negative'
            );
        }
    }
    
        public function jcrop($x, $y, $w, $h, $maxw, $maxh)
    {
        
    }


    /**
     * Crop image to specified coordinats
     *
     * @throws Zend_Image_Driver_Exception
     * @param int $width
     * @param int $height
     * @param int $targetWidth
     * @param int $targetHeight
     */
    public function crop( $left, $top, $targetWidth, $targetHeight )
    {
        if ( $left < 0 ) {
            throw new Zend_Image_Driver_Exception(
                "Trying to crop from ($left, $top). Offset can't " .
                    "been negative."
            );
        }

        if ( $top < 0 ) {
            throw new Zend_Image_Driver_Exception(
                "Trying to crop from ($left, $top). Offset can't " .
                    "been negative."
            );
        }

        list( $sourceWidth, $sourceHeight ) = $this->getSize();

        if ( $top + $targetHeight > $sourceHeight ) {
            throw new Zend_Image_Driver_Exception(
                'Trying to crop to (' . ( $left + $targetWidth ) . ', ' .
                    ( $top + $targetHeight ) . '). Out of bottom bound.'
            );
        }

        if ( $left + $targetWidth > $sourceWidth ) {
            throw new Zend_Image_Driver_Exception(
                'Trying to crop to (' . ( $left + $targetWidth ) . ', ' .
                    ( $top + $targetHeight ) . '). Out of right bound.'
            );
        }

        if ( $targetHeight <= 0 ) {
            throw new Zend_Image_Driver_Exception(
                'Target height can not be 0 or negative'
            );
        }

        if ( $targetWidth <= 0 ) {
            throw new Zend_Image_Driver_Exception(
                'Target width can not be 0 or negative'
            );
        }
    }


    protected function getTypeFileName( $fileName )
    {
        $info = pathinfo( $fileName );

        return $info['extension'];
    }


    /**
     * Check if image was loaded
     *
     * @return bool
     */
    public function isImageLoaded()
    {
        return $this->_imageLoaded;
    }


    /**
     * Get type of current image
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }


    protected $_imageLoaded = false;

    /**
     * Type of current image (jpeg, png or etc.)
     *
     * @var string
     */
    protected $_type = '';
}
