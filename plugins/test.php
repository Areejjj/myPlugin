<?php

/*Plugin Name: red hell
Description: this plugin for pets animal adoption 
Author: areej
Version: 11.1.0
*/
function add_new_post_type()
{


  register_post_type('adoption', array(
    'show_in_rest' => true,
    'supports' => array('title', 'thumbnail'),

    'has_archive' => true,

    'public' => true, 'labels' => array('name' => 'Adoption', 'add_new_item' => 'Add new Adoption', 'edit_item' => 'edit adoption', 'all_items' => 'All Adoptions', 'singular_name' => 'adoption'),
    'menu_icon' => 'dashicons-editor-textcolor'
  ));
}
add_action('init', 'add_new_post_type');
add_action('init', function () {

  $labels = array(
    "name" => "Breed",
    "singular_name" => " Breed",
    "menu_name" => "Breed",
    "all_items" => "All  Breed",
    "add_new" => "Add New",
    "add_new_item" => "Add New  Breed",
    "edit" => "Edit",
    "edit_item" => "Edit Breed",
    "new_item" => "New breed ",
    "view" => "View",
    "view_item" => "View  breed",
    "search_items" => "Search  Breed",
    "not_found" => "No Breed Found",
    "not_found_in_trash" => "No Breed  Found in Trash",
    "parent" => "Parent  Breeds",
  );

  $args = array(
    "labels" => $labels,
    "description" => "",
    'menu_icon' => 'dashicons-editor-bold',
    "public" => true,
    "show_ui" => true,
    "has_archive" => true,
    "show_in_menu" => true,
    "exclude_from_search" => false,
    "capability_type" => "post",
    "map_meta_cap" => true,
    "hierarchical" => false,
    "rewrite" => array("slug" => "breed", "with_front" => true),
    "query_var" => true,
    "supports" => array("title")
  );

  register_post_type("breed", $args);

  $labels = array(
    "name" => "Type",
    "singular_name" => "Type",
  );

  $args = array(
    "labels" => $labels,
    "description" => "",
    "public" => true,
    "show_ui" => true,
    "has_archive" => true,
    "show_in_menu" => true,
    "exclude_from_search" => false,
    "capability_type" => "post",
    "map_meta_cap" => true,
    "rewrite" => array("slug" => "type", "with_front" => true),
    'menu_icon' => 'dashicons-buddicons-activity',
    "query_var" => true,
    "supports" => array("title")
  );

  register_post_type("type", $args);
});
//end of build the cpts
// add parent to breed  child

add_action('add_meta_boxes', 'wporg_add_custom_box');

function wporg_add_custom_box()
{

  $screens = ['breed'];
  foreach ($screens as $screen) {
    add_meta_box(
      'wporg_box_id',                 // id
      'Custom Meta for type',      // title
      'wporg_custom_box_html',  //function 
      $screen, // Post type

      'advanced',
      'high'

    );
  }
}
function wporg_custom_box_html($post)
{
  $breed = get_post($post->ID);

  $type = get_post($breed->post_parent);

?>
<br>
<select name='new_child' id="wporg_field" class="postbox">
    <?php
    $full_array = get_options($post);
    echo "<option  id='$type->ID' value='$type->ID'> $type->post_title</option>";

    foreach ($full_array as $temp) {
      foreach ($temp as $key => $value) {
        if ($type->ID != $key)

          echo "<option value='$key'  id='$key'> $value</option>";
      }
    ?>
    <br>

    <?php  }

    ?>
</select>
</div>


<?php
} //fun
function get_options($post)
{
  $options = new WP_Query(array(
    'post_type' => 'type',
    'posts_per_page' => -1,



  ));
  $full_array = array();

  while ($options->have_posts()) {
    $options->the_post();
    $temp = [];
    $id = get_the_ID();
    $temp[$id] = get_the_title();
    array_push($full_array, $temp);
    //  $title=
    // array_push($optionsTitle, get_the_title());
  }
  return $full_array;
}


//  save the parent of breed

function save_custom_meta_box_type($post_id, $post, $update)
{

  global $wpdb;

  if (isset($_POST["new_child"])) {
    $post_title_new = $_POST["new_child"];
    $wpdb->update(
      'wp_posts',
      array(
        'post_type' => 'breed',
        'post_parent' => $_POST['new_child']
      ),
      array('ID' => $post_id)
    );
  }

  // update_post_meta($post_id, "meta-box-checkbox", $meta_box_checkbox_value);
}

add_action("save_post", "save_custom_meta_box_type", 10, 3);

function episodes_attributes_meta_box($post)
{
  $pages = wp_dropdown_pages(array('post_type' => 'type', 'selected' => $post->post_parent, 'name' => 'parent_id', 'show_option_none' => __('(no parent)'), 'sort_column' => 'menu_order, post_title', 'echo' => 0));
  if (!empty($pages)) {
    echo $pages;
  } // end empty pages check
}
function add_type_meta_box()
{

  $screens = array('adoption');

  foreach ($screens as $screen) {

    // 
    add_meta_box(
      'type_meta_box',       // id
      __('Types Of Animals'),    // title
      'type_meta_box_callback',    // Function that prints out the HTML for the edit screen section.
      $screen,
      'normal',
      'high'     // Which writing screen 
    );
  }
}
global $type_id;


function type_meta_box_callback($post)
{
  global $type_id, $parents;

  $all_types = get_posts(
    array(
      'post_type'   => 'type',
      'orderby'     => 'title',
      'order'       => 'ASC',
      'numberposts' => -1
    )
  );

?>
<?php
$idType=get_post_meta($post->ID, 'typeOfAnimal', true);

echo '<form method="GET"><select name="typeOfAnimal" id="type_of_id" class="widefat" onchange="test()">'; //

        //updates
        $type1 = get_post($idType);
        if (!empty($type1)) {

        printf('<option value="%s">%s</option>', esc_attr($type1->ID), esc_html($type1->post_title));
        }

        if (!empty($all_types)) {


        foreach ($all_types as $type) {
        if ($type->ID != $type1->ID)
        printf('<option value="%s">%s</option>', esc_attr($type->ID), esc_html($type->post_title));
        }

        echo '</select></form>';
}
}
//add meta box for breedssssssssssssssssssssssssssssssssssssssssssss
//

function add_breed_meta_box()
{
global $type_id;
$screens = array('adoption');

foreach ($screens as $screen) {

add_meta_box(
'breed_meta_box', // id
__('Breed Of Animal'), // title
'breed_meta_box_callback', // Function that prints out the HTML for the edit screen section.
$screen

);
}
}
global $ids;
function breed_meta_box_callback($post)
{
get_breeds();

global $parents;
$parents = get_posts(
array(
'post_type' => 'breed',
'orderby' => 'title',
'order' => 'ASC',
'numberposts' => -1,
)
);
$idBreed = get_post_meta($post->ID, 'breedOfAnimal', true);
$breed = get_post($idBreed);
echo '<select name="parent_id1" id="listTypes" class="widefat">';
    if (!empty($idBreed))
    printf('<option value="%s">%s</option>', esc_attr($breed->ID), esc_html($breed->post_title));
    else
    echo "<option value='0'> </option>";
    if (!empty($parents)) {

    // !Important! Don't change the 'parent_id' name attribute.

    foreach ($parents as $parent) {
    // printf('<option value="%s">%s</option>', esc_attr($parent->ID), esc_html($parent->post_title));
    }

    echo '</select><br><br>';
}
}
global $full_array1;

function get_breeds()
{
$full_array1 = array();

$options = new WP_Query(array(
'post_type' => 'breed',
'posts_per_page' => -1
));
$temp1 = [];

while ($options->have_posts()) {
$options->the_post();

$id = get_the_ID();
$title = get_the_title();
$post_parent = wp_get_post_parent_id($id);
array_push($full_array1, array($id, $title, $post_parent));
}
if (!headers_sent() && session_id() == 'all_breeds') {
session_start();
unset($_SESSION['all_breeds']);
$_SESSION['all_breeds'] = $full_array1;
}
return $full_array1;
}

add_action('add_meta_boxes', 'acme_add_meta_boxes');
function acme_add_meta_boxes()
{
add_breed_meta_box();
add_type_meta_box();
add_data_meta_box();
}

//save breed based on type from metabox

function wporg_save_postdata($post_id)
{
global $wpdb;

if (isset($_POST["new_child"])) {
$post_title_new = $_POST["new_child"];
$wpdb->update(
'wp_posts',
array(
'post_type' => 'breed',
'post_parent' => $_POST['new_child']
),
array('ID' => $post_id)
);

update_post_meta($post_id, "new_child", $_POST["new_child"]);
}
}
add_action('save_post', 'wporg_save_postdata');
add_action('save_post_adoption', 'saveAdoption');

function saveAdoption($post_id)
{
global $wpdb;

if (isset($_POST["typeOfAnimal"])) {
update_post_meta($post_id, "typeOfAnimal", $_POST["typeOfAnimal"]);
}
if (isset($_POST["parent_id1"])) {
update_post_meta($post_id, "breedOfAnimal", $_POST["parent_id1"]);
}
if (isset($_POST["colorAnimal"])) {
update_post_meta($post_id, "colorOfAnimal", $_POST["colorAnimal"]);
}
if (isset($_POST["dobAnimal"])) {
update_post_meta($post_id, "dobOfAnimal", $_POST["dobAnimal"]);
}

if (isset($_POST["noteAnimal"])) {
update_post_meta($post_id, "noteOfAnimal", $_POST["noteAnimal"]);
}
if (isset($_POST["genderAnimal"])) {

update_post_meta($post_id, "genderOfAnimal", $_POST["genderAnimal"]);
}
} //saveAdoption
// add custom fields
function add_data_meta_box()
{
global $type_id;
$screens = array('adoption');
foreach ($screens as $screen) {
add_meta_box(
'data_meta_box', // id
__('Data Of Animal'), // title
'data_meta_box_callback', // Function that prints out the HTML for the edit screen section.
$screen

);
}
}

function data_meta_box_callback($post)
{

$colorOfAnimal = get_post_meta($post->ID, 'colorOfAnimal', true);
$dobOfAnimal = get_post_meta($post->ID, 'dobOfAnimal', true);
$noteOfAnimal = get_post_meta($post->ID, 'noteOfAnimal', true);
$genderOfAnimal = get_post_meta($post->ID, 'genderOfAnimal', true);

?>
<label for="wporg_field">color </label><br>

<input type="text" id="colorAnimal" name='colorAnimal' class="widefat" value="<?php echo $colorOfAnimal; ?>" />
<br>
<br>
<label for="wporg_field">Date Of Birthday </label><br>

<input type="date" id="dobAnimal" name='dobAnimal' class="widefat" value="<?php echo $dobOfAnimal; ?>" />
<br>
<br>
<label for="wporg_field">Gender </label>
<br>
<br>
<?php
  if ($genderOfAnimal == 'male') { ?>

Male:&nbsp;<input type="radio" id="gender" name='genderAnimal' value="male" checked />
Female: &nbsp;<input type="radio" id="gender" name='genderAnimal' value="female" />
<?php
  } else {
  ?>
Female: &nbsp;<input type="radio" id="gender" name='genderAnimal' value="female" checked />
Male:&nbsp;<input type="radio" id="gender" name='genderAnimal' value="male">
<?php }
  ?>
<br>
<br>
<label for="wporg_field">Note About Animal </label><br>
<input type="text" id="noteAnimal" name='noteAnimal' class="widefat" value="<?php echo $noteOfAnimal; ?>" />
<?php
}
?>