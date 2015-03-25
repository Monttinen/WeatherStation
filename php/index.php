<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <script type="text/javascript"
                src="https://www.google.com/jsapi?autoload={
                'modules':[{
                'name':'visualization',
                'version':'1',
                'packages':['corechart']
                }]
        }"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <?php
            session_start();
            if(isset($_POST['l'])&& preg_match("([0-9]+)", $_SESSION['l'])){
                $_SESSION['l'] = $_POST['l'];
            } else {
                $_SESSION['l'] = 24;
            }
            
            if(isset($_POST['s'])&& preg_match("([0-9]+)", $_SESSION['s'])){
                $_SESSION['s'] = $_POST['s'];
            }else {
                $_SESSION['s'] = 1;
            }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                console.log("sensor: <?php echo $_SESSION["s"]; ?>");
                $("#time").find('option[value="<?php echo $_SESSION['l']; ?>"]').attr("selected",true);
                
                var sensors = <?php include "getSensors.php"; ?>;
                for(var j = 0; j < sensors.length; j++){
                    $("#sensors").append('<option value="'+sensors[j].id+'">Sensor '+sensors[j].id+'</option>');
                }
                
                $("#sensors").find('option[value="<?php echo $_SESSION['s']; ?>"]').attr("selected",true);
                
                var formData = {l: "<?php echo $_SESSION["l"]; ?>", s: "<?php echo $_SESSION["s"]; ?>"};
                $.post("getMeasurements.php", formData, function(datain){

                        var json = datain;
                        // Parse unix timestamps into Date objects
                        for (var i = 0; i < json.rows.length; i++) {
                            json.rows[i].c[0].v = new Date(json.rows[i].c[0].v * 1000);
                        }

                        // Set the data and options for Google Chart
                        var data = new google.visualization.DataTable(json);
                        var options = {
                        title: 'Sensor <?php echo $_SESSION["s"]; ?>',
                                curveType: 'function',
                                legend: { position: 'bottom' },
                                vAxes: {0: {logScale: false, format: '# hPa'},
                                        1: {logScale: false, format: '# °C'}
                                },
                                hAxis: {
                                format: 'HH:mm'
                                },
                                series:{
                                0:{targetAxisIndex:0},
                                        1:{targetAxisIndex:1},
                                        2:{targetAxisIndex:1}
                                }
                        };
                        // Create and draw the chart to HTML
                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                        chart.draw(data, options);

                }, "json");
            });
        </script>
        <title>Weather Station</title>
    </head>
    <body>
        <div id="text" style="text-align: center;">
            <form id="form" method="post">
                Time:
                <select id="time" name="l" onchange="submit()">
                    <option value="1">1 hour</option>
                    <option value="3">3 hours</option>
                    <option value="12">12 hours</option>
                    <option value="24">1 day</option>
                    <option value="120">5 days</option>
                    <option value="168">7 days</option>
                    <option value="336">14 days</option>
                    <option value="720">30 days</option>
                </select>
                Sensor:
                <select id="sensors" name="s" onchange="submit()">
                </select>
            </form>
        </div>
        <div id="curve_chart" style="width: 90%; min-width: 800px; min-height: 500px; height: 70%">
        </div>

    </body>
</html>
