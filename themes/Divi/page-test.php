<?php

$homepageEvents = new WP_Query(array(
  'posts_per_page' => -1,
  'post_type' => 'adoption',

            
));

while($homepageEvents->have_posts()) {
  $homepageEvents->the_post();
  $id=get_the_ID();
$idpost= get_post_meta($id);
$id=$idpost['typeOfAnimal'][0];
echo get_the_title($id).'<br>';
?>



<?php
}


?>