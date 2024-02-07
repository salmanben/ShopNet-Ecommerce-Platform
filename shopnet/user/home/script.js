var category_id = 0;
var queryParams = window.location.search;
if (queryParams)
{
   var arrParams = queryParams.slice(1).split('&')
   for (let i = 0; i < arrParams.length; i++)
   {
	if (arrParams[i].includes('category_id'))
	{
		var category_id = arrParams[i].split("=")[1]
	}
   }
}

var catHeader = document.querySelector(".categories h4")
var catList = document.querySelector(".categories ul")
var cat = catList.querySelectorAll("li")

cat.forEach((li, id) => {
	li.style.transition = (id + 2) / 10 + "s"
})
var bg = document.querySelector(".background")
catHeader.onclick = () => {
	catList.classList.toggle("active")
	cat.forEach(li => li.classList.toggle("active-all-li"))

	if (catList.classList.contains("active")) {
		catList.parentNode.style.zIndex = "2"
		bg.style.zIndex = "1"
	} else {
		catList.parentNode.style.zIndex = "1"
		bg.style.zIndex = "2"

	}

}


var seacrhInput = document.querySelector(".search input")
var section = document.querySelector("section")
var cards = document.querySelectorAll("section .card")
var noDataFound = document.querySelector(".no-data-found")
var viewMoreBtn = document.querySelector(".view-more")

if(cards.length == 0)
   noDataFound.style.display = "block"
else
   var offset = cards[cards.length - 1].getAttribute('id')

seacrhInput.onkeyup = function() {
	section.innerHTML = ""
	var val = seacrhInput.value.trim()
	fetch(`list_searched_products.php?searched=${val}&category_id=${category_id}`)
		.then(res => res.json())
		.then(data => {
			for (let i = 0; i < data.length; i++) {
				section.innerHTML += `<div class="card" id="${data[i].id}">
                    <div>
                        <a href="../product_details/product_details.php?id=${data[i].id}">
                            <img src="../../upload/${data[i].image}" alt=""> 
                        </a>
                        <h3>${data[i].title}</h3>
                        <p class="category">${data[i].name}</p>
                        <p class="price">Price:
                            <span>$${data[i].price}</span>
                        </p>
                    </div>
                    <div class="shop">
                        <a onclick="addToCart(event)" href="" class="add-cart" id ="${data[i].id}">
                            <i class="card-icon fas fa-shopping-bag"></i><span>Add to cart</span>
                        </a>
                    </div>
                    <a href="../product_details/product_details.php?id=${data[i].id}" class="preview">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </div>`;

			}
			if (data.length == 0) {
				section.append(noDataFound)
				noDataFound.style.display = "block"
			}
			else if (data.length == 40) {
                cards = document.querySelectorAll("section .card")
				viewMoreBtn.style.display = "block"
                section.append(viewMoreBtn)
				offset = cards[cards.length - 1].getAttribute('id')
			}
            
		})
		.catch(err => console.log(err));

}



var products = document.querySelector(".products")
var spans = document.querySelectorAll(" .container .spans span")
function fetchData() {
    fetch(`list_5_products.php?category_id=${category_id}`)
        .then(res => res.json())
        .then(data => {
            spans.forEach((e) => e.classList.remove("active"));
            spans[0].classList.add("active");
            products.innerHTML = '';
            if (data.length === 0)
                return;
            for (let i = 0; i < data.length; i++) {
                products.innerHTML += `
                    <div class="product">
					    <a href = "../product_details/product_details.php?id=${data[i].id}">
						   <img src="../../upload/${data[i].image}" alt=""> 
						</a>
                        <div>
                            <div class="text">
                                <h3>${data[i].title}</h3>
								<p class="short_description">${data[i].short_description}</p>
                                <p class="category">${data[i].name}</p>
                            </div>
                            <a onclick="addToCart(event)" class="add-cart" href=""  id ="${data[i].id}">
                              <i class="card-icon fas fa-shopping-bag"></i><span>Add to cart</span>
                            </a>
                        </div>
                    </div>`;
            }
        })
        .catch(err => console.log(err));
}
fetchData()
spans.forEach((e, id) => {
	e.onclick = () => {
		if (id > document.querySelectorAll(".products .product").length - 1)
			return
		spans.forEach(e => e.classList.remove("active"))
		e.classList.add("active")
		products.parentNode.scrollLeft = id * (200 + parseFloat(window.getComputedStyle(products).getPropertyValue("width")))
	}


})

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
                element.innerHTML = "<i style = \"font-size:20px;position:relative;left:4px\" class=\"fa-sharp fa-solid fa-check\"></i>"
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

if (cards.length == 40)
{
    viewMoreBtn.style.display = "block"
    offset = cards[cards.length - 1].getAttribute('id')
}
function viewMore(e) {
	element = e.currentTarget
    var url = ''
    if(seacrhInput.value)
       url = `list_searched_products.php?searched=${seacrhInput.value}&category_id=${category_id}&offset=${offset}`
    else
       url = `list_more_products.php?category_id=${category_id}&offset=${offset}`
	fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.length < 40) {
                element.style.display = "none"
            }
            for (let i = 0; i < data.length; i++) {
                section.innerHTML += `<div class="card" id="${data[i].id}">
                    <div>
                        <a href="../product_details/product_details.php?id=${data[i].id}">
                            <img src="../../upload/${data[i].image}" alt=""> 
                        </a>
                        <h3>${data[i].title}</h3>
                        <p class="category">${data[i].name}</p>
                        <p class="price">Price:
                            <span>$${data[i].price}</span>
                        </p>
                    </div>
                    <div class="shop">
                        <a onclick="addToCart(event)" href="" class="add-cart" id ="${data[i].id}">
                            <i class="card-icon fas fa-shopping-bag"></i><span>Add to cart</span>
                        </a>
                    </div>
                    <a href="../product_details/product_details.php?id=${data[i].id}" class="preview">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </div>`;
				
            }
			offset -= 40
        })
        .catch(err => console.log(err));
}
