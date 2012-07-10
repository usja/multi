<?

/* * *************************************************
 * Выкидывает селект под парент id
 */

class Admin_View_Helper_SelectParentId {

    public $puk = array();
    public $depth = 0;

    /*     * ***************************************** */

    public function Show($arr) {
        if (is_array($arr)) {
            $this->PaintMyComments($arr, 0, 0);
        }

        return $this->puk;
    }

    public function PaintMyComments(array $items, $parentId, $depth) {
        foreach ($items[$parentId] as $item) {
            $curId = $item->id;

            $t = '';
            if ($this->depth > 0) {
                $i = 1;
                while ($i <= $this->depth) {
                    $i++;
                    $t .= '→';
                }
            } else {
                $t = '';
            }
            $this->puk[] = array('id'    => $item->id,
                'title' => $t . $item->title);

            $this->depth++;
            if (!empty($items[$curId])) {
                $this->PaintMyComments($items, $curId, $depth);
            }
            $this->depth--;
        }
    }

    /*     * ************************************************ */

    public function VieaPages($arr) {
        if (is_array($arr)) {
            self::PaintMyPages($arr, 0, '');
        }
    }

    public function PaintMyPages(array $items, $parentId, $parent_url) {

        foreach ($items[$parentId] as $item) {
            $curId = $item->id;

            if ($parent_url) {
                $parent_url = str_replace('/', '', $parent_url);
                $parent_url.='/';
            }
            if ($item->parent_id === 0) {
                $parent_url = "";
            }

            $data = array(
                'id'           => $item->id,
                'visible'      => $item->visible,
                'title'        => $item->title,
                'url'          => $item->url,
                'parent_url'   => '',
                'pic'          => $item->pic,
                'external_url' => $item->external_url,
                'isindex'      => $item->isindex
            );
            echo '<ul>';

            if (!$parent_url) {
                $parent_url = $item->url;
            }

            echo $this->partial('pages/pagesLoop.phtml', $data);

            if (!empty($items[$curId])) {
                self::PaintMyPages($items, $curId, $parent_url);
            }

            echo '</li></ul>';
        }
    }

    /*     * ******************************** */
}