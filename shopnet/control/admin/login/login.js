function showPssword() {
	var input = event.target.parentNode.querySelector("input");
	if (input.type == "password")
		input.type = "text";
	else
		input.type = "password"
	event.target.classList.toggle("active")


}

var p = document.querySelector(".error")

var form = document.querySelector("form");
form.addEventListener('submit', submitForm)

function submitForm(event) {
	event.preventDefault();
	var formData = new FormData(form);
	fetch(`Login_test.php`, {
			method: "POST",
			body: formData
		})
		.then(res => res.text())
		.then(data => {
			if (data != 1) {
				p.style.display = "block"
				p.innerText = data
			} else {
				window.location.href = "../dashboard/dashboard.php"

			}
		})
		.catch(err => console.log(err));
}
var forgot = document.querySelector(".forgot")
var password = document.querySelector("input[type='password']")
var email = document.querySelector("input[type='email']")
var val_email = '';
forgot.onclick = () => {
	p.style.display = "block"
	p.style.background = '#4361ee'
	p.style.color = "#fff"
	p.innerText = "Enter you email."
	form.innerHTML = `
   <div><input type="email" name="email" placeholder="Email" id=""></div>
   <button>Next</button>
                    `

	email = document.querySelector("input[type='email']")
	email.style.width = "100%"
	form.removeEventListener('submit', submitForm)
	form.addEventListener('submit', submitEmail)

	function submitEmail() {
		event.preventDefault()
		var formData = new FormData(form);
		fetch(`forgot_info.php?check=email`, {
				method: "POST",
				body: formData
			})
			.then(res => res.text())
			.then(data => {
				if (data != 1) {
					p.style.display = "block"
					p.innerText = data
					p.style.background = '#f8d7da'
					p.style.color = "#721c24"
				} else {
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
					for (var i = 0; i < 7; i++) {
						code[i] = randomArray[Math.floor(Math.random() * randomArray.length)]
					}
					code = code.join('')
					val_email = email.value
					fetch('email.php', {
							method: 'POST',
							body: JSON.stringify({
								email: val_email,
								code: code
							}),
							header: {
								'Content-Type': 'application/json'
							}
						})
						.then(res => res.text())
						.then(data => {
					        p.style.background = '#4361ee'
		                    p.style.color = "#fff"
					        p.innerText = 'Check your email for verification code.';
							form.innerHTML = `
                                <input style = "width:100%; margin-bottom:10px" type="text" name="text" placeholder="Enter verification code" id="">
                                <button>Send</button>
                                                 `

					        form.removeEventListener('submit', submitEmail)
							form.onsubmit = () => testCode(code)
						});
				}
			})
			.catch(err => console.log(err));
	}

}

function testCode(code) {
	var value = document.querySelector("input").value
	event.preventDefault();
	if (value == '') {
		p.innerText = 'You must fill the field.'
		p.style.background = '#f8d7da'
		p.style.color = "#721c24"
		return
	}
	if (code == value.trim()) {
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
		p.style.background = '#4361ee'
		p.style.color = "#fff"
	} else {
		p.style.background = '#f8d7da'
		p.style.color = "#721c24"
		p.innerText = 'The verification code is not correct.'
	}


}

function testPassword() {
	event.preventDefault()
	var formData = new FormData(form);
	fetch(`forgot_info.php?check=password&email=${val_email}`, {
			method: "POST",
			body: formData
		})
		.then(res => res.text())
		.then(data => {
			if (data != 1) {
				p.style.background = '#f8d7da'
				p.style.color = "#721c24"
				p.innerText = data
			} else {
				p.style.display = "none"
				window.location.href = "login.php"

			}
		})
		.catch(err => console.log(err));

}