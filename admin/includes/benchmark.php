<?php if (isset($_SESSION["logged"]) === true) { ?>
    <?php
    set_time_limit(120);
    $score = 0;
    $options = array();
    for ($i = 0; $i < 5; $i++) {
        $benchmarkResult = test_benchmark($options);
        $completeTime = (float)$benchmarkResult['benchmark']['total'];
        $score += round(5000 / $completeTime);
    }
    $score = (int)($score / 5);
    ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Benchmark</h5>
                        <p class="category"><a href="./?view=about">Return About</a></p>
                    </div>
                    <div class="card-body">
                        <div class="content">
                            <strong>Score</strong>
                            <pre><?php echo $score; ?></pre>
                        </div>
                        <div class="content">
                            <p>
                                <strong>What does it mean?</strong><br>
                                If your score is under 2000, your server performance is bad. Scores from 2000 to 10000
                                mean
                                moderate performance. If score is above 10000 you do not need to do anything, server
                                performance perfect :)
                                <br><strong>Note</strong><br>
                                Changes in the system load can cause small differences in the score.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
}

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

function test_benchmark($settings)
{
    $result = array();
    $result['version'] = '1.2';
    $result['sysinfo']['time'] = date('Y-m-d H:i:s');
    $result['sysinfo']['php_version'] = PHP_VERSION;
    $result['sysinfo']['platform'] = PHP_OS;
    $result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
    $result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];
    $result['sysinfo']['xdebug'] = in_array('xdebug', get_loaded_extensions());
    $timeStart = microtime(true);
    test_math($result);
    test_string($result);
    test_loops($result);
    test_ifelse($result);
    $result['benchmark']['calculation_total'] = timer_diff($timeStart) . ' sec.';
    $result['benchmark']['total'] = timer_diff($timeStart) . ' sec.';
    return $result;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
        }
    }
    $result['benchmark']['math'] = timer_diff($timeStart) . ' sec.';
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");
    $string = 'the quick brown fox jumps over the lazy dog';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    $result['benchmark']['string'] = timer_diff($timeStart) . ' sec.';
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {

    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    $result['benchmark']['loops'] = timer_diff($timeStart) . ' sec.';
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {

        } elseif ($i == -2) {

        } else {
            if ($i == -3) {

            }
        }
    }
    $result['benchmark']['ifelse'] = timer_diff($timeStart) . ' sec.';
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 3);
}

function h($v)
{
    return htmlentities($v);
}