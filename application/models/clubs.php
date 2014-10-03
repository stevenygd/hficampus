<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clubs
 * 
 * HFICampus的社团模型类
 * 
 * 
 * @package model
 * @author halfcoder
 * @copyright One Technology Team
 * @version $Id$
 * @access public
 */

class Clubs extends SAE_Model {
    public function __construct() {
        parent::__construct();
        $this->_options['tableName'] = 'club';
    }
}

/* End of file clubs.php */
/* Location: ./application/models/clubs.php */