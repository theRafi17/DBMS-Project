const barCtx = document.getElementById('analyticsBarChart').getContext('2d');
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Product A', 'Product B', 'Product C'],
    datasets: [
      { label: 'Sales', data: [50, 30, 20], backgroundColor: '#42A5F5' },
      { label: 'Demand', data: [40, 35, 25], backgroundColor: '#66BB6A' }
    ]
  }
});