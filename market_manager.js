// const barCtx = document.getElementById('analyticsBarChart').getContext('2d');
// new Chart(barCtx, {
//   type: 'bar',
//   data: {
//     labels: ['Product A', 'Product B', 'Product C'],
//     datasets: [
//       { label: 'Sales', data: [50, 30, 20], backgroundColor: '#42A5F5' },
//       { label: 'Demand', data: [40, 35, 25], backgroundColor: '#66BB6A' }
//     ]
//   }
// });

fetch('http://localhost:3000/DBMS-Project/Demand.html')
    .then(response => response.json())
    .then(data => {
        // Update overview section
        document.getElementById('totalProducts').textContent = data.overview.total_products;
        document.getElementById('totalDemand').textContent = data.overview.total_demand;
        document.getElementById('avgPrice').textContent = `$${data.overview.avg_price.toFixed(2)}`;
        document.getElementById('highDemandProduct').textContent = data.highDemandProduct;
        document.getElementById('lowDemandProduct').textContent = data.lowDemandProduct;

        // Update charts
        const products = data.chartData.map(item => item.product_name);
        const demands = data.chartData.map(item => item.demand_quantity);
        const prices = data.chartData.map(item => item.price);

        demandChart.data.labels = products;
        demandChart.data.datasets[0].data = demands;
        demandChart.update();

        priceChart.data.labels = products;
        priceChart.data.datasets[0].data = prices;
        priceChart.update();
    })
    .catch(error => console.error('Error fetching data:', error));
