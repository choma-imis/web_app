<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL) -->
@include('layouts.dashboard.chart-card',[
    'card_title' => __("Compoundwise Distribution of Containment Types"),
    'export_chart_btn_id' => "exportcontainmentTypesPerWardChart",
    'canvas_id' => "containmentTypesPerWardChart"
])

@push('scripts')
<script>
var ctx = document.getElementById("containmentTypesPerWardChart");

var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($containmentTypesPerWardChart->labels) !!},
        datasets: [
            @foreach($containmentTypesPerWardChart->datasets as $dataset)
            {
                label: {!! json_encode($dataset->label) !!},
                backgroundColor: {!! json_encode($dataset->color) !!},
                data: {!! json_encode($dataset->data) !!},
                values: {!! json_encode($dataset->value) !!}
            },
            @endforeach
        ]
    },
    options: {
        animation: {
            animateScale: true
        },
        responsive: true,
        legend: {
            display: true,
            position: 'bottom',
            align: 'start',
            labels: {
                boxWidth: 10
            }
        },
        scales: {
            xAxes: [{
                stacked: false,
                scaleLabel: {
                    display: true,
                    labelString: 'Wards',
                },
                ticks: {
                callback: function(value, index, values) {
                    const wardMap = {
                        1: 'Zambia Compound',
                        2: 'Overspill Compound'
                        // Add more mappings as needed
                    };
                    return wardMap[value] || value;
                }
        }
            }],
            yAxes: [{
                stacked: false,
                ticks: {
                    beginAtZero: true,
                    userCallback: function(label) {
                        if (Math.floor(label) === label) {
                            return label;
                        }
                    }
                }
            }]
        },
        tooltips: {
            mode: 'index',
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var label = dataset.label || '';
                    var value = dataset.values[tooltipItem.index];
                    return label + ': ' + value;
                }
            }
        },
        plugins: {
            datalabels: {
                color: 'white',
                font: {
                    weight: 'bold'
                },
                formatter: function(value) {
                    return Math.round(value) + '%';
                }
            }
        }
    }
});

document.getElementById('exportcontainmentTypesPerWardChart').addEventListener("click", downloadIMG);

function downloadIMG() {
    var canvas = document.getElementById("containmentTypesPerWardChart");
    var image = canvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href = image;
    a.download = 'Wardwise Distribution of Containment Types by Wards.png';
    a.click();
}
</script>
@endpush
