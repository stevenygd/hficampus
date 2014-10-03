<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 各种表单验证配置的集合
 * 
 * @var array
 */
$config = array(
    /*=== Account 控制器 ===*/
    //login方法
    'account/login' => array(
        array(
            'field'   => 'uname',
            'label'   => 'user name',
            'rules'   => 'trim|required|alpha_numeric',
            ),
        array(
            'field'   => 'pw',
            'label'   => 'password',
            'rules'   => 'trim|required|alpha_numeric|xss_clean'
            ),
    ),
    //regi方法
    'account/regi' => array(
        array(
            'field'   =>  'uname', 
            'label'   =>  'user name', 
<<<<<<< HEAD
            'rules'   =>  'trim|required|min_length[5]|max_length[12]|alpha_numeric'
=======
            'rules'   =>  'trim|required|min_length[5]|max_length[12]'
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
        ),
        array(
            'field'   =>  'pw', 
            'label'   =>  'password', 
            'rules'   =>  'required'
        ),  
        array(
            'field'   =>  'email', 
            'label'   =>  'email', 
            'rules'   =>  'required|valid_email'
        ),
        array(
            'field'   =>  'cngname', 
            'label'   =>  'Chinese given name', 
            'rules'   =>  'trim|required'
        ),
        array(
            'field'   =>  'cnfname', 
            'label'   =>  'CHINESE FAMILY NAME', 
            'rules'   =>  'trim|required'
        ),
        array(
            'field'   =>  'id', 
            'label'   =>  'student id', 
            'rules'   =>  'trim|required|alpha_numeric'
        )
    ),
    //lost_name方法
    'account/lost_name' => array(
        array(
            'field'   =>  'cnln', 
            'label'   =>  'Chinese last name', 
            'rules'   =>  'trim|required|numeric_alpha'//@todo
        ),
        array(
            'field'   =>  'cnfn', 
            'label'   =>  'Chinese first name', 
            'rules'   =>  'required'
        ),  
        array(
            'field'   =>  'email', 
            'label'   =>  'Email', 
            'rules'   =>  'required|valid_email'
        ),
        array(
            'field'   =>  'y', 
            'label'   =>  'ENROLLED YEAR', 
            'rules'   =>  'trim|required|numeric'
        )
    ),
	'account/admin/adduser'=>array(
		array(
			'field'   =>  'uname',
			'label'   =>  'user name',
			'rules'   =>  'required'
			),
		array(
			'field'   =>  'pw',
			'label'   =>  'password',
			'rules'   =>  'required'
			),
		array(
			'field'   =>  'email',
			'label'   =>  'security email',
			'rules'   =>  'required'
			),
		array(
			'field'   =>  'type',
			'label'   =>  'user type',
			'rules'   =>  'required'
			),
	),
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */