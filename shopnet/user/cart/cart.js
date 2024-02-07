var selectAll = document.querySelector(".select-all input")
var  selectProduct = document.querySelectorAll(".main ul input[type='checkbox']")
var total = document.querySelector(".total")
var  plus = document.querySelectorAll(".plus")
var moins = document.querySelectorAll(".moins")
var num = document.querySelector(".num")
var ul = document.querySelector(".main ul")

selectAll.onchange = ()=>{
    if(selectAll.checked == true)
    {
         selectProduct.forEach(e=>{
            e.checked = true;
        })
        getTotal()
    }
       
    else{
        selectAll.checked = false
         selectProduct.forEach(e=>{
            e.checked = false;
            total.innerHTML = 0
        })
    }

}
 selectProduct.forEach(e=>{
    e.onclick=()=>{
        getTotal()
        if(selectAll.checked == true)
           selectAll.checked = false
    }
 })
plus.forEach(e=>{
    e.onclick=()=>{
        var num = e.parentNode.querySelector(".num")
        var max = e.parentNode.querySelector(".num").getAttribute("data-count")
        var x = Number(num.innerText)
        if(x == max){
            alert("there is no more from this product")
            return;
        }
        num.innerText = x + 1
        getTotal()
    }
})
moins.forEach(e=>{
    e.onclick=()=>{
        {    
            var num = e.parentNode.querySelector(".num")
            var x = Number(num.innerText)
            if(x <= 1)
            return
            num.innerText = x - 1
            getTotal()
        }
        
    }
})
var select = document.querySelectorAll("select")
select.forEach(e=>e.onchange=()=>getTotal())
var arrData = []
function getTotal()
{
    var li = ul.querySelectorAll("li")
    var totalValue = 0
    li.forEach((e, i)=>{
        arrData[i] = {}
        var checkbox = e.querySelector("input[type='checkbox']")
        if (checkbox.checked == true)
        {
            arrData[i].id = e.id
            var price = Number(e.querySelector(".price").innerHTML)
            var qty = Number(e.querySelector(".num").innerHTML)
            arrData[i].qty = qty
            var select = e.querySelectorAll("select")
            arrData[i].variants = {}
            select.forEach(e=>{
                var options = e.querySelectorAll("option")
                for (let j = 0; j < options.length; j++)
                {
                    if (options[j].selected == true)
                    {
                        price += Number(options[j].getAttribute('data-price'))
                        arrData[i].variants[e.className] = {name: options[j].innerText, price: Number(options[j].getAttribute('data-price'))}
                        break
                    }
                }
                
            })
            arrData[i].unit_price = price
            totalValue += price * qty
        }
        
    })
    total.innerText = totalValue.toFixed(2)
    
}

function rmProduct(id)
{
        fetch(`rm_cart.php?id=${id}`)
        .then(res=>res.text())
        .then(data=>{
            if(data == 1){
                var product = document.getElementById(id)
                if(product.querySelector("input").checked == true){
                    price = product.querySelector(".price")
                    var num = product.querySelector(".num")
                    var t = Number(total.innerText)
                    t -= Number(price.innerText) * Number(num.innerText);
                    total.innerText = t.toFixed(2)
                }
                product.remove()
                var cart = document.querySelector("header .cart");
                var cartCount = Number(cart.getAttribute('cart-count'))
                cart.setAttribute('cart-count', cartCount - 1)
                if (cartCount - 1 == 0)
                   window.location.href = "../home/index.php"
            }
        })
}

var payerBtn = document.querySelector(".payerBtn")

payerBtn.onclick = () => {
    arrData = arrData.filter((e)=>Object.keys(e).length > 0)
	if (total.innerText == 0) {
		alert('you didn\'t select any product')
		return;
	}
	fetch('store_cart.php', {
			body: JSON.stringify({
				cart: arrData,
                subtotal: total.innerText
			}),
			method: 'post',
			headers: {
				'Content-Type': 'application/json'
			}
		})
		.then(res => res.text())
		.then(data => {
			if (data) {
				window.location.href = '../payment/payment.php'
			}
		})
}
