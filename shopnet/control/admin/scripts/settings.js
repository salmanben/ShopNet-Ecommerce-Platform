function showPssword() {
	var input = event.target.parentNode.querySelector("input");
	if (input.type == "password") input.type = "text";
	else input.type = "password"
	event.target.classList.toggle("active")
}
var file = document.querySelector("input[type = 'file']")
var userImg = document.querySelector(".user-img img")
file.onchange = () => {
	var url = URL.createObjectURL(file.files[0])
	userImg.src = url
}
var p = document.querySelector(".message")
var firstName = document.querySelector(".first_name")
var firstNameValue = firstName.value
var lastName = document.querySelector(".last_name")
var lastNameValue = lastName.value
var email = document.querySelector(".email")
var emailValue = email.value
var password = document.querySelector(".password")
var rePassword = document.querySelector(".re-password")
var choices = document.querySelectorAll(".choice button")
var background = document.querySelector(".background")
var action = 'edit';
var oldSrc = userImg.src;
var btn = document.querySelector(".submit")

choices[0].onclick = () => {
	action = 'edit'
	background.style.left = "0"
	background.style.borderRadius = "5px 0px 0px 5px"
	choices[1].style.color = "#1d3557"
	choices[0].style.color = "#4361ee"
	p.style.display = "none"
	btn.innerText = "Save"
	userImg.src = oldSrc
	firstName.value = firstNameValue
	lastName.value = lastNameValue
	email.value = emailValue
	password.value = ''
	rePassword.value = ''
	file.value = ''

	

}
choices[1].onclick = () => {
	action = 'add'
	file.style.left = "0";
	background.style.left = "50%"
	background.style.borderRadius = "0px 5px 5px 0px"
	choices[0].style.color = "#1d3557"
	choices[1].style.color = "#4361ee"
	p.style.display = "none"
	userImg.src= "tmp.png"
	firstName.value = ''
	lastName.value = ''
	email.value = ''
	password.value = ''
	rePassword.value = ''
	file.value = ''
	
}


function submitFormAdmin(event) {
	event.preventDefault();
	var form = event.target
	var formData = new FormData(form);
	fetch(`settings_test.php?form=${action}`, {
			method: "POST",
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			if (data.status == 'error') {
				
				p.classList.remove('success')
				p.classList.add('error')
				
			} else {
				p.classList.remove('error')
				p.classList.add('success')
				if (action == 'edit')
			    {
					firstName = document.querySelector(".first_name")
					firstNameValue = firstName.value
					lastName = document.querySelector(".last_name")
					lastNameValue = lastName.value
					email = document.querySelector(".email")
					emailValue = email.value
					userImg = document.querySelector(".user-img img")
					oldSrc = userImg.src;
				}	
				else{
					var inputs = form.querySelectorAll("input")
					inputs.forEach(e=>e.value = '')
				}
			}
			p.innerText = data.message
			p.style.display = "block"
		})
		.catch(err => console.log(err));
}
function submitFormClientId(event) {
	event.preventDefault();
	var form = event.target
	var formData = new FormData(form);
	var action = 'client_id'
	var p = document.querySelector(".message-client-id")
	fetch(`settings_test.php?form=${action}`, {
			method: "POST",
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			if (data.status == 'error') {
				
				p.classList.remove('success')
				p.classList.add('error')
				
			} else {
				p.classList.remove('error')
				p.classList.add('success')
				form.querySelector("input[type='hidden']").value = data.id
			}
			p.innerText = data.message
			p.style.display = "block"
		})
		.catch(err => console.log(err));
}

function submitFormEmailSettings(event) {
	event.preventDefault()
	var form = event.target
	var formData = new FormData(form);
	var action = 'email_settings'
	var p = document.querySelector(".message-email-settings")
	fetch(`settings_test.php?form=${action}`, {
			method: "POST",
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			if (data.status == 'error') {
				
				p.classList.remove('success')
				p.classList.add('error')
				
			} else {
				p.classList.remove('error')
				p.classList.add('success')
				form.querySelector("input[type='hidden']").value = data.id
			}
			p.innerText = data.message
			p.style.display = "block"
		})
		.catch(err => console.log(err));
}

