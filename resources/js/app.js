import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Start Livewire
Livewire.start()

import './bootstrap';

// Import Chart.js and make it globally available
import { Chart,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  RadialLinearScale,
  LineController,
  BarController,
  DoughnutController,
  PieController,
  Filler
} from 'chart.js';

// Register Chart.js components
Chart.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  RadialLinearScale,
  LineController,
  BarController,
  DoughnutController,
  PieController,
  Filler
);

window.Chart = Chart;

// Import flatpickr
import flatpickr from 'flatpickr';

// Define Chart.js default settings safely
if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = '"Inter", sans-serif';
    Chart.defaults.font.weight = 500;

    // Set tooltip defaults safely
    if (Chart.defaults.plugins && Chart.defaults.plugins.tooltip) {
        Chart.defaults.plugins.tooltip.borderWidth = 1;
        Chart.defaults.plugins.tooltip.displayColors = false;
        Chart.defaults.plugins.tooltip.mode = 'nearest';
        Chart.defaults.plugins.tooltip.intersect = false;
        Chart.defaults.plugins.tooltip.position = 'nearest';
        Chart.defaults.plugins.tooltip.caretSize = 0;
        Chart.defaults.plugins.tooltip.caretPadding = 20;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.padding = 8;
    }

    // Function that generates a gradient for line charts
    const chartAreaGradient = (ctx, chartArea, colorStops) => {
        if (!ctx || !chartArea || !colorStops || colorStops.length === 0) {
            return 'transparent';
        }
        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
        colorStops.forEach(({ stop, color }) => {
            gradient.addColorStop(stop, color);
        });
        return gradient;
    };

    // Make it available globally if needed
    window.chartAreaGradient = chartAreaGradient;

    // Register Chart.js plugin to add a bg option for chart area
    Chart.register({
        id: 'chartAreaPlugin',
        beforeDraw: (chart) => {
            if (chart.config.options.chartArea && chart.config.options.chartArea.backgroundColor) {
                const ctx = chart.canvas.getContext('2d');
                const { chartArea } = chart;
                ctx.save();
                ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
                ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
                ctx.restore();
            }
        },
    });
}

document.addEventListener('DOMContentLoaded', () => {
  // Light switcher
  const lightSwitches = document.querySelectorAll('.light-switch');
  if (lightSwitches.length > 0) {
    lightSwitches.forEach((lightSwitch, i) => {
      if (localStorage.getItem('dark-mode') === 'true') {
        lightSwitch.checked = true;
      }
      lightSwitch.addEventListener('change', () => {
        const { checked } = lightSwitch;
        lightSwitches.forEach((el, n) => {
          if (n !== i) {
            el.checked = checked;
          }
        });
        document.documentElement.classList.add('**:transition-none!');
        if (lightSwitch.checked) {
          document.documentElement.classList.add('dark');
          document.querySelector('html').style.colorScheme = 'dark';
          localStorage.setItem('dark-mode', true);
          document.dispatchEvent(new CustomEvent('darkMode', { detail: { mode: 'on' } }));
        } else {
          document.documentElement.classList.remove('dark');
          document.querySelector('html').style.colorScheme = 'light';
          localStorage.setItem('dark-mode', false);
          document.dispatchEvent(new CustomEvent('darkMode', { detail: { mode: 'off' } }));
        }
        setTimeout(() => {
          document.documentElement.classList.remove('**:transition-none!');
        }, 1);
      });
    });
  }
  // Flatpickr
  flatpickr('.datepicker', {
    mode: 'range',
    static: true,
    monthSelectorType: 'static',
    dateFormat: 'M j, Y',
    defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
    prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
    nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
    onReady: (selectedDates, dateStr, instance) => {
      // eslint-disable-next-line no-param-reassign
      instance.element.value = dateStr.replace('to', '-');
      const customClass = instance.element.getAttribute('data-class');
      instance.calendarContainer.classList.add(customClass);
    },
    onChange: (selectedDates, dateStr, instance) => {
      // eslint-disable-next-line no-param-reassign
      instance.element.value = dateStr.replace('to', '-');
    },
  });
  // dashboardCard01();
  // dashboardCard02();
  // dashboardCard03();
  // dashboardCard04();
  // dashboardCard05();
  // dashboardCard06();
  // dashboardCard08();
  // dashboardCard09();
  // dashboardCard11();

  // Store chart instances to avoid duplicates
  let chartInstances = {};

  // Function to destroy all charts
  window.destroyAllCharts = function() {
    Object.keys(chartInstances).forEach(key => {
      if (chartInstances[key]) {
        try {
          chartInstances[key].destroy();
          delete chartInstances[key];
        } catch (e) {
          console.log(`Error destroying ${key} chart:`, e);
        }
      }
    });
    chartInstances = {};
  }

  // Function to initialize all charts - make globally available
  window.initializeCharts = function() {
    console.log('Initializing charts...');
    console.log('Chart.js available:', typeof Chart);

    // Common configuration
    if (typeof Chart !== 'undefined') {
      Chart.defaults.font.family = '"Inter", system-ui, sans-serif';
      Chart.defaults.color = '#6b7280';
      Chart.defaults.responsive = true;
      Chart.defaults.maintainAspectRatio = false;
    }

    // Function to reset canvas attributes
    const resetCanvas = (canvasId) => {
      const canvas = document.getElementById(canvasId);
      if (canvas) {
        // Remove any width/height attributes
        canvas.removeAttribute('width');
        canvas.removeAttribute('height');
        canvas.style.width = '';
        canvas.style.height = '';
        // Ensure display block
        canvas.style.display = 'block';
      }
    };

    // Wait a bit for DOM to be ready
    setTimeout(() => {
      // Destroy all existing charts first
      window.destroyAllCharts();

      // Production Trends Chart
      const productionCtx = document.getElementById('productionTrendsChart');
      if (productionCtx && typeof Chart !== 'undefined') {
        console.log('Creating Production Trends Chart...');
        resetCanvas('productionTrendsChart');

        // Use real data from server
        const prodData = window.chartData?.productionData || {};
        const salesData = window.chartData?.salesData || {};
        const labels = Object.keys(prodData);
        const productionValues = Object.values(prodData);
        const salesValues = labels.map(month => salesData[month] || 0);

        chartInstances.production = new Chart(productionCtx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
          datasets: [{
            label: 'Produksi (KG)',
            data: productionValues.length ? productionValues : [45000, 52000, 48000, 61000, 58000, 67000],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
          }]
          /* Commented dummy data for future testing
          , {
            label: 'Produksi Olahan (KG)',
            data: [12000, 14500, 13000, 16000, 15500, 18000],
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' KG';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }

    // Sales vs Production Chart
    const salesProdCtx = document.getElementById('salesProductionChart');
    if (salesProdCtx && typeof Chart !== 'undefined') {
      resetCanvas('salesProductionChart');

      // Use real data from server
      const prodData = window.chartData?.productionData || {};
      const salesData = window.chartData?.salesData || {};
      const labels = Object.keys(prodData);
      const productionValues = Object.values(prodData);
      const salesValues = labels.map(month => salesData[month] || 0);

      const salesProdChart = chartInstances.salesProduction = new Chart(salesProdCtx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
          datasets: [{
            label: 'Produksi (KG)',
            data: productionValues.length ? productionValues : [45000, 52000, 48000, 61000, 58000, 67000],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            yAxisID: 'y'
          }, {
            label: 'Penjualan (Rp)',
            data: salesValues.length ? salesValues : [135000000, 156000000, 144000000, 183000000, 174000000, 201000000],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            yAxisID: 'y1'
          }]
          /* Commented dummy data for future testing
          datasets: [{
            label: 'Produksi (KG)',
            data: [45000, 52000, 48000, 61000, 58000, 67000],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            yAxisID: 'y'
          }, {
            label: 'Penjualan (Rp)',
            data: [135000000, 156000000, 144000000, 183000000, 174000000, 201000000],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            yAxisID: 'y1'
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  if (context.datasetIndex === 0) {
                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' KG';
                  } else {
                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                  }
                }
              }
            }
          },
          scales: {
            y: {
              display: true,
              position: 'left',
              title: {
                display: true,
                text: 'Produksi (KG)'
              }
            },
            y1: {
              display: true,
              position: 'right',
              title: {
                display: true,
                text: 'Penjualan (Rp)'
              },
              grid: {
                drawOnChartArea: false
              }
            }
          }
        }
      });

      // Chart type toggle buttons
      const lineBtn = document.getElementById('chartTypeLine');
      const barBtn = document.getElementById('chartTypeBar');

      if (lineBtn && barBtn) {
        lineBtn.addEventListener('click', function() {
          salesProdChart.config.type = 'line';
          salesProdChart.update();
          lineBtn.className = 'px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors';
          barBtn.className = 'px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors';
        });

        barBtn.addEventListener('click', function() {
          salesProdChart.config.type = 'bar';
          salesProdChart.update();
          barBtn.className = 'px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors';
          lineBtn.className = 'px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors';
        });
      }
    }

    // Financial Flow Chart
    const financialCtx = document.getElementById('financialFlowChart');
    if (financialCtx && typeof Chart !== 'undefined') {
      resetCanvas('financialFlowChart');

      // Use real data from server
      const financialData = window.chartData?.financialData || {};
      const labels = financialData.months || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
      const incomeData = financialData.income || [200000000, 225000000, 190000000, 245000000, 210000000, 260000000];
      const expenseData = financialData.expense || [150000000, 165000000, 140000000, 180000000, 160000000, 190000000];

      chartInstances.financial = new Chart(financialCtx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pemasukan',
            data: incomeData,
            backgroundColor: 'rgba(16, 185, 129, 0.8)'
          }, {
            label: 'Pengeluaran',
            data: expenseData,
            backgroundColor: 'rgba(239, 68, 68, 0.8)'
          }]
          /* Commented dummy data for future testing
          datasets: [{
            label: 'Pemasukan',
            data: [200000000, 225000000, 190000000, 245000000, 210000000, 260000000],
            backgroundColor: 'rgba(16, 185, 129, 0.8)'
          }, {
            label: 'Pengeluaran',
            data: [150000000, 165000000, 140000000, 180000000, 160000000, 190000000],
            backgroundColor: 'rgba(239, 68, 68, 0.8)'
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return 'Rp ' + (value/1000000).toFixed(0) + 'jt';
                }
              }
            }
          }
        }
      });
    }

    // Debt Aging Chart
    const debtCtx = document.getElementById('debtAgingChart');
    if (debtCtx && typeof Chart !== 'undefined') {
      resetCanvas('debtAgingChart');

      // Use real data from server
      const debtAgingData = window.chartData?.debtAgingData || {};
      const debtValues = [
        debtAgingData['0-30'] || 150000000,
        debtAgingData['31-60'] || 85000000,
        debtAgingData['61-90'] || 45000000,
        debtAgingData['>90'] || 20000000
      ];

      chartInstances.debt = new Chart(debtCtx, {
        type: 'doughnut',
        data: {
          labels: ['0-30 Hari', '31-60 Hari', '61-90 Hari', '>90 Hari'],
          datasets: [{
            data: debtValues,
            backgroundColor: [
              'rgba(34, 197, 94, 0.8)',
              'rgba(251, 191, 36, 0.8)',
              'rgba(251, 146, 60, 0.8)',
              'rgba(239, 68, 68, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
          /* Commented dummy data for future testing
          datasets: [{
            data: [150000000, 85000000, 45000000, 20000000],
            backgroundColor: [
              'rgba(34, 197, 94, 0.8)',
              'rgba(251, 191, 36, 0.8)',
              'rgba(251, 146, 60, 0.8)',
              'rgba(239, 68, 68, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = 'Rp ' + context.parsed.toLocaleString('id-ID');
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = Math.round(context.parsed / total * 100);
                  return label + ': ' + value + ' (' + percentage + '%)';
                }
              }
            }
          }
        }
      });
    }

    // Employee Distribution Chart
    const empCtx = document.getElementById('employeeDistributionChart');
    if (empCtx && typeof Chart !== 'undefined') {
      resetCanvas('employeeDistributionChart');

      // Use real data from server
      const empData = window.chartData?.employeeDistributionData || {};
      const deptLabels = Object.keys(empData);
      const deptValues = Object.values(empData);

      chartInstances.employee = new Chart(empCtx, {
        type: 'pie',
        data: {
          labels: deptLabels.length ? deptLabels : ['Produksi', 'Administrasi', 'Keuangan', 'SDM', 'Umum'],
          datasets: [{
            data: deptValues.length ? deptValues : [45, 15, 10, 8, 12],
            backgroundColor: [
              'rgba(59, 130, 246, 0.8)',
              'rgba(34, 197, 94, 0.8)',
              'rgba(251, 191, 36, 0.8)',
              'rgba(168, 85, 247, 0.8)',
              'rgba(251, 146, 60, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
          /* Commented dummy data for future testing
          datasets: [{
            data: [45, 15, 10, 8, 12],
            backgroundColor: [
              'rgba(59, 130, 246, 0.8)',
              'rgba(34, 197, 94, 0.8)',
              'rgba(251, 191, 36, 0.8)',
              'rgba(168, 85, 247, 0.8)',
              'rgba(251, 146, 60, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.parsed + ' orang';
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = Math.round(context.parsed / total * 100);
                  return label + ': ' + value + ' (' + percentage + '%)';
                }
              }
            }
          }
        }
      });
    }

    // Top 5 Production by Division
    const topCtx = document.getElementById('topProductionChart');
    if (topCtx && typeof Chart !== 'undefined') {
      resetCanvas('topProductionChart');

      // Use real data from server
      const topDivisions = window.chartData?.topDivisions || [];
      const divisionLabels = topDivisions.map(d => d.name);
      const divisionValues = topDivisions.map(d => d.production);

      chartInstances.topProduction = new Chart(topCtx, {
        type: 'bar',
        data: {
          labels: divisionLabels.length ? divisionLabels : ['Afdeling 1', 'Afdeling 2', 'Afdeling 3', 'Afdeling 4', 'Afdeling 5'],
          datasets: [{
            label: 'Produksi (KG)',
            data: divisionValues.length ? divisionValues : [18500, 17200, 15600, 14200, 13500],
            backgroundColor: [
              'rgba(34, 197, 94, 0.8)',
              'rgba(34, 197, 94, 0.7)',
              'rgba(34, 197, 94, 0.6)',
              'rgba(34, 197, 94, 0.5)',
              'rgba(34, 197, 94, 0.4)'
            ]
          }]
          /* Commented dummy data for future testing
          datasets: [{
            label: 'Produksi (KG)',
            data: [18500, 17200, 15600, 14200, 13500],
            backgroundColor: [
              'rgba(34, 197, 94, 0.8)',
              'rgba(34, 197, 94, 0.7)',
              'rgba(34, 197, 94, 0.6)',
              'rgba(34, 197, 94, 0.5)',
              'rgba(34, 197, 94, 0.4)'
            ]
          }]
          */
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return 'Produksi: ' + context.parsed.x.toLocaleString('id-ID') + ' KG';
                }
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true
            }
          }
        }
      });
    }

    // Monthly Profit Margin Chart
    const profitCtx = document.getElementById('profitMarginChart');
    if (profitCtx && typeof Chart !== 'undefined') {
      resetCanvas('profitMarginChart');

      // Use real data from server
      const profitMarginData = window.chartData?.profitMarginData || {};
      const labels = Object.keys(profitMarginData);
      const marginValues = Object.values(profitMarginData);

      chartInstances.profit = new Chart(profitCtx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
          datasets: [{
            label: 'Margin Laba (%)',
            data: marginValues.length ? marginValues : [25, 26.7, 26.3, 26.5, 23.8, 26.9],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            tension: 0.4,
            fill: true
          }]
          /* Commented dummy data for future testing
          datasets: [{
            label: 'Margin Laba (%)',
            data: [25, 26.7, 26.3, 26.5, 23.8, 26.9],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            tension: 0.4,
            fill: true
          }]
          */
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return 'Margin Laba: ' + context.parsed.y + '%';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              min: 20,
              max: 30,
              ticks: {
                callback: function(value) {
                  return value + '%';
                }
              }
            }
          }
        }
          });
    }

      console.log('Charts initialized successfully');

      // Set up a mutation observer to handle chart re-initialization after Livewire updates
      if (!window.chartMutationObserver) {
        window.chartMutationObserver = new MutationObserver((mutations) => {
          let shouldReinit = false;
          mutations.forEach((mutation) => {
            if (mutation.type === 'childList' && mutation.target.classList.contains('h-80') ||
                mutation.target.classList.contains('h-64')) {
              // Check if a canvas was added or removed
              for (let node of mutation.addedNodes) {
                if (node.nodeName === 'CANVAS' || (node.querySelector && node.querySelector('canvas'))) {
                  shouldReinit = true;
                  break;
                }
              }
            }
          });

          if (shouldReinit) {
            setTimeout(() => {
              console.log('Reinitializing charts after DOM mutation...');
              window.initializeCharts();
            }, 200);
          }
        });

        // Start observing the document body for changes
        window.chartMutationObserver.observe(document.body, {
          childList: true,
          subtree: true
        });
      }
    }, 100); // Close setTimeout
  }

  // Initialize charts if we're on the dashboard
  if (window.location.pathname === '/dashboard') {
    // Initialize on first load with multiple fallbacks
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        // Also try after a short delay
        setTimeout(initializeCharts, 100);
      });
    } else {
      // DOM already loaded
      initializeCharts();
      setTimeout(initializeCharts, 100);
    }

    // Reinitialize after Livewire updates
    Livewire.hook('component.initialized', () => {
      setTimeout(initializeCharts, 100);
    });

    Livewire.hook('component.updated', () => {
      setTimeout(initializeCharts, 100);
    });

    Livewire.hook('message.processed', () => {
      setTimeout(initializeCharts, 100);
    });

    // Also initialize on window load
    window.addEventListener('load', function() {
      setTimeout(initializeCharts, 200);
    });
  }
});
