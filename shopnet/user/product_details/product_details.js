var cart = document.querySelector(".cart")
function addToCart(event)
{
    event.preventDefault()
    var element = event.currentTarget
    var idElement = element.id
    fetch(`../cart/add_cart.php?id=${idElement}`)
        .then(res => res.json())
        .then(data => {
            if (data.status == 'success') {
                element.innerHTML = "<i style = \"font-size:20px;position:relative;left:2px\" class=\"fa-sharp fa-solid fa-check\"></i>"
                if (data.already_added == 0)
                    cart.setAttribute('cart-count', Number(cart.getAttribute('cart-count')) + 1)
            }
            else{
                if (data.redirect)
                {
                    window.location.href= data.redirect;
                    return
                }
                alert(data.message)
            }
        }).catch(err => console.log(err))
}
