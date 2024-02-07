function showPssword(){
    var input = event.target.parentNode.querySelector("input");
    if(input.type == "password")
       input.type = "text";
    else
    input.type = "password"
    event.target.classList.toggle("active")
       

}

var p = document.querySelector(".error")
var choices  = document.querySelectorAll(".choice button")
var background = document.querySelector(".background")
var user = 'buyer';
choices[0].onclick = ()=>{
    user = 'buyer'
    background.style.left = "0"
    background.style.borderRadius = "5px 0px 0px 5px"
    choices[1].style.color = "#1d3557"
    choices[0].style.color = "#e63946"
    p.style.display = "none"
}
choices[1].onclick = ()=>{
    user = 'seller'
   background.style.left = "50%"
   background.style.borderRadius = "0px 5px 5px 0px"
   choices[0].style.color = "#1d3557"
   choices[1].style.color = "#e63946"
   p.style.display = "none"
}

var form = document.querySelector("form");
form.addEventListener('submit',submitForm)
function submitForm(event) {
    event.preventDefault();
   var formData = new FormData(form);
    fetch(`Login_test.php?form=${user}`, {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if(data != 1){
            p.style.display = "block"
            p.innerText = data
        }else{
            if(user == 'buyer')
            window.location.href = "../../user/home/index.php"
            else
            window.location.href = "../../control/seller/dashboard/dashboard.php"

        }
    })
    .catch(err => console.log(err));
}
var forgot = document.querySelector(".forgot")
var password = document.querySelector("input[type='password']")
var email = document.querySelector("input[type='email']")
var val_email = '';
forgot.onclick = ()=>{
   document.querySelector(".choice").style.display= "none"
   p.style.display= "block"
   p.style.background = '#ef233c'
   p.style.color = "#fff"
   p.innerText = "Enter your email."
   p.style.marginTop = "15px"
   form.innerHTML = `
   <input type="email" name="email" placeholder="Email" id="">

   <button>Next</button>
                    `

    email = document.querySelector("input[type='email']")
    email.style.width = "100%"
    email.style.margin = "20px 0px"
    form.removeEventListener('submit',submitForm)
    form.addEventListener('submit',submitEmail)
    function submitEmail(){
        event.preventDefault()
        var formData = new FormData(form);
        fetch(`forgot_info.php?form=${user}&check=email`, {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if(data != 1){
                p.style.display = "block"
                p.innerText = data
                p.style.background = '#f8d7da'
                p.style.color = "#721c24"
            }else{
                val_email = email.value
                form.querySelector("button").innerText = "Loading..."
                 const randomArray = [
                    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
                    'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
                    'u', 'v', 'w', 'x', 'y', '1', '2', '3', '4', '5',
                    '6', '7', '8', '9', '0', '!', '@', '#', '$', '%',
                    '^', '&', '*', '(', ')', '_', '-', '+', '=', '[',
                    ']', '{', '}', '|', ';', ':', ',', '.', '<', '>'
                  ];
                  var code = []
                  for(var i = 0;i<7;i++){
                    code[i] = randomArray[Math.floor(Math.random() * randomArray.length)]
                  }
                  code = code.join('')
                  fetch('email.php',{
                    method : 'POST',
                    body : JSON.stringify({email:val_email,code:code}),
                    header:{'Content-Type': 'application/json'}
                  })
                  .then(res=>res.text())
                  .then(data => {
                    p.style.background = '#ef233c'
                    p.style.color = "#fff"
                    p.innerText = 'Check your email for verification code.';
                    form.innerHTML = `
                    <input style = "width:100%;margin:20px 0px" type="text" name="text" placeholder="Enter verification code" id="">
                    <button>Send</button>
                                     `
                                                     
                     form.removeEventListener('submit',submitEmail)
                     form.onsubmit = () => testCode(code)
                  });
                  
                
            }
        })
        .catch(err => console.log(err));
      }

}
function testCode(code){
    var value = document.querySelector("input").value
    event.preventDefault();
    if (value=='')
    {
       p.innerText = 'You must fill the field.'
       p.style.background = '#f8d7da'
       p.style.color = "#721c24"
       return
    }
    if(code == value.trim()){
        form.innerHTML = `
        <div>
        <i class="fa-solid fa-lock"></i>
        <i onclick="showPssword()" class="fa-solid fa-eye"></i>
        <input type="password" name="password" placeholder="Password" id="">
      </div>
      <div>
        <i class="fa-solid fa-lock"></i>
        <i onclick="showPssword()" class="fa-solid fa-eye"></i>
        <input type="password" name="Re-password" placeholder="Re-type Password" id="">
      </div>
      <button>Save</button>
      
                          `
        form.onsubmit = testPassword
        p.innerText = "Enter your new password."
        p.style.background = '#ef233c'
        p.style.color = "#fff"
    }
    else
    {
        p.style.background = '#f8d7da'
        p.style.color = "#721c24"
        p.innerText = 'The verification code is not correct.'
    }
        
    
}
function testPassword(){
    event.preventDefault()
    var formData = new FormData(form);
    fetch(`forgot_info.php?form=${user}&check=password&email=${val_email}`, {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if(data != 1){
            p.style.background = '#f8d7da'
            p.style.color = "#721c24"
            p.innerText = data
        }else{
            p.style.display = "none"
            window.location.href = "login.php"

        }
    })
    .catch(err => console.log(err));
    
}