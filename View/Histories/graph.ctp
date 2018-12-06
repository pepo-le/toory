<?php
$this->assign('title', '履歴グラフ');
$this->assign('css', '<link rel="stylesheet" type="text/css" href="/css/vendor/loaders.css">');
$this->assign('script', '
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js" integrity="sha256-oSgtFCCmHWRPQ/JmR4OoZ3Xke1Pw4v50uh6pLcu+fIc=" crossorigin="anonymous"></script>
    <script src="/js/chart.js"></script>
');
?>
<div class="p-chart-wrap">
    <canvas id="chart" class="p-chart"></canvas>
</div>
<div id="control" class="p-chart-control">
    <div id="loading" class="c-loading c-loading--top"><div></div></div>
    <button id="pprev" disabled=true>&lt;&lt;</button>
    <button id="prev" disabled=true>&lt;</button>
    <button id="next" disabled=true>&gt;</button>
    <button id="nnext" disabled=true>&gt;&gt;</button>
</div>
