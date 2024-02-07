var searchPartner = document.querySelector(".search-partner")
var listPartners = document.querySelector(".list-partners")
var partners = listPartners.querySelectorAll("li")
var t_getPartenrs = setInterval(getPartenrs, 1000)

searchPartner.onkeyup = () => {
	clearInterval(t_getPartenrs)
	var val = searchPartner.value.toLocaleLowerCase()
	listPartners.innerHTML = ""
	partners.forEach(e => {
		var name = e.querySelector("h4").innerHTML.toLocaleLowerCase()
		if (name.includes(val))
			listPartners.append(e)

	})
	if(val == '')
	   t_getPartenrs = setInterval(getPartenrs, 1000)
	
	
}

searchPartner.onsearch = () => {
	listPartners.innerHTML = ""
	partners.forEach(e => {
		listPartners.append(e)
	})
	t_getPartenrs = setInterval(getPartenrs, 1000)
}
var chat = document.querySelector(".chat")
var messages = document.querySelector(".messages")
var empty = document.querySelector(".empty")
var send = document.querySelector(".send")
var chatHeaderTitle = chat.querySelector(".header h4")
var chatHeaderImg = chat.querySelector(".header img")
var faLeftLong = document.querySelector(".fa-left-long")
var inputMsg = chat.querySelector("input[type='text']")
var t_send_msg
var idPartner

function accessChat(e) {
    clearInterval(t_send_msg);
    partners = listPartners.querySelectorAll("li");
    partners.forEach(e => e.classList.remove("active"));
    e.currentTarget.classList.add("active");
    chatHeaderTitle.innerText = e.currentTarget.querySelector("h4").innerText;
    chatHeaderImg.src = e.currentTarget.querySelector("img").src;
    chatHeaderImg.style.display = "block";
    chatHeaderTitle.style.display = "block";
    empty.style.display = "none";
    send.style.display = "flex";
    messages.style.display = "block";
    if (window.innerWidth <= 700) {
        chat.style.display = "block";
        listPartners.parentNode.style.display = "none";
        faLeftLong.style.display = "block";
    }
    idPartner = e.currentTarget.id;
    fetchMessages(idPartner);
    t_send_msg = setInterval(() => fetchMessages(idPartner), 1000);
}

function fetchMessages(id) {
    inputMsg = chat.querySelector("input[type='text']");
    fetch(`fetch_insert_msg.php?id=${id}`)
    .then(res => res.text())
    .then(data => {
        messages.innerHTML = data;
        messages.scrollTo({
            top: messages.scrollHeight,
            behavior: 'instant'
        });
    })
    .catch(err => console.log(err));
}

messages.addEventListener('mouseover', () => clearInterval(t_send_msg));
messages.addEventListener('mouseleave', () => t_send_msg = setInterval(() => fetchMessages(idPartner), 1000));
inputMsg.onkeyup = (event) => {
	if (event.keyCode === 13) {
		sendBtn.click();
	}
};
var sendBtn = document.querySelector(".sendBtn")
sendBtn.addEventListener("click", sendMsg)
var newPartner

function sendMsg() {
	if (inputMsg.value != "") {

		fetch("fetch_insert_msg.php", {
			method: 'POST',
			body: JSON.stringify({
			  id: idPartner,
			  msg: inputMsg.value,
			}),
			headers: {
			  'Content-Type': "application/json"
			}
		  })
            .then(res => res.text())
			.then(data => {
				if (data == 1) {
					messages.innerHTML += `<p class ="to">${inputMsg.value}</p>`;
					messages.scrollTo(0, messages.scrollHeight)
				    var currentPartner = document.getElementById(idPartner)
					if (currentPartner)
					{
						currentPartner.querySelector('p').innerText = inputMsg.value
						currentPartner.querySelector('.date').innerText = new Date().toString().substring(0, 15)
						listPartners.prepend(currentPartner)
					}
				    else
					{
						newPartner  = `<li class="buyer active" id="${idPartner}" onclick="accessChat(event)">
			                              <img src="${chatHeaderImg.src}">
			                              <h4>${chatHeaderTitle.innerText}</h4>
			                               <span class="date">${new Date().toString().substring(0, 15)}</span>
			                              <p>${inputMsg.value}</p>
		                               </li>`
						listPartners.innerHTML = newPartner + listPartners.innerHTML
					}
					inputMsg.value = "";
				}
			}).catch(err => console.log(err))
	}


}
faLeftLong.onclick = () => {
	chat.style.display = "none";
	listPartners.parentNode.style.display = "block";

}

function getPartenrs() {
	fetch(`fetch_partners.php`)
		.then(res => res.json())
		.then(data => {
			listPartners.innerHTML = '';
				for (i = 0; i < data.length; i++) {
					listPartners.innerHTML +=  `<li class="buyer" id="${data[i].buyer_id}" onclick="accessChat(event)">
					                              <img src="buyer.png">
					                              <h4>${data[i].username}</h4>
					                              <span class="date">${new Date(data[i].date).toString().substring(0, 15)}</span>
					                              <p>${data[i].message}</p>
				                                </li>`;
		   }
		   var currentPartner = document.getElementById(idPartner)
		   if(currentPartner)
		      currentPartner.classList.add("active")
	     }
		)
}