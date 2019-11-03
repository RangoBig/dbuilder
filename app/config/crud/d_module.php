<?php
/**
 * GModule管理模块的配置文件，手工编写的，不可删除。
 * User: lvyahui
 * Date: 2016/5/12
 * Time: 21:27
 */

return array(
    'data_source'   =>  'core',
    'table'         =>  'd_module',
    'fields' => array(
        'id'        => array(),
        'name'      => array(
            'label' => 'ModuleKey',
            'form'  => array(
                'rule' => 'required',
            ),
            'list'  => array(
                'search' => array(),
                'sort'   => false,
            ),
        ),
        'title'     => array(
            'label' => '名称',
            'form' => array(
                'rule' => 'required',
            )
        ),
        'note'      => array(
            'label' => '说明',
        ),
        'db_source' => array(
            'label' => '数据源',
            'form'  => array(
                'type'    => 'select',
                'options'   =>  'dataSources',
                'rule' => 'required',
            ),
            'list'  =>  array(
                'search'    =>  false,
            )
        ),
        'db_table'  =>  array(
            'label' =>  'Module 主表',
            'form'  =>  array(
                'type'  =>  'select',
                'options'   =>  array(),
                'rule' => 'required',
            ),
            'list'  =>  array(
                'search'    =>  false,
            )
        ),
        'db_table_key'  =>  array(
            'label' =>  'Module主表主键',
            'form'  =>  array(
                'show'  =>  false,
                'value' =>  'id',
                'rule' => 'required',
            )
        )
    ),

    'form_options' => array(
        'layout' => array(
            'cols'       => 7,
            'label_cols' => 4,
            'input_cols' => 8,
        ),
    ),
    'list_options' => array(
    ),

);