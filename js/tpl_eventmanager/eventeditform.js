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
var from_time_object;
var end_time_objetct;



var all_input_fields_in_body;

var xhttp = new XMLHttpRequest();
var event_level_ids;

window.onload = function () {

    // validation for time fields 
   from_time_object = document.getElementById('from_time');
   end_time_objetct =  document.getElementById('end_time');
   from_time_object.pattern = end_time_objetct.pattern = '([01]?[0-9]|2[0-3]):[0-5][0-9]';
   from_time_object.type = end_time_objetct.type = 'time';
//   oninvalid="setCustomValidity('Plz enter on Alphabets ')"
//    onchange=
//   from_time_object.oninvalid = end_time_objetct.oninvalid = 'Plz enter on Alphabets ';
//   from_time_object.onchange = end_time_objetct.onchange = "try{setCustomValidity('')}catch(e){}";
   


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
    address_object = document.getElementById('address');
    city_object = document.getElementById('city');
    state_object = document.getElementById('state');
    country_object = document.getElementById('country');
    contact_number_object = document.getElementById('contact_number');
    contact_email_object = document.getElementById('contact_email');
    pin_code_object = document.getElementById('pin_code');
    product_launch_date_trig_object = document.getElementById('product_launch_date_trig');

    all_input_fields_in_body = document.getElementsByTagName("input");

    // trigger both on page load
    
    
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        event_level_ids = xhttp.responseText; 
        console.log(event_level_ids);
      }
    };
    xhttp.open("GET", "http://"+window.location.host+"/tpl_eventsmanager/event/geteventslevelids", true);
    xhttp.send();

    
    on_event_level_select();
    on_event_type_select();



    event_level_object.onchange = function () {
        on_event_level_select();
    };
    event_type_object.onchange = function () {
        on_event_type_select();
    };

    //console.log(event_level_object);

};





function on_event_level_select()
{
    switch (event_level_object.value) {
        case '15':
            handleglobalevent();

            break;
        case '16':
            handleglobalcustomerevent();

            break;
        case '17':
            handleuserevents();

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

    group_id_object.disabled = 'disabled';
    user_email_object.disabled = 'disabled';
    togglecolors();
    console.log('15');
}

function handleglobalcustomerevent()
{
    group_id_object.disabled = 'disabled';
    user_email_object.disabled = 'disabled';
    console.log('16');
    togglecolors();
}

function handleuserevents()
{
    group_id_object.disabled = 'disabled';
    user_email_object.removeAttribute("disabled");
    console.log('17');
    togglecolors();
}

function handlegroupevents()
{
    group_id_object.removeAttribute("disabled");
    user_email_object.disabled = 'disabled';
    console.log('18');
    togglecolors();
}

















function on_event_type_select()
{
    switch (event_type_object.value) {
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
    thumbnail_object.removeAttribute("disabled");
    banner_object.removeAttribute("disabled");

    product_launch_date_object.disabled = 'disabled';
    product_launch_date_trig_object.style.visibility = 'hidden';
    product_launch_time_object.disabled = 'disabled';
    address_object.disabled = 'disabled';
    city_object.disabled = 'disabled';
    state_object.disabled = 'disabled';
    country_object.disabled = 'disabled';
    contact_number_object.disabled = 'disabled';
    contact_email_object.disabled = 'disabled';
    pin_code_object.disabled = 'disabled';
    console.log('19');
    togglecolors();
}

function handleproductlaunchevent()
{
    product_launch_date_object.removeAttribute("disabled");
    product_launch_date_trig_object.style.visibility = 'initial';
    product_launch_time_object.removeAttribute("disabled");
    thumbnail_object.removeAttribute("disabled");
    banner_object.removeAttribute("disabled");

    address_object.disabled = 'disabled';
    city_object.disabled = 'disabled';
    state_object.disabled = 'disabled';
    country_object.disabled = 'disabled';
    contact_number_object.disabled = 'disabled';
    contact_email_object.disabled = 'disabled';
    pin_code_object.disabled = 'disabled';
    console.log('20');
    togglecolors();
}

function handlelocalexibitionevent()
{
    thumbnail_object.removeAttribute("disabled");
    banner_object.removeAttribute("disabled");
    address_object.removeAttribute("disabled");
    city_object.removeAttribute("disabled");
    state_object.removeAttribute("disabled");
    country_object.removeAttribute("disabled");
    contact_number_object.removeAttribute("disabled");
    contact_email_object.removeAttribute("disabled");
    pin_code_object.removeAttribute("disabled");

    product_launch_date_object.disabled = 'disabled';
    //product_launch_date_object.style.background = "rgb(234, 231, 231) none repeat scroll 0% 0%"; 
    product_launch_date_trig_object.style.visibility = 'initial';
    product_launch_time_object.disabled = 'disabled';
// product_launch_time_object.style.background = "rgb(234, 231, 231) none repeat scroll 0% 0%"; 
    console.log('21');
    togglecolors();
}

function handlenotificationevent()
{
//   thumbnail_object.disabled = 'disabled'; 
//   banner_object.disabled = 'disabled';
    address_object.disabled = 'disabled';
    city_object.disabled = 'disabled';
    state_object.disabled = 'disabled';
    country_object.disabled = 'disabled';
    contact_number_object.disabled = 'disabled';
    contact_email_object.disabled = 'disabled';
    pin_code_object.disabled = 'disabled';
    togglecolors();
    console.log('22');
}


// function for toggling colors of disabled fields
function togglecolors()
{
    var len = all_input_fields_in_body.length;
    for (i = 0; i < len; i++)
    {
        if (all_input_fields_in_body[i].hasAttribute("disabled")) {
            all_input_fields_in_body[i].style.background = "rgb(234, 231, 231) none repeat scroll 0% 0%";

        } else {
            all_input_fields_in_body[i].style.background = "#fff";
        }
    }
}
