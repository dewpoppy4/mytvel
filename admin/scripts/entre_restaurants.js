let add_restaurant_form = document.getElementById('add_restaurant_form');


add_restaurant_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_restaurant();
});

function add_restaurant() {
  let data = new FormData();
  data.append('add_restaurant','');
  data.append('name',add_restaurant_form.elements['name'].value);
  data.append('desc',add_restaurant_form.elements['desc'].value);
  data.append('area',add_restaurant_form.elements['area'].value);
  data.append('price',add_restaurant_form.elements['price'].value);
  data.append('quantity',add_restaurant_form.elements['quantity'].value);
  data.append('adult',add_restaurant_form.elements['adult'].value);
  data.append('children',add_restaurant_form.elements['children'].value);
  data.append('features',add_restaurant_form.elements['features'].value);
  data.append('facilities',add_restaurant_form.elements['facilities'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('add-restaurant');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','New travel added!');
      add_restaurant_form.reset();
      get_all_restaurants();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function get_all_restaurants() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('restaurant-data').innerHTML = this.responseText;
  }

  xhr.send('get_all_restaurants');
}

let edit_restaurant_form = document.getElementById('edit_restaurant_form');

function edit_details(id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);
    edit_restaurant_form.elements['name'].value = data.restaurantdata.name;
    edit_restaurant_form.elements['desc'].value = data.restaurantdata.description;
    edit_restaurant_form.elements['restaurant_id'].value = data.restaurantdata.id;
    
    // Set hidden field values
    edit_restaurant_form.elements['area'].value = '0';
    edit_restaurant_form.elements['price'].value = '0';
    edit_restaurant_form.elements['quantity'].value = '0';
    edit_restaurant_form.elements['adult'].value = '0';
    edit_restaurant_form.elements['children'].value = '0';
    edit_restaurant_form.elements['features'].value = '[]';
    edit_restaurant_form.elements['facilities'].value = '[]';
  }

  xhr.send('get_restaurant='+id);
}

edit_restaurant_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_restaurant();
});

edit_restaurant_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_restaurant();
});

function submit_edit_restaurant() {
  let data = new FormData();
  data.append('edit_restaurant','');
  data.append('restaurant_id',edit_restaurant_form.elements['restaurant_id'].value);
  data.append('name',edit_restaurant_form.elements['name'].value);
  data.append('desc',edit_restaurant_form.elements['desc'].value);
  data.append('area',edit_restaurant_form.elements['area'].value);
  data.append('price',edit_restaurant_form.elements['price'].value); 
  data.append('quantity',edit_restaurant_form.elements['quantity'].value);
  data.append('adult',edit_restaurant_form.elements['adult'].value);
  data.append('children',edit_restaurant_form.elements['children'].value);
  data.append('features',edit_restaurant_form.elements['features'].value);
  data.append('facilities',edit_restaurant_form.elements['facilities'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('edit-restaurant');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Travel data edited!');
      edit_restaurant_form.reset();
      get_all_restaurants();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function toggle_status(id,val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Status toggled!');
      get_all_restaurants();
    }
    else{
      alert('success','Server Down!');
    }
  }

  xhr.send('toggle_status='+id+'&value='+val);
}

let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_image();
});

function add_image()
{
  let data = new FormData();
  data.append('image',add_image_form.elements['image'].files[0]);
  data.append('restaurant_id',add_image_form.elements['restaurant_id'].value);
  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 'inv_img'){
      alert('error','Only JPG, WEBP or PNG images are allowed!','image-alert');
    }
    else if(this.responseText == 'inv_size'){
      alert('error','Image should be less than 2MB!','image-alert');
    }
    else if(this.responseText == 'upd_failed'){
      alert('error','Image upload failed. Server Down!','image-alert');
    }
    else{
      alert('success','New image added!','image-alert');
      restaurant_images(add_image_form.elements['restaurant_id'].value,document.querySelector("#restaurant-images .modal-title").innerText)
      add_image_form.reset();
    }
  }
  xhr.send(data);
}

function restaurant_images(id,rname)
{
  document.querySelector("#restaurant-images .modal-title").innerText = rname;
  add_image_form.elements['restaurant_id'].value = id;
  add_image_form.elements['image'].value = '';

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('restaurant-image-data').innerHTML = this.responseText;
  }

  xhr.send('get_restaurant_images='+id);
}

function rem_image(img_id,restaurant_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('restaurant_id',restaurant_id);
  data.append('rem_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Removed!','image-alert');
      restaurant_images(restaurant_id,document.querySelector("#restaurant-images .modal-title").innerText);
    }
    else{
      alert('error','Image removal failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function thumb_image(img_id,restaurant_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('restaurant_id',restaurant_id);
  data.append('thumb_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_restaurants.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Thumbnail Changed!','image-alert');
      restaurant_images(restaurant_id,document.querySelector("#restaurant-images .modal-title").innerText);
    }
    else{
      alert('error','Thumbnail update failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function remove_restaurant(restaurant_id)
{
  if(confirm("Are you sure, you want to delete this restaurant?"))
  {
    let data = new FormData();
    data.append('restaurant_id',restaurant_id);
    data.append('remove_restaurant','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/entre_restaurants.php",true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success','Room Removed!');
        get_all_restaurants();
      }
      else{
        alert('error','Room removal failed!');
      }
    }
    xhr.send(data);
  }

}

window.onload = function(){
  get_all_restaurants();
}

