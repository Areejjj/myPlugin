

var obj = {

    "id": [],
    "title": [],
    "parennt": []
};
var id, title, parent_id, value, title;
var select = document.getElementById("listTypes");

<?php

session_start();
$breeds=$_SESSION['all_breeds'];

for($i=0;$i<count($breeds);$i++){
  
?>

var count = <?php echo count($breeds)?>;
console.log(count);
id = <?php echo $breeds[$i][0];
?>
title = '<?php echo $breeds[$i][1];
?>';
parent_id = <?php echo $breeds[$i][2];
?>;
obj.id.push(id);
obj.title.push(title);
obj.parennt.push(parent_id);

<?php  }
?>

function myfunction(parent_id) {


    console.log(obj.id);
    count = <?php echo count($breeds)?>;
    for (var i = 0; i < count; i++) {
        if (obj.parennt[i] == parent_id) {
            var option = document.createElement("option");
            // var value = myarray[key];
            option.value = obj.id[i];
            option.text = obj.title[i];

            select.add(option);
        } //if

    } //for
} //f


function removeOptions(selectElement) {
    var i, L = selectElement.options.length - 1;
    for (i = L; i >= 0; i--) {
        selectElement.remove(i);
    }
}

// using the function:
removeOptions(document.getElementById('DropList'));

function test() {
    var type_of_id = document.getElementById("type_of_id").value;
    // let all_breed;
    //  createCookie("type_id_selected", type_of_id, "10");
    select = document.getElementById("listTypes");


    removeOptions(select);
    myfunction(type_of_id);
    // alert(type_of_id);



}
console.log('ffhf');