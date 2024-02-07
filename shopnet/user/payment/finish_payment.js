var totalValue = Number(document.querySelector(".total-value").innerText);
paypal.Buttons({
    // Function to create the order
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [
                {
                    amount: {
                        value: totalValue
                    }
                }
            ]
        });
    },
    // Function called when the payment is approved
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            fetch('success_payment.php', {
                method: 'post',
                body: JSON.stringify({ invoiceId: details.id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(newData => {
                if (newData.status === 'success') {
                    alert('Payment Succeeded');
                    setInterval(() => window.location.href = "../home/index.php", 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    },
    
    // Function called when the payment is cancelled
    onCancel: function(data) {
        alert("Payment cancelled."); // Display an alert box with a message
    },
    // Function called when an error occurs during payment
    onError: function(err) {
        alert("An error occurred. Please try again later."); // Display an alert box with an error message
        console.error(err); // Log the error to the console
    }
}).render('#paypal-button-container');
