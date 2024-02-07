function showPssword(){
    var input = event.target.parentNode.querySelector("input");
    if(input.type == "password")
       input.type = "text";
    else
    input.type = "password"
    event.target.classList.toggle("active")
       
    
}
var p =document.querySelector(".error")
var form = document.querySelector("form");
var choices  = document.querySelectorAll(".choice button")
var background = document.querySelector(".background")
var user = 'buyer';
var file = document.querySelector(".file")
var arrow = document.querySelector(".fa-right-long")
choices[0].onclick = ()=>{
    user = 'buyer'
    background.style.left = "0"
    file.style.left = "-400px"
    background.style.borderRadius = "5px 0px 0px 5px"
    choices[1].style.color = "#1d3557"
    choices[0].style.color = "#e63946"
    p.style.display = "none"
    arrow.style.display = "none"
}
choices[1].onclick = ()=>{
    user = 'seller'
    file.style.left = "0";
   background.style.left = "50%"
   background.style.borderRadius = "0px 5px 5px 0px"
   choices[0].style.color = "#1d3557"
   choices[1].style.color = "#e63946"
   p.style.display = "none"
   arrow.style.display = "block"
}
var container1= document.querySelector(".container1")
var container2= document.querySelector(".container2")
var counter = 0
arrow.onclick = ()=>{
    arrow.classList.toggle("active")
  if(counter == 0){
    container1.style.left = "-400px"
    container2.style.left = "0px"
    p.style.left = "0px"
    counter++
  }else{
    container1.style.left = "0px"
    container2.style.left = "400px"
    counter = 0
  }
}
function submit_form(event) {
    event.preventDefault();
   var formData = new FormData(form);
    fetch(`Signup_test.php?form=${user}`, {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if(data != 1){
            p.style.display = "block"
            p.innerText = data
        }
        else{
            if(user == 'buyer')
            window.location.href = "../../user/home/index.php"
            else
            window.location.href = "../../control/seller/dashboard/dashboard.php"

        }
    })
    .catch(err => console.log(err));
}
