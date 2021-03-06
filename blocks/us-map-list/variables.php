<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
  'key' => 'group_609a3819edfdd',
  'title' => 'Block: US Map List',
  'fields' => array(
    array(
      'key' => 'field_609b41eb86df3',
      'label' => 'Intro Text',
      'name' => 'intro_text',
      'type' => 'wysiwyg',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'tabs' => 'all',
      'toolbar' => 'full',
      'media_upload' => 1,
      'delay' => 0,
    ),
    /*
    array(
      'key' => 'field_609a382c05f87',
      'label' => 'Category',
      'name' => 'which_category',
      'type' => 'taxonomy',
      'instructions' => '',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'taxonomy' => 'partner_category',
      'field_type' => 'checkbox',
      'add_term' => 1,
      'save_terms' => 0,
      'load_terms' => 0,
      'return_format' => 'object',
      'acfe_bidirectional' => array(
        'acfe_bidirectional_enabled' => '0',
      ),
      'multiple' => 0,
      'allow_null' => 0,
    ),
    */
    array(
      'key' => 'field_609a382c05f87',
      'label' => 'Category',
      'name' => 'which_category',
      'type' => 'taxonomy',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'taxonomy' => 'partner_category',
      'field_type' => 'multi_select',
      'allow_null' => 0,
      'add_term' => 0,
      'save_terms' => 0,
      'load_terms' => 0,
      'return_format' => 'object',
      'acfe_bidirectional' => array(
        'acfe_bidirectional_enabled' => '0',
      ),
      'multiple' => 0,
    ),
    array(
      'key' => 'field_609ae35994787',
      'label' => 'Card Height',
      'name' => 'card_height',
      'type' => 'number',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => 300,
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'min' => '',
      'max' => '',
      'step' => '',
    ),
    array(
      'key' => 'field_60baeca60567b',
      'label' => 'Show Map',
      'name' => 'show_map',
      'type' => 'true_false',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'message' => '',
      'default_value' => 1,
      'ui' => 0,
      'ui_on_text' => '',
      'ui_off_text' => '',
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'block',
        'operator' => '==',
        'value' => 'acf/us-map-list',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'acfe_display_title' => '',
  'acfe_autosync' => '',
  'acfe_form' => 0,
  'acfe_meta' => '',
  'acfe_note' => '',
));

endif;