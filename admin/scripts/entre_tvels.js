let add_tvel_form = document.getElementById('add_tvel_form');


add_tvel_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_tvel();
});

function add_tvel() {
  let data = new FormData();
  data.append('add_tvel','');
  data.append('name',add_tvel_form.elements['name'].value);
  data.append('desc',add_tvel_form.elements['desc'].value);
  data.append('area',add_tvel_form.elements['area'].value);
  data.append('price',add_tvel_form.elements['price'].value);
  data.append('quantity',add_tvel_form.elements['quantity'].value);
  data.append('adult',add_tvel_form.elements['adult'].value);
  data.append('children',add_tvel_form.elements['children'].value);
  data.append('features',add_tvel_form.elements['features'].value);
  data.append('facilities',add_tvel_form.elements['facilities'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('add-tvel');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','New travel added!');
      add_tvel_form.reset();
      get_all_tvels();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function get_all_tvels() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('tvel-data').innerHTML = this.responseText;
  }

  xhr.send('get_all_tvels');
}

let edit_tvel_form = document.getElementById('edit_tvel_form');

function edit_details(id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);
    edit_tvel_form.elements['name'].value = data.tveldata.name;
    edit_tvel_form.elements['desc'].value = data.tveldata.description;
    edit_tvel_form.elements['tvel_id'].value = data.tveldata.id;
    
    // Set hidden field values
    edit_tvel_form.elements['area'].value = '0';
    edit_tvel_form.elements['price'].value = '0';
    edit_tvel_form.elements['quantity'].value = '0';
    edit_tvel_form.elements['adult'].value = '0';
    edit_tvel_form.elements['children'].value = '0';
    edit_tvel_form.elements['features'].value = '[]';
    edit_tvel_form.elements['facilities'].value = '[]';
  }

  xhr.send('get_tvel='+id);
}

edit_tvel_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_tvel();
});

edit_tvel_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_tvel();
});

function submit_edit_tvel() {
  let data = new FormData();
  data.append('edit_tvel','');
  data.append('tvel_id',edit_tvel_form.elements['tvel_id'].value);
  data.append('name',edit_tvel_form.elements['name'].value);
  data.append('desc',edit_tvel_form.elements['desc'].value);
  data.append('area',edit_tvel_form.elements['area'].value);
  data.append('price',edit_tvel_form.elements['price'].value); 
  data.append('quantity',edit_tvel_form.elements['quantity'].value);
  data.append('adult',edit_tvel_form.elements['adult'].value);
  data.append('children',edit_tvel_form.elements['children'].value);
  data.append('features',edit_tvel_form.elements['features'].value);
  data.append('facilities',edit_tvel_form.elements['facilities'].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('edit-tvel');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Travel data edited!');
      edit_tvel_form.reset();
      get_all_tvels();
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
  xhr.open("POST","ajax/entre_tvels.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Status toggled!');
      get_all_tvels();
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
  data.append('tvel_id',add_image_form.elements['tvel_id'].value);
  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);

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
      tvel_images(add_image_form.elements['tvel_id'].value,document.querySelector("#tvel-images .modal-title").innerText)
      add_image_form.reset();
    }
  }
  xhr.send(data);
}

function tvel_images(id,rname)
{
  document.querySelector("#tvel-images .modal-title").innerText = rname;
  add_image_form.elements['tvel_id'].value = id;
  add_image_form.elements['image'].value = '';

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('tvel-image-data').innerHTML = this.responseText;
  }

  xhr.send('get_tvel_images='+id);
}

function rem_image(img_id,tvel_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('tvel_id',tvel_id);
  data.append('rem_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Removed!','image-alert');
      tvel_images(tvel_id,document.querySelector("#tvel-images .modal-title").innerText);
    }
    else{
      alert('error','Image removal failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function thumb_image(img_id,tvel_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('tvel_id',tvel_id);
  data.append('thumb_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/entre_tvels.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Thumbnail Changed!','image-alert');
      tvel_images(tvel_id,document.querySelector("#tvel-images .modal-title").innerText);
    }
    else{
      alert('error','Thumbnail update failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function remove_tvel(tvel_id)
{
  if(confirm("Are you sure, you want to delete this tvel?"))
  {
    let data = new FormData();
    data.append('tvel_id',tvel_id);
    data.append('remove_tvel','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/entre_tvels.php",true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success','Room Removed!');
        get_all_tvels();
      }
      else{
        alert('error','Room removal failed!');
      }
    }
    xhr.send(data);
  }

}

window.onload = function(){
  get_all_tvels();
}

