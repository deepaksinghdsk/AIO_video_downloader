<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-lg">
        <canvas id="lineChart"></canvas>
    </div>
    <div class="content">
        <?php if (!file_exists(__DIR__ . "/../../system/classes/youtube.class.php")) { ?>
            <div class="alert alert-warning">
                <span>You can install YouTube plugin by automatically <a href="?view=install-plugin">here</a>.</span>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="statistics">
                                    <div class="info">
                                        <div class="icon icon-primary">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                        </div>
                                        <h3 class="info-title"><?php echo database::allTotal("today"); ?></h3>
                                        <h6 class="stats-title">Today</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="statistics">
                                    <div class="info">
                                        <div class="icon icon-warning">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                        </div>
                                        <h3 class="info-title"><?php echo database::allTotal("yesterday"); ?></h3>
                                        <h6 class="stats-title">Yesterday</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="statistics">
                                    <div class="info">
                                        <div class="icon icon-success">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                        </div>
                                        <h3 class="info-title"><?php echo database::allTotal("week"); ?></h3>
                                        <h6 class="stats-title">This Week</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="statistics">
                                    <div class="info">
                                        <div class="icon icon-info">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                        </div>
                                        <h3 class="info-title"><?php echo database::allTotal("month"); ?></h3>
                                        <h6 class="stats-title">Last 30 Days</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="statistics">
                                    <div class="info">
                                        <div class="icon icon-danger">
                                            <i class="now-ui-icons arrows-1_cloud-download-93"></i>
                                        </div>
                                        <h3 class="info-title"><?php echo database::allTotal(); ?></h3>
                                        <h6 class="stats-title">All time</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-category">Downloads per Website <small>(Updated daily)</small></h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <div class="chartjs-size-monitor"
                                 style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas id="barChart" width="491" height="190" class="chartjs-render-monitor"
                                    style="display: block; width: 491px; height: 190px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 d-flex align-items-stretch">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-category">News</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php
                            $news = get_news();
                            if (!empty($news)) {
                                foreach ($news as $new) {
                                    echo "<li>";
                                    echo "<a target'_blank' href='" . $new["url"] . "'>" . $new["title"] . "</a>";
                                    echo "<small>(" . $new["date"] . ")</small>";
                                    echo "</li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx = document.getElementById("barChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    $stats = database::formatted_stats();
                    $stats = array_filter($stats, function ($x) {
                        return $x['title'] != 'total';
                    });
                    $labels = sprintf("'%s'", strtoupper(implode("','", array_column($stats, 'title'))));
                    echo $labels;
                    ?>
                ],
                datasets: [{
                    label: '# of Downloads',
                    data: [
                        <?php
                        $data = sprintf("'%s'", implode("','", array_column($stats, 'value')));
                        echo $data;
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 0,
                        bottom: 0
                    }
                },
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: '#fff',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest"
                },
                legend: {
                    position: "bottom",
                    fillStyle: "#FFF",
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontStyle: "bold",
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 10
                        },
                        gridLines: {
                            drawTicks: true,
                            drawBorder: false,
                            display: true,
                            color: "rgba(255,255,255,0.1)",
                            zeroLineColor: "transparent"
                        }

                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent",
                            display: false,

                        },
                        ticks: {
                            padding: 10,
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
        var ctx = document.getElementById("lineChart").getContext("2d");
        chartColor = "#FFFFFF";
        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, '#80b6f4');
        gradientStroke.addColorStop(1, chartColor);
        var gradientFill = ctx.createLinearGradient(0, 200, 0, 50);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
        gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: [
                    <?php
                    $rows = database::monthly_stats();
                    $labels = sprintf("'%s'", strtoupper(implode("','", array_column($rows, "date"))));
                    echo $labels;
                    ?>
                ],
                datasets: [{
                    label: '# of Downloads',
                    data: [
                        <?php
                        $data = sprintf("'%s'", implode("','", array_column($rows, "count")));
                        echo $data;
                        ?>
                    ],
                    backgroundColor: gradientFill,
                    borderColor: chartColor,
                    pointBorderColor: chartColor,
                    pointBackgroundColor: "#1e3d60",
                    pointHoverBackgroundColor: "#1e3d60",
                    pointHoverBorderColor: chartColor,
                    pointBorderWidth: 1,
                    pointHoverRadius: 7,
                    pointHoverBorderWidth: 2,
                    pointRadius: 5,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 0,
                        bottom: 0
                    }
                },
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: '#fff',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest"
                },
                legend: {
                    position: "bottom",
                    fillStyle: "#FFF",
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "rgba(255,255,255,0.4)",
                            fontStyle: "bold",
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 10
                        },
                        gridLines: {
                            drawTicks: true,
                            drawBorder: false,
                            display: true,
                            color: "rgba(255,255,255,0.1)",
                            zeroLineColor: "transparent"
                        }

                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent",
                            display: false,

                        },
                        ticks: {
                            padding: 10,
                            fontColor: "rgba(255,255,255,0.4)",
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
    </script>
<?php } else {
    http_response_code(403);
} ?>