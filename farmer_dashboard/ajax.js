// Add event listeners to quantity and price per kg inputs to update the total price
document.getElementById("quantity").addEventListener("input", updateTotalPrice);
document.getElementById("pricePerKg").addEventListener("input", updateTotalPrice);

function updateTotalPrice() {
    const quantity = parseFloat(document.getElementById("quantity").value);
    const pricePerKg = parseFloat(document.getElementById("pricePerKg").value);
    const totalPriceField = document.getElementById("totalPrice");

    if (!isNaN(quantity) && !isNaN(pricePerKg)) {
        const totalPrice = quantity * pricePerKg;
        totalPriceField.value = totalPrice.toFixed(2);  // Format to 2 decimal places
    } else {
        totalPriceField.value = '';  // Clear total price if inputs are invalid
    }
}

// Function to add or update crop data
function addOrUpdate() {
    const farmerId = document.getElementById("farmerId").value;
    const crop = document.getElementById("cropType").value;
    const plantingDate = document.getElementById("plantingDate").value;
    const harvestDate = document.getElementById("harvestDate").value;
    const quantity = document.getElementById("quantity").value;
    const pricePerKg = document.getElementById("pricePerKg").value;
    const totalPrice = document.getElementById("totalPrice").value;

    // Check if all necessary fields are filled
    if (!farmerId || !crop || !plantingDate || !harvestDate || !quantity || !pricePerKg || !totalPrice) {
        alert("Please fill in all fields!");
        return;
    }

    const action = 'add';  // action could be 'update' for update operations

    // Create a FormData object to send data via AJAX
    let formData = new FormData();
    formData.append('action', action);
    formData.append('farmerId', farmerId);
    formData.append('crop', crop);
    formData.append('plantingDate', plantingDate);
    formData.append('harvestDate', harvestDate);
    formData.append('quantity', quantity);
    formData.append('pricePerKg', pricePerKg);
    formData.append('totalPrice', totalPrice);

    // Perform AJAX request
    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            alert("Data added successfully!");
            loadCrops(); // Reload crops data
        } else {
            alert("Error occurred!");
        }
    });
}

// Function to load crop data and display in the table
function loadCrops() {
    fetch('process.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'read'
        })
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById("tableBody");
        tableBody.innerHTML = "";  // Clear existing data
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.farmer_id}</td>
                <td>${row.crop}</td>
                <td>${row.planting_date}</td>
                <td>${row.harvest_date}</td>
                <td>${row.quantity}</td>
                <td>${row.price_per_kg}</td>
                <td>${row.total_price}</td>
                <td><button onclick="deleteCrop(${row.id})">Delete</button></td>
            `;
            tableBody.appendChild(tr);
        });
    });
}

// Function to delete a crop record
function deleteCrop(id) {
    if (confirm("Are you sure you want to delete this record?")) {
        let formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        fetch('process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                alert("Record deleted successfully!");
                loadCrops(); // Reload crops data
            } else {
                alert("Error occurred!");
            }
        });
    }
}

// Function to edit a crop record (populate the form with data for editing)
function editCrop(id) {
    fetch('process.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'read'
        })
    })
    .then(response => response.json())
    .then(data => {
        const cropData = data.find(row => row.id === id);

        // Populate the form with the data of the selected crop
        document.getElementById("farmerId").value = cropData.farmer_id;
        document.getElementById("cropType").value = cropData.crop;
        document.getElementById("plantingDate").value = cropData.planting_date;
        document.getElementById("harvestDate").value = cropData.harvest_date;
        document.getElementById("quantity").value = cropData.quantity;
        document.getElementById("pricePerKg").value = cropData.price_per_kg;
        document.getElementById("totalPrice").value = cropData.total_price;

        // Change the action to update the record
        document.getElementById("action").value = 'update';
    });
}

// Function to update a crop record
function updateCrop() {
    const farmerId = document.getElementById("farmerId").value;
    const crop = document.getElementById("cropType").value;
    const plantingDate = document.getElementById("plantingDate").value;
    const harvestDate = document.getElementById("harvestDate").value;
    const quantity = document.getElementById("quantity").value;
    const pricePerKg = document.getElementById("pricePerKg").value;
    const totalPrice = document.getElementById("totalPrice").value;

    const action = 'update';

    let formData = new FormData();
    formData.append('action', action);
    formData.append('farmerId', farmerId);
    formData.append('crop', crop);
    formData.append('plantingDate', plantingDate);
    formData.append('harvestDate', harvestDate);
    formData.append('quantity', quantity);
    formData.append('pricePerKg', pricePerKg);
    formData.append('totalPrice', totalPrice);

    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            alert("Data updated successfully!");
            loadCrops(); // Reload crops data
        } else {
            alert("Error occurred!");
        }
    });
}

// Load the crops when the page loads
window.onload = loadCrops;
