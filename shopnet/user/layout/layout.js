
var faBars = document.querySelector(".fa-bars")
var faX = document.querySelector(".fa-x")
var nav = document.querySelector("nav")
faBars.onclick = () => {
	nav.style.height = "128px";
	faBars.style.transform = "scale(0)"
	faX.style.transform = "scale(1)"
	nav.style.overflow = "visible";
}

faX.onclick = () => {
	nav.style.height = "0";
	nav.style.overflow = "hidden";
	faBars.style.transform = "scale(1)"
	faX.style.transform = "scale(0)"
}
