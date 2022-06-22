<?php 
error_reporting(0);
global $parents ;
/*

Plugin Name:adoption plugin
Description: this plugin for pets animal adoption 
Author: areej
Version: 1.1.0
*/


/*function add_custom_post_type(){

$labels1 = array(
    "name" => "adoptions",
    "singular_name" => "adoptions",
    "menu_name" => "Adoptions",
    "all_items" => "All adoption",
    "add_new" => "Add New adoption",
    "add_new_item" => "Add New adoption",
    "edit" => "Edit adoptions",
    "edit_item" => "Edit adoption",
    "new_item" => "New adoption",
    "view" => "View",
    "view_item" => "View adoption",
    
    
  );

  $args1 = array(
    "labels" => $labels1,
    "description" => "",
    "public" => true,
    "show_ui" => true,
    "has_archive" => true,
    "show_in_menu" => true,


    "map_meta_cap" => true,
    "hierarchical" => true,
    "rewrite" => array("slug" => "adoption", "with_front" => true),
    "query_var" => true,
    "supports" => array('title'),
    'menu_icon' => 'dashicons-table-col-before'
  
  
  );
  register_post_type("adoption", $args1);

}
  add_action('init', 'add_custom_post_type');*/
  function add_new_post_type()
  {


    register_post_type('adoption',array(
      'show_in_rest'=>true,
      'supports'=>array('title','thumbnail'),
      'rewrite' => array('slug' => 'adoption'),
      'has_archive'=>true,
      
        'public'=>true,'labels'=>array('name'=>'Adoption','add_new_item'=>'Add new Adoption','edit_item'=>'edit adoption','all_items'=>'All Adoptions','singular_name'=>'adoption'),
      'menu_icon'=>'dashicons-table-col-before'));
      
      }

  add_action('init', 'add_new_post_type');














/**/
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
    "hierarchical" => true,

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
      'side'
    );
  }
}
function wporg_custom_box_html($post)
{
?>
<fotm method="post">



  <br>
  <select   name='new_child' id="wporg_field" class="postbox">
  <?php
  $full_array = get_options($post);
  

  foreach ($full_array as $temp) {
    foreach($temp as $key=>$value)
  
{

    echo "<option value='$key'> $value</option>";
}
    ?>
    <br>

  <?php  }

  ?>
  </select>
  </div>
  </form>

<?php
}
function get_options($post)
{
  $options = new WP_Query(array(
    'post_type' => 'type',
    'posts_per_page' => -1,
  


  ));
  $full_array=array();

  while ($options->have_posts()) {
    $options->the_post(); 
    $temp=[];
    $id=get_the_ID();
    $temp[$id]=get_the_title();
array_push($full_array,$temp);
    //  $title=
    // array_push($optionsTitle, get_the_title());
  }
  return $full_array;
}


//  save the parent of breed

function save_custom_meta_box_type($post_id, $post, $update)
{
   
  
    global $wpdb;

    if(isset($_POST["new_child"]))
    {
        $post_title_new = $_POST["new_child"];
        $wpdb->update('wp_posts', 
        array('post_type' => 'breed', 
        'post_parent' => $_POST['new_child']) ,
    array('ID'=>$post_id)); 
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
//////////






function my_add_meta_boxes()
{

  add_meta_box(
    'my-place-parent',
    __('adoption'),
    'my_place_parent_meta_box',
    'adoption',
    'side',
    'default'
  );
}

/* Displays the meta box. */
// function my_place_parent_meta_box($post)
// {

//   $parents = get_posts(
//     array(
//       'post_type'   => 'type',
//       'orderby'     => 'title',
//       'order'       => 'ASC',
//       'numberposts' => -1
//     )
//   );

//   if (!empty($parents)) {

//     echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.

//     foreach ($parents as $parent) {
//       printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $post->post_parent, false), esc_html($parent->post_title));
//     }

//     echo '</select>';
//   }
  
//   echo  'fff';
// }
// 
// 
// 
// 
// ////
// 

function add_type_meta_box() {

  $screens = array( 'adoption' );

  foreach ( $screens as $screen ) {

    // 
    add_meta_box(
      'type_meta_box',       // id
      __( 'Types Of Animals' ),    // title
      'type_meta_box_callback',    // Function that prints out the HTML for the edit screen section.
      $screen ,'side' ,'high'     // Which writing screen 
    );

  }
}
global $type_id;








function type_meta_box_callback()
{global $type_id,$parents;

  $all_types = get_posts(
    array(
      'post_type'   => 'type',
      'orderby'     => 'title',
      'order'       => 'ASC',
      'numberposts' => -1
    )
  );
  ?>

    
<script>
 
  function createCookie(name, value, days) {
      var expires;
        
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
      }
      else {
          expires = "";
      }
        
      document.cookie = escape(name) + "=" + 
          escape(value) + expires + "; path=/";
  }
  

      var select;

  

 function test()
    {
var type_of_id   = document.getElementById("type_of_id").value;
// let all_breed;
//  createCookie("type_id_selected", type_of_id, "10");
  select = document.getElementById("listTypes");
  
 for(var i=0; i<select.children.length;i++)
   {select.remove(i);}
 
  myfunction (9);
  // alert(type_of_id);



}


  
  
  </script>
  <?php


  if (!empty($all_types)) {

    echo '<form method="GET"><select name="typeOfAnimal" id="type_of_id" class="widefat" onchange="test()" >'; // !Important! Don't change the 'parent_id' name attribute.
    echo "<option value='0'> </option>";
    foreach ($all_types as $type) {
      printf('<option value="%s">%s</option>', esc_attr($type->ID),esc_html($type->post_title));
    }

    echo '</select></form>';
  }
}





//add meta box for breedssssssssssssssssssssssssssssssssssssssssssss
//

function add_breed_meta_box() {
global $type_id;
  $screens = array( 'adoption' );

  foreach ( $screens as $screen ) {

  add_meta_box(
      'breed_meta_box',       // id
      __( 'Breed Of Animal' ),    // title
      'breed_meta_box_callback',    // Function that prints out the HTML for the edit screen section.
      $screen  
	
    );

  }
  
}
global $ids;
function breed_meta_box_callback()
{

  global $parents;
  $parents = get_posts(
    array(
      'post_type'   => 'breed',
      'orderby'     => 'title',
      'order'       => 'ASC',
      'numberposts' => -1,
   
    )
  );

  if (!empty($parents)) {

    echo '<select name="parent_id1" id="listTypes" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
echo "<option value='0'>2 </option>";
    foreach ($parents as $parent) {
     //   printf('<option value="%s">%s</option>', esc_attr($parent->ID), esc_html($parent->post_title));
    }

    echo '</select><br><br>';
  }
	
}
global $full_array1;

function get_breeds()
{ $full_array1=array();

  $options = new WP_Query(array(
    'post_type' => 'breed',
    'posts_per_page' => -1
    



  ));
  $temp1=[];

  while ($options->have_posts()) {
    $options->the_post(); 
  
    $id=get_the_ID();
  $title=get_the_title();
  $post_parent=wp_get_post_parent_id($id);
// $full_array1[$id]=$title;
array_push($full_array1,array($id,$title,$post_parent));




}
session_start();
session_destroy();
$_SESSION['all_breeds'] = $full_array1;


  // array_push($full_array1, $temp1);
  return $full_array1;
}





add_action( 'add_meta_boxes', 'acme_add_meta_boxes' );
function acme_add_meta_boxes() {

	add_breed_meta_box();
	add_type_meta_box();

}



//save	data from  metabox

function wporg_save_postdata( $post_id ) {
  
   update_post_meta($post_id, "wporg_box_id", $meta_box_checkbox_value);
      // wp_update_post(
      //       $post_id,
      //       'post_parent',
      //      35
      //   );
      // // $data = array(
      // //   'ID' => 12,
      // //   'post_parent' =>34
    
         
      // //  );
       
      // // wp_update_post( $data );
    }

add_action( 'save_post', 'wporg_save_postdata' );






add_filter( 'manage_type_posts_columns', 'set_custom_edit_book_columns' );
function set_custom_edit_book_columns($columns) {
    unset( $columns['author'] );
    $columns['book_author'] = __( 'Author', 'your_text_domain' );
    $columns['publisher'] = __( 'Publisher', 'your_text_domain' );

    return $columns;
}