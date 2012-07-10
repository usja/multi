<?

/* * *************************************************
 * Выкидывает селект под парент id
 */

class Default_View_Helper_SelectParentIdCategories {

    public function Tree($rows) {
        $children = array(); // children of each ID
        $ids = array();
        $idName = 'id';
        $pidName = 'parent_id';
        // Collect who are children of whom.
        foreach ($rows as $i => $r) {
            $row = & $rows[$i];
            //$d['label'] = $rows[$i]['title'];
            //$row[] = $d;
            $id = $row[$idName];
            if ($id === null) {
                continue;
            }
            $pid = $row[$pidName];
            if ($id == $pid)
                $pid = null;
            $children[$pid][$id] = & $row;

            if (!isset($children[$id]))
                $children[$id] = array();
            $row['pages'] = & $children[$id];
            $ids[$id] = true;
        }
        // Root elements are elements with non-found PIDs.
        $forest = array();
        foreach ($rows as $i => $r) {
            $row = & $rows[$i];
            $id = $row[$idName];
            $pid = $row[$pidName];
            if ($pid == $id)
                $pid = null;
            if (!isset($ids[$pid])) {
                $forest[$row[$idName]] = & $row;
            }
            //unset($row[$idName]); 
            //unset($row[$pidName]);
        }
        //echo '<pre>';
        //print_r($forest);
        return $forest;
    }
    
    

    /*     * ******************************** */
}