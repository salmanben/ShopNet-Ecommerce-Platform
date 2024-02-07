
var form = document.querySelector("form")
var message = document.querySelector(".message")
form.onsubmit = (event) => {
event.preventDefault();
var formData = new FormData(form)
fetch("contact_test.php",{
  method : "POST",
  body : formData
})
.then(res => res.text())
.then(data => {
     if(data != 1){
      message.style.color = "#721c24"
      message.style.background = '#f8d7da'
      message.innerHTML = data
     }else{
      message.style.background = "#28a745"
      message.style.borderColor = "#28a745"
      message.style.color = "white"
      message.innerHTML = "Form submitted successfully."
      form.reset()
     }
     message.style.display = "block"
     window.scroll({top:0, behavior:'smooth'})
})
.catch(err => console.log(err));
 
};
