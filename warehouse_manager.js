function displayWarehouseInfo() {
    
    const warehouseId = document.getElementById("warehouseId").value;
    const warehouseManagerId = document.getElementById("warehouseManagerId").value;
    
    document.getElementById("displayWarehouseId").innerText = warehouseId;
    document.getElementById("displayWarehouseManagerId").innerText = warehouseManagerId;
    
}