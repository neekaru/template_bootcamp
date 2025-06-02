document.addEventListener('DOMContentLoaded', function () {
    const checkoutButton = document.getElementById('checkout-button'); // Adjust selector if needed

    if (checkoutButton) {
        checkoutButton.addEventListener('click', function () {
            const transactionId = this.dataset.transactionId;
            const snapTokenUrl = this.dataset.snapTokenUrl; // Get URL from data attribute

            if (!transactionId) {
                alert('Transaction ID not found. Please try again.');
                return;
            }

            if (!snapTokenUrl) {
                alert('Snap token URL not found. Please contact support.');
                return;
            }

            // Disable button to prevent multiple clicks
            checkoutButton.disabled = true;
            checkoutButton.textContent = 'Processing...';

            fetch(snapTokenUrl, { // Use the dynamic URL
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ transaction_id: transactionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    checkoutButton.disabled = false;
                    checkoutButton.textContent = 'Order Now'; // Or your preferred text
                    return;
                }

                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            alert("payment success!"); console.log(result);
                            // Implement redirection or UI update for success
                            // window.location.href = '/payment/success?order_id=' + result.order_id;
                        },
                        onPending: function(result){
                            alert("wating your payment!"); console.log(result);
                            // Implement redirection or UI update for pending
                            // window.location.href = '/payment/pending?order_id=' + result.order_id;
                        },
                        onError: function(result){
                            alert("payment failed!"); console.log(result);
                            checkoutButton.disabled = false;
                            checkoutButton.textContent = 'Order Now';
                            // Implement UI update for error
                        },
                        onClose: function(){
                            alert('You closed the popup without finishing the payment');
                            checkoutButton.disabled = false;
                            checkoutButton.textContent = 'Order Now';
                        }
                    });
                } else {
                    alert('Could not get payment token.');
                    checkoutButton.disabled = false;
                    checkoutButton.textContent = 'Order Now';
                }
            })
            .catch(error => {
                console.error('Error fetching snap token:', error);
                alert('An unexpected error occurred. Please try again.');
                checkoutButton.disabled = false;
                checkoutButton.textContent = 'Order Now';
            });
        });
    }
}); 