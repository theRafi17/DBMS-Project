// Function to update the total price based on quantity and price per kg
function updateTotalPrice() {
    const quantity = document.getElementById('quantity').value;
    const pricePerKg = document.getElementById('pricePerKg').value;

    // Check if values are numbers and calculate total price
    if (!isNaN(quantity) && !isNaN(pricePerKg)) {
        const totalPrice = quantity * pricePerKg;

        const totalPriceField = document.getElementById('totalPrice');
        if (totalPriceField) {
            totalPriceField.value = totalPrice; // Update the field
        } else {
            console.error('Total Price field not found!');
        }
    } else {
        console.error('Invalid input for quantity or price per kg!');
    }
}

// Function to add or update crop data
function addOrUpdate() {
    // Get form values
    const farmerId = document.getElementById('farmerId').value;
    const cropType = document.getElementById('cropType').value;
    const plantingDate = document.getElementById('plantingDate').value;
    const harvestDate = document.getElementById('harvestDate').value;
    const quantity = document.getElementById('quantity').value;
    const pricePerKg = document.getElementById('pricePerKg').value;
    const totalPrice = document.getElementById('totalPrice').value;

    // Validate fields
    if (!farmerId || !cropType || !plantingDate || !harvestDate || !quantity || !pricePerKg) {
        alert('Please fill all the fields.');
        return;
    }

    // Send data to PHP using AJAX (POST method)
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/farmer_dashboard/db.php', true); // Update the file path here
 // Ensure this is the correct PHP file
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText); // Parse the response as JSON

                if (response.success) {
                    alert(response.success);  // Show success message
                    loadCrops();
                } else if (response.error) {
                    alert(response.error);  // Show error message
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
                alert('Error processing the response.');
            }
        } else {
            alert('Error storing data.');
        }
    };

    // Data to send to the PHP file (via POST)
    const params = `farmerId=${farmerId}&cropType=${cropType}&plantingDate=${plantingDate}&harvestDate=${harvestDate}&quantity=${quantity}&pricePerKg=${pricePerKg}&totalPrice=${totalPrice}`;
    xhr.send(params);  // Send data to PHP
}



function loadCrops() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/farmer_dashboard/process.php', true);  // Assuming you have a `get_crops.php` file to fetch data
    xhr.onload = function () {
        if (xhr.status === 200) {
            const crops = JSON.parse(xhr.responseText);
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Clear the table

            crops.forEach(crop => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${crop.farmer_id}</td>
                    <td>${crop.crop}</td>
                    <td>${crop.planting_date}</td>
                    <td>${crop.harvest_date}</td>
                    <td>${crop.quantity}</td>
                    <td>${crop.price_per_kg}</td>
                    <td>${crop.total_price}</td>
                    <td><button onclick="editCrop(${crop.id})">Edit</button></td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            alert('Error loading crops.');
        }
    };
    xhr.send();
}

