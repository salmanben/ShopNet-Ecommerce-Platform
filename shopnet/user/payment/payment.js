var totalValue = document.querySelector(".total-value")
var discountValue = document.querySelector(".discount-value")
var subtotalValue = document.querySelector(".subtotal-value")
var shippingFee = document.querySelector(".shipping-fee")
function applyCoupon()
{
    var couponCode = document.querySelector(".coupon-code")
    if(couponCode.value == '')
    {
        alert('Please, insert coupon code.')
        return
    }
    fetch('apply_coupon.php',
    {
        method: 'post',
        body: JSON.stringify({code:couponCode.value}),
        headers:{
            'Content-Type':'application/json'
        }
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status == 'success')
        {
           discountValue.innerText = data.discount
           totalValue.innerText = (data.total + Number(shippingFee.innerText)).toFixed(2)
        }
        else
        {
            alert(data.message)
            couponCode.value = ''
            discountValue.innerText = 0
            totalValue.innerText =(Number(subtotalValue.innerText) + Number(shippingFee.innerText)).toFixed(2)
        }
    }) 
    
}
var shippingMethods = document.querySelectorAll(".shipping input[type='radio']")
var shippingMethodId = document.querySelector(".shipping-method-id")
shippingMethods.forEach(e=>{
    e.onclick = ()=>{
        var cost = Number(e.getAttribute('data-cost'))
        shippingFee.innerText = cost
        totalValue.innerText = (Number(subtotalValue.innerText) + cost - Number(discountValue.innerText)).toFixed(2)
        shippingMethodId.value = e.value
    }
})

var form = document.querySelector("form")
var errorMsg = document.querySelector(".error")
var confirm = document.querySelector(".confirm")
form.onsubmit = (e)=>{
    e.preventDefault()
    var inputs = form.querySelectorAll('input:not([type="hidden"])')
    for (let i = 0; i < inputs.length; i++)
    {
        if(!inputs[i].value)
        {
            errorMsg.style.display = "block";
            errorMsg.innerText = 'You must fill all the fields.'
            window.scrollTo({top:0, behavior:'smooth'})
            return
        }
    }
    var selected = false
    shippingMethods.forEach(e=>
    {
        if(e.checked)
        {
            selected = true
        }
    })
    if(!selected)
    {
        alert("You must select shipping method!")
        return
    }
    form = document.querySelector("form")
    var formData = new FormData(form)
    fetch('store_session.php',
    {
         method: 'post',
         body: formData
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status = 'success')
        {
           window.location.href = "finish_payment.php"
        }
        else{
            alert('Something went wrong. please try again')
        }
    }).catch(err=>console.log(err))

    

} 
