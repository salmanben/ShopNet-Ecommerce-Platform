function showPssword() {
    var input = event.target.parentNode.querySelector("input");
    if (input.type == "password") input.type = "text";
    else input.type = "password"
    event.target.classList.toggle("active")
}
var imgFile = document.querySelector(".img-file")
var userImg = document.querySelector(".user-img img")
imgFile.onchange = () => {
    var url = URL.createObjectURL(imgFile.files[0])
    userImg.src = url
}
var banner= document.querySelector(".banner")
var bannerFile = document.querySelector(".banner-file")
bannerFile.onchange = () => {
    var url = URL.createObjectURL(bannerFile.files[0])
    banner.src = url
}


var p = document.querySelector(".message")
function submitForm(event) {
	event.preventDefault();
	var formData = new FormData(event.target);
	fetch(`settings_s_test.php`, {
			method: "POST",
			body: formData
		})
		.then(res => res.text())
		.then(data => {
			if (data != 1) {
				
				p.innerText = data
                p.classList.remove('success')
				p.classList.add('error')
				p.style.display = "block"
			} else {
				p.classList.remove('error')
				p.classList.add('success')
				p.innerHTML = "Your new informations have been saved successfully."
				p.style.display = "block"

			}
		})
		.catch(err => console.log(err));
}
