<!-- Last Modified Date: 19-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@include('layouts.dashboard.chart-card',[
    'card_title' =>__('Compoundwisw Water Supply Length (m)') ,
    'export_chart_btn_id' => "exportwaterLengthPerWardChart",
    'canvas_id' => "waterLengthPerWardChart"
])
@push('scripts')
    <script>
        var ctx = document.getElementById("waterLengthPerWardChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', $watersupplyLenghtPerWardChart['labels']); ?>],
                datasets: [
                    {
                        label: "Wardwise Water Supply Length (m)",
                        backgroundColor: "rgb(157,208,246)",
                        borderColor: "rgb(157,208,246)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgb(157,208,246)",
                        hoverBorderColor: "rgb(157,208,246)",
                        data: [<?php echo implode(',', $watersupplyLenghtPerWardChart['values']); ?>],
                    }
                ]
            },
            options: {
                animation:{
                    animateScale:true
                },
                scales: {
                    xAxes: [{

            scaleLabel: {
                            display: true,
                            labelString: 'Compounds'
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
                        ticks: {
                            beginAtZero: true,
                            userCallback: function(label, index, labels) {
                     // when the floored value is the same as the value we have a whole number
                     if (Math.floor(label) === label) {
                         return label;
                     }

                 }
                        }
                    }]
                }
            }
        });
        document.getElementById('exportwaterLengthPerWardChart').addEventListener("click", downloadIMG);
        //donwload pdf from original canvas
        function downloadIMG() {
            var newCanvas = document.querySelector('#waterLengthPerWardChart');

            //create image from dummy canvas
            var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
            var a = document.createElement('a');
            a.href =newCanvas.toDataURL("image/png", 1.0);

            a.download = 'Wardwise Water Supply Length (m).png';

            // Trigger the download
            a.click();
        }
    </script>
@endpush
