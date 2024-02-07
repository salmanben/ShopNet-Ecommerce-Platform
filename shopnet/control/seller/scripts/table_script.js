var searchInput = document.querySelector(".search input")
var searchIcon = document.querySelector(".search i")
var tbody = document.querySelector(".tbody")
var items = document.querySelectorAll(".item")
searchInput.onkeyup = () => {
  tbody.innerHTML = ""
  var val = searchInput.value.trim()
  items.forEach(e => {
    var x = e.id;
    if (x.startsWith(val)) 
    tbody.append(e)
  })
}
searchInput.onsearch = () => {
  tbody.innerHTML = ""
  items.forEach(e => {
    tbody.append(e)
  })
}
var img = document.querySelectorAll("table td img")
img.forEach(e=>{
    e.onclick = ()=>{
      if(e.requestFullscreen)
      e.requestFullscreen()
    }
  })