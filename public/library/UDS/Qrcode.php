<?
class UDS_Qrcode extends  Zend_Controller_Plugin_Abstract
{
    protected $template = '<img src="%s" alt="QR" />';
 
    /**
     * Constructor.
     *
     * @return Amnuts_View_Helper_QrCode
     */
    public function Qrcode($template = null)
    {
        if (null !== $template) {
            $this->template = $template;
        }
        return $this;
    }
 
    /**
     * Generate the QR code image via Google's Chart API.
     *
     * @param  array  $params
     * @return string
     */
    public function google($params = array())
    {
        $default = array(
            'text'       => $_SERVER['SCRIPT_URI'],
            'size'       => '100x100',
            'correction' => 'M',
            'margin'     => 0
        );
        $params = array_merge($default, $params);
 
        $params['text']   = urlencode($params['text']);
        $params['margin'] = (int)$params['margin'];
        if (!in_array($params['correction'], array('L', 'M', 'Q', 'H'))) {
            $params['correction'] = 'M';
        }
        if (!preg_match('/^\d+x\d+$/', $params['size'])) {
            $params['size'] = '100x100';
        }
 
        $url = "http://chart.apis.google.com/chart?cht=qr&chl={$params['text']}"
             . "&chld={$params['correction']}|{$params['margin']}"
             . "&chs={$params['size']}";
        return sprintf($this->template, $url);
    }
}