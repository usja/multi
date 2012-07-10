<?php
/*
 *  Хэлпер для отображдения когда последний раз было событие
 *  @param $last_day formay Y-m-d
 *
 */
    class UDS_Captcha extends Zend_Captcha_Image
{
    /**     * Generate image captcha
     *
     * @param string $id Captcha ID
     * @param string $word Captcha word
     */    protected function _generateImage($id, $word)
    {
        // Fallback to GD-based captcha when imagick is not installed
        if (!extension_loaded("imagick")) {
            return parent::_generateImage($id, $word);        }

        $font = $this->getFont();

        if (empty($font)) {            require_once 'Zend/Captcha/Exception.php';
            throw new Zend_Captcha_Exception("Image CAPTCHA requires font");
        }

        $w     = $this->getWidth();        $h     = $this->getHeight();
        $fsize = $this->getFontSize();

        $img_file   = $this->getImgDir() . $id . $this->getSuffix();
         if(empty($this->_startImage)) {
            $img = new Imagick();
            $img->newImage($w, $h, new ImagickPixel('#FFFFFF'), 'png');
        } else {
            $img = new Imagick($this->_startImage);            $w = $img->getImageWidth();
            $h = $img->getImageHeight();
        }

        $text = new ImagickDraw();        $text->setFilLColor('#000000');
        $text->setFont($font);
        $text->setFontSize($h - 10);
        $text->setGravity(Imagick::GRAVITY_CENTER);
        $text->annotation(0, 0, $word);        $img->drawImage($text);

        // generate noise
        $noise = new ImagickDraw();
        $noise->setFilLColor('#000000');        for ($i=0; $i<$this->_dotNoiseLevel; $i++) {
            $x = mt_rand(0,$w);
            $y = mt_rand(0,$h);
            $noise->circle($x, $y, $x+mt_rand(0.3, 1.7), $y+mt_rand(0.3, 1.7));
        }                for($i=0; $i<$this->_lineNoiseLevel; $i++) {
            $noise->line(mt_rand(0,$w), mt_rand(0,$h), mt_rand(0,$w), mt_rand(0,$h));
        }

        $img->waveImage(5, mt_rand(60, 100));        $img->drawImage($noise);
        $img->swirlImage(mt_rand(10, 30));

        file_put_contents($img_file, $img);
        unset($img);    
        
        }
}

