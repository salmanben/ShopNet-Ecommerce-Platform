function showPssword() {
    var input = event.target.parentNode.querySelector("input");
    if (input.type == "password") input.type = "text";
    else input.type = "password"
    event.target.classList.toggle("active")
  }
var form = document.querySelector("form")
var username = document.querySelector("input[type='text']")
var email = document.querySelector("input[type='email']")
var p = document.querySelector(".error")
fetch("Settings_buyer_test.php")
.then(res => res.json())
.then(data => {
     username.value = data.username
     email.value = data.email
})
.catch(err => console.log(err));
form.onsubmit = (event) => {
event.preventDefault();
var formData = new FormData(form)
fetch("Settings_buyer_test.php",{
  method : "POST",
  body : formData
})
.then(res => res.text())
.then(data => {
     if(data != 1){
      p.style.color = "#721c24"
      p.style.background = '#f8d7da'
      p.innerHTML = data
     }else{
      p.style.background = "#28a745"
      p.style.borderColor = "#28a745"
      p.style.color = "white"
      p.innerHTML = "Your new informations saved successfully."
     }
     p.style.display = "block"
})
.catch(err => console.log(err));
 
};
