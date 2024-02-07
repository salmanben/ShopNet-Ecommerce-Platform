
const totalOrders = document.querySelector('.total-orders-value');
const completedOrders = document.querySelector('.completed-orders-value');
const pendingOrders = document.querySelector('.pending-orders-value');
const totalEarnings = document.querySelector('.total-earnings-value');
const totalProducts = document.querySelector('.total-products-value');


var dates = document.querySelectorAll('.date input[type="date"]')
var startDate = document.querySelector(".start-date");
var endDate = document.querySelector(".end-date");
dates.forEach((e, id) => {
  e.onchange = () => {
    startDate = document.querySelector(".start-date");
    endDate = document.querySelector(".end-date");
    if (startDate.value && endDate.value) {
      if (new Date(startDate.value).getTime() - new Date(endDate.value).getTime() > 0)
      {
        endDate.value = ''
        startDate.value = ''
        totalOrders.innerText = "..."
        completedOrders.innerText = "..."
        pendingOrders.innerText = "..."
        totalEarnings.innerText = "..."
        totalProducts.innerText = "..."
        alert("The end date must be after the start date!")
        return
      }
        var obj = {
        startDate: startDate.value,
        endDate: endDate.value
      }
      fetch("get_statistics.php", {
        method: 'POST',
        body: JSON.stringify(obj),
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(res => res.json())
        .then(data => {
          totalOrders.innerText = data.total_orders;
          completedOrders.innerText = data.completed_orders;
          pendingOrders.innerText = data.pending_orders;
          totalEarnings.innerText = "$"+data.total_earnings;
          totalProducts.innerText = data.total_products;
        });
    }
  };
});


var orderCtx = document.getElementById("orders").getContext('2d');
var orderStatusCtx = document.getElementById("orders-status").getContext('2d');
var earningsCtx = document.getElementById("earnings").getContext('2d');

var bgYear = [
  '#9381ff',
  '#06d6a0',
  '#4361ee',
  '#ef476f',
  '#ffd60a',
  '#03045e',
  '#e1e5f2',
  '#9381ff',
  '#06d6a0',
  '#4361ee',
  '#ef476f',
  '#ffd60a'
];

const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December"
];


fetch('get_statistics.php')
.then(res=>res.json())
.then(data=>{
  for (var i = data['orders'].length; i < 12; i++)
  {
    data['orders'][i] = 0
  }
  for (var i = data['earnings'].length; i < 12; i++)
  {
    data['earnings'][i] = 0
  }

  var chartOrders = new Chart(
    orderCtx, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Orders',
          data: data['orders'],
          backgroundColor: bgYear,
        }]
      }
    }
  );
  
  var chartEarning = new Chart(
    earningsCtx, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Earning',
          data: data['earnings'],
          backgroundColor: bgYear
        }]
      }
    }
  );
  
  var chartOrdersStatus = new Chart(
    orderStatusCtx, {
      type: 'doughnut',
      data: {
        labels: ["Completed", "Pending", "Dropped Off"], 
        datasets: [{
          data: data['orders_status'],
          backgroundColor: ["#0ABF30", "#E9BD0C", "orange"]
        }]
      },
    }
  );
})
