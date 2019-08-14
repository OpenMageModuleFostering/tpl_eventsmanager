/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * For Tpl_EventsManager
 */

// Event Level related variables 
var event_level_object;
var group_id_object; 
var user_email_object;

//Event type related variables
var event_type_object; 
var thumbnail_object; 
var banner_object;
var product_launch_date_object;
var product_launch_time_object;        
var address_object;
var city_object;
var state_object;
var country_object;
var contact_number_object;
var contact_email_object;
var pin_code_object;
var product_launch_date_trig_object;


window.onload = function() {
  
  // Event Level related variables initialization
  event_level_object = document.getElementById('event_level');
  group_id_object = document.getElementById('group_id'); 
  user_email_object = document.getElementById('user_email');
  
  // Event Type related variables initialization
  event_type_object = document.getElementById('event_type');
  thumbnail_object = document.getElementById('thumbnail');
  banner_object = document.getElementById('banner');
  product_launch_date_object = document.getElementById('product_launch_date');
  product_launch_time_object = document.getElementById('product_launch_time');        
  address_object =  document.getElementById('address');
  city_object =  document.getElementById('city');
  state_object =  document.getElementById('state');
  country_object =  document.getElementById('country');
  contact_number_object =  document.getElementById('contact_number');
  contact_email_object =  document.getElementById('contact_email');
  pin_code_object =  document.getElementById('pin_code');
  product_launch_date_trig_object = document.getElementById('product_launch_date_trig');
  
  
  
  // trigger both on page load
  on_event_level_select();
  on_event_type_select();
  
  
    
  event_level_object.onchange=function(){ on_event_level_select();};
  event_type_object.onchange=function(){ on_event_type_select();};
  
  //console.log(event_level_object);
  
};





function on_event_level_select()
{
    switch(event_level_object.value) {
     case '15':
             handleglobalevent();
             
         break;
     case '16':
            handleglobalcustomerevent();
            
         break;
     case '17':
            handleuserevents()
            
         break;
     case '18':
            handlegroupevents();
           
         break;

     default:
             console.log('in level default default');

 } 
   
    //console.log(event_level_object.value);
}

// Event Level Handles
function handleglobalevent()
{
    
 group_id_object.disabled = true;
 user_email_object.disabled = true;
 
 console.log('15');   
}

function handleglobalcustomerevent()
{   group_id_object.disabled = true;
    user_email_object.disabled = true;
    console.log('16');
}

function handleuserevents()
{
    group_id_object.disabled = true;
    user_email_object.disabled = false;
    console.log('17');
}

function handlegroupevents()
{   group_id_object.disabled = false;
    user_email_object.disabled = true;
    console.log('18');
}

















function on_event_type_select()
{
    switch(event_type_object.value) {
     case '19':
             handlesalesevent();
             
         break;
     case '20':
            handleproductlaunchevent();
            
         break;
     case '21':
            handlelocalexibitionevent()
            
         break;
     case '22':
            handlenotificationevent();
           
         break;

     default:
             console.log('in event type default');

 } 
}

// Event Type Handels
function handlesalesevent()
{
 thumbnail_object.disabled = false; 
 banner_object.disabled = false; 
    
 product_launch_date_object.disabled = true; 
 product_launch_date_trig_object.style.visibility = 'hidden';
 product_launch_time_object.disabled = true;       
 address_object.disabled = true;
 city_object.disabled = true; 
 state_object.disabled = true; 
 country_object.disabled = true; 
 contact_number_object.disabled = true;
 contact_email_object.disabled = true;
 pin_code_object.disabled = true;
 console.log('19');
}

function handleproductlaunchevent()
{
 product_launch_date_object.disabled = false; 
 product_launch_date_trig_object.style.visibility = 'initial';
 product_launch_time_object.disabled = false; 
 thumbnail_object.disabled = false; 
 banner_object.disabled = false; 
 
 address_object.disabled = true;
 city_object.disabled = true; 
 state_object.disabled = true; 
 country_object.disabled = true; 
 contact_number_object.disabled = true;
 contact_email_object.disabled = true;
 pin_code_object.disabled = true;
 console.log('20');
 
}

function handlelocalexibitionevent()
{
 thumbnail_object.disabled = false; 
 banner_object.disabled = false; 
 address_object.disabled = false;
 city_object.disabled = false; 
 state_object.disabled = false; 
 country_object.disabled = false; 
 contact_number_object.disabled = false;
 contact_email_object.disabled = false;
 pin_code_object.disabled = false;
    
 product_launch_date_object.disabled = true; 
 product_launch_date_trig_object.style.visibility = 'initial';
 product_launch_time_object.disabled = true; 
 console.log('21');
}

function handlenotificationevent()
{
//   thumbnail_object.disabled = true; 
//   banner_object.disabled = true;
   address_object.disabled = true;
   city_object.disabled = true; 
   state_object.disabled = true; 
   country_object.disabled = true; 
   contact_number_object.disabled = true;
   contact_email_object.disabled = true;
   pin_code_object.disabled = true;
   
   console.log('22');
}


