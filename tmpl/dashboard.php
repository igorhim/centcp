      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php include('breadcrumb.php')?>

        <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Load average</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $loadAverage[0]?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $loadAverage[1]?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $loadAverage[2]?>
                                </div>
                            </div>
                    </div><!-- /.box-body -->
                </div>
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Uptime</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                            <?php echo $uptime ?>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mounted Filesystems</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding" style="display: block;">
                        <table class="table">
                        <tr><th>Filesystem</th><th>Size</th><th>Used</th><th> Avail</th><th>Use%</th><th> Mounted on</th></tr>
                        <?php foreach($diskspace as $row) {
                            echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td><td>' . $row[5] . '</td></tr>';
                        }
                        ?>
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Services</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding" style="display: block;">
                        <table class="table">
                            <tr><td>Nginx</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>
                            <tr><td>PHP-FPM</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>
                            <tr><td>MariaDB</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>
                            <!--<tr><td>Memcached</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>-->
                            <!--<tr><td>CSF Firewall</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>-->
                            <tr><td>Pure-FTPD</td><td><i class="fa fa-check-square" style="color: green; font-size:20px"></i></td></tr>
                            <tr><td>MongoDB</td><td><i class="fa fa-minus-square" style="color: red; font-size:20px"></i></td></tr>
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div style="text-align:left">
                    <select id="time-range" class="form-control" style="margin-bottom: 15px;">
                        <option value="3600000" selected="selected">1 hour</option>
                        <option value="7200000">2 hours</option>
                        <option value="21600000">6 hours</option>
                        <option value="43200000">12 hours</option>
                        <option value="86400000">1 day</option>
                        <option value="172800000">2 days</option>
                        <option value="259200000">3 days</option>
                        <option value="345600000">4 days</option>
                        <option value="604800000">1 week</option>
                        <option value="1209600000">2 weeks</option>
                        <option value="2419200000">1 month</option>
                        <option value="4838400000">2 months</option>
                        <option value="7257600000">3 months</option>
                        <option value="14515200000">6 months</option>
                        <option value="29030400000">1 year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <div id="charts"></div>
            <script language="javascript" type="text/javascript" src="/src/bignas-charts/js/highcharts.js"></script>
            <script type="text/javascript">
                var charts = {},
                    timeSpan = $('#time-range').val(),
                    refreshInterval
            
                $(document).ready(function() {
                    $('#time-range').change(function () {
                        timeSpan = this.value
                        clearInterval(refreshInterval)
                        $('#charts').empty()
                        initGraphs()
                    })
            
                    function drawGraphs (graphs) {
                        $.each(graphs, function (graph, details) {
                            console.log(details);
                            //if((typeof details.series.data != 'undefined' && details.series.data.length) || (typeof details.series[0].data != 'undefined' && details.series[0].data.length)) {
                                $('#charts').append(
                                '<div class="box box-info">'+
                                    '<div class="box-header with-border">'+
                                        '<h3 class="box-title">' + details.title.text + '</h3>'+
                                        '<div class="box-tools pull-right">'+
                                            '<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>'+
                                        '</div><!-- /.box-tools -->'+
                                    '</div><!-- /.box-header -->'+
                                    '<div class="box-body" id="' + graph + '" style="display: block; height:300px">'+
                                    '</div><!-- /.box-body -->'+
                                    '</div>'
                                );
                                details.title.text = '';
                                charts[graph] = new Highcharts.Chart($.extend(details, { chart: { renderTo: graph }}))
                            //}
                        })
                    }
            
                    function updateGraphs (graphs) {
                        $.each(graphs, function (graph, details) {
                            $.each(details.series, function (index, series) {
                                //if ((typeof series.data != 'undefined' && series.data.length) || (typeof series[0].data != 'undefined' && series[0].data.length)) { 
                                    charts[graph].series[index].setData(series.data)
                                //}
                            })
                        })
                    }
            
                    function initGraphs () {
                        $.ajax({
                            url: '/src/bignas-charts/highcharts/rrd.php?start=' + (Date.now() - timeSpan)
                            , method: 'get'
                            , dataType: 'json'
                            , success: drawGraphs
                        })
                    }
            
                    refreshInterval = setInterval(function () {
                        $.ajax({
                            url: '/src/bignas-charts/highcharts/rrd.php?start=' + (Date.now() - timeSpan)
                            , method: 'get'
                            , dataType: 'json'
                            , success: updateGraphs
                        })
                    }, 10000)
            
                    initGraphs()
                })
            </script>
            </div>
        </div>
        </section>
    </div>
