
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
         scroll({top:0 , behavior:"smooth"})
         message.style.display = "block"
         message.innerText = data.message

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
            if(form.className == 'withdraw-method-form')
            {
              form.querySelector("input[type='hidden']").value = data.id
            }
         }
         else
         {
            message.classList.remove("success")
            message.classList.add("error")
         }
         if(!form.className == 'withdraw-method-form')
         {
           form.querySelector("input[type='hidden']").value = data.id
         }
         message.style.display = "block"
         message.innerText = data.message

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

function switchStatus(e, id)
{
  var item_status = e.target.checked == true ? 1 : 0;
  fetch(`handle_actions.php?action=switch_status&id=${id}&status=${item_status}`)
  .then(res=>res.json())
  .then(data=>{

    if (data.status != 'success')
    {
      item_status == 1 ? e.target.checked = false : e.target.checked = true
     alert(data.message)
    }
  })
  .catch(err=>{
    console.log(err)
  })

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

function changeValueOfStatus(event)
{
  var status = event.target.value
  var id = event.target.getAttribute('data-id')
  fetch(`handle_actions.php?status=${status}&id=${id}`)
  .then(res=>res.json())
  .catch(err=>console.log(err))
}