var searchFollowed = document.querySelector(".search-followed input")
var listFollowed = document.querySelector(".list-followed")
var boxes = listFollowed.querySelectorAll(".box")
var username  = document.querySelectorAll(".username")
var message = document.querySelector(".message p")
var numFollowed = boxes.length
if (numFollowed == 0)
{
    searchFollowed.parentNode.style.display = "none"
    message.innerText = "You don't follow any seller."
    message.parentNode.style.display = "block"
}

searchFollowed.onkeyup =()=>{
    message.parentNode.style.display = "none"
   listFollowed.innerHTML = ""
   var val = searchFollowed.value.toLowerCase()
   username.forEach(e=>{
       var x = e.innerText.toLowerCase()
       if(x.includes(val))
          listFollowed.append(e.parentNode.parentNode)
   })
   if (listFollowed.innerHTML == '')
   {
    message.innerText = "No seller found."
    message.parentNode.style.display = "block"
   }
}
searchFollowed.onsearch =()=>{
    if(listFollowed.length == "0")
      return
    listFollowed.innerHTML = ""
    username.forEach(e=>{
       listFollowed.append(e.parentNode.parentNode)
   })
   message.parentNode.style.display = "none"
}

function remove(id){
 event.preventDefault()
 var element = event.target;
  fetch(`rm_followed.php?id=${id}`)
  .then(res=>res.json())
  .then(data=>{
    if(data.status == 'success'){
        numFollowed--;
        element.parentNode.parentNode.remove()
        if(numFollowed == 0){
            message.innerText = "You don't follow any seller."
            message.parentNode.style.display = "block"
            searchFollowed.parentNode.style.display = "none"
        }
    }
    else{
        alert(data.message)
    }
  })
  .catch(err=>console.log(err))
  

}