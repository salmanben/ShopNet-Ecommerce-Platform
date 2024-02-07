

function createItem(e)
{
    e.preventDefault();
    var message = e.target.parentNode.querySelector(".message")
    var form = e.target;
    formData =  new FormData(form)
    fetch('handle_actions.php?action=create',{
        method: 'POST',
        body: formData,     
    })
    .then(res=>res.json())
    .then(data=>{
        if (data.status == 'success')
         {
            message.classList.remove("error")
            message.classList.add("success")
            form.reset()
         }
         else
         {
            message.classList.remove("success")
            message.classList.add("error")
         }
         message.innerText = data.message
         scroll({top:0 , behavior:"smooth"})
		 message.style.display = "block"

    }) .catch(err=>console.log(err)) 
}

function updateItem(e)
{
    e.preventDefault();
    var message = e.target.parentNode.querySelector(".message")
    var form = e.target;
    formData =  new FormData(form)
    fetch('handle_actions.php?action=update',{
        method: 'POST',
        body: formData,     
    })
    .then(res=>res.json())
    .then(data=>{
         if (data.status == 'success')
         {
            message.classList.remove("error")
            message.classList.add("success")
         }
         else
         {
            message.classList.remove("success")
            message.classList.add("error")
         }
         message.innerText = data.message
         scroll({top:0 , behavior:"smooth"})
		 message.style.display = "block"

    }) .catch(err=>console.log(err)) 
}

function deleteItem(e)
{
    e.preventDefault();
    var tr = e.currentTarget.parentNode.parentNode;
    var id = tr.id
    fetch('handle_actions.php?action=delete',{
        method: 'POST',
        body: JSON.stringify({id:id}), 
        headers:{
            'Content-Type': 'application/json'
        }    
    })
    .then(res=>res.json())
    .then(data=>{
        if (data.status == 'success')
         {
            tr.remove()
         }
         else
         {
           alert(data.message)
         }

    }) .catch(err=>console.log(err)) 
}

function change_img(e) {
    var arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
    var product_img = document.querySelector(".product_img");
    var arr = e.target.files[0].name.split('.');
    
    if (arr_ext.indexOf(arr[arr.length - 1]) !== -1) {
        var src = URL.createObjectURL(e.target.files[0]);
        product_img.src = src;

    }
}

function switchStatus(e, id)
{
  var item_status = e.target.checked == true ? 1 : 0;
  fetch(`handle_actions.php?action=switch_status&id=${id}&status=${item_status}`)
  .then(res=>res.json())
  .then(data=>{
    if (data.status == 'error')
    {
      item_status == 1 ? e.target.checked = false : e.target.checked = true
      alert(data)
    }
  })
  .catch(err=>{
    console.log(err)
  })

}

function switch_order_status(event)
{
  var status = event.target.value
  var id = event.target.getAttribute('data-order-id')
  fetch(`handle_actions.php?status=${status}&id=${id}`)
  .then(res=>res.json())
  .then(data=>{
    if(data.status == 'error')
    {
      alert(data.message)
      event.target.value = "Dropped Off"
    }
  })
  .catch(err=>console.log(err))
}

function withdrawRequest(e)
{
  var message = e.target.parentNode.querySelector(".message")
  var inputAmount = e.target.previousElementSibling
  var amount = inputAmount.value
  if(!amount)
  {
    message.classList.remove("success")
    message.classList.add("error")
    message.innerText = "You must insert the amount!"
    message.style.display = "block"
    return
  }
  fetch(`handle_actions.php?amount=${amount}`)
  .then(res=>res.json())
  .then(data=>{
    console.log(data)
    if(data.status=='error')
    {
      message.classList.remove("success")
      message.classList.add("error")
    }
    else
    {
      message.classList.remove("error")
      message.classList.add("success")
      var tbody = document.querySelector(".tbody");
      tbody.innerHTML = `<tr class="item" id="${data.id}">
        <td>${data.id}</td>
        <td>\$${data.total}</td>
        <td>\$${data.amount}</td>
        <td>\$${data.charges}</td>
        <td><span class="request-status" style="background:#ffc107">${data.status}</span></td>
        <td>${data.date}</td>
       </tr>` + tbody.innerHTML;
    }
    message.innerText = data.message
    message.style.display = "block"
    inputAmount.value=''
    
    

  
 })
}